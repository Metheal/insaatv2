<?php
require '../../config/config.php';
require 'response.php';

function createWorksite($pdo)
{

    $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $initialPrice = isset($_POST['initialPrice']) ? floatval($_POST['initialPrice']) : 0.0;
    $materials = isset($_POST['materials']) ? $_POST['materials'] : [];

    if (!$customer_id || !$address || !$description || !$initialPrice) {
        respondWithError('All fields are required');
    }

    $cost = 0;
    foreach ($materials as $material) {
        if (!isset($material['quantity'], $material['cost_by_piece'])) {
            respondWithError('Invalid material data provided');
        }
        $cost += $material['quantity'] * $material['cost_by_piece'];
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            "INSERT INTO worksites (address, description, initial_price, cost, in_progress) 
            VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$address, $description, $initialPrice, $cost, true]);
        $worksite_id = $pdo->lastInsertId();

        $insertWorksiteExpenses = $pdo->prepare(
            "INSERT INTO worksite_expenses (worksite_id, material_id, quantity, cost_by_piece) 
            VALUES (?, ?, ?, ?)"
        );
        $updateMainStorage = $pdo->prepare(
            "UPDATE main_storage SET quantity = quantity - ? WHERE id = ?"
        );

        foreach ($materials as $material) {
            $material_id = $material['material_id'];
            $quantity = $material['quantity'];
            $cost_by_piece = $material['cost_by_piece'];
            $isFromStorage = isset($material['from_storage']) && $material['from_storage'];

            if (!isset($material['material_id'], $material['quantity'], $material['cost_by_piece'])) {
                respondWithError('Invalid material data: ' . json_encode($material));
            }

            print_r($isFromStorage);

            if ($isFromStorage && isset($material['id'])) {

                error_log("Updating main_storage: Reducing quantity by $quantity for id {$material['id']}");

                $updateResult = $updateMainStorage->execute([$quantity, $material['id']]);

                if ($updateResult === false) {
                    throw new Exception('Failed to update main_storage: ' . implode(' ', $updateMainStorage->errorInfo()));
                }
            }

            $insertResult = $insertWorksiteExpenses->execute([$worksite_id, $material_id, $quantity, $cost_by_piece]);

            if ($insertResult === false) {
                throw new Exception('Failed to insert into worksite_expenses: ' . implode(' ', $insertWorksiteExpenses->errorInfo()));
            }
        }

        $stmt = $pdo->prepare(
            "INSERT INTO customer_worksites (customer_id, worksite_id) 
            VALUES (?, ?)"
        );
        $stmt->execute([$customer_id, $worksite_id]);

        $pdo->commit();
        respondWithSuccess('Worksite created successfully');
    } catch (Exception $e) {
        $pdo->rollBack();
        respondWithError('Error: ' . $e->getMessage());
    }
}

function readWorksites($pdo)
{
    try {
        $stmt = $pdo->query("
            SELECT 
                w.id,
                w.description,
                w.address,
                w.initial_price,
                w.cost,
                w.in_progress,
                c.fullname AS customer,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'material_id', we.material_id,
                        'material_name', m.name,
                        'quantity', we.quantity,
                        'cost_by_piece', we.cost_by_piece
                    )
                ) AS materials,
                COALESCE(total_paid.total_amount, 0) AS total_paid
            FROM 
                worksites AS w
            JOIN 
                customer_worksites AS cw ON cw.worksite_id = w.id
            JOIN 
                customers AS c ON c.id = cw.customer_id
            JOIN 
                worksite_expenses AS we ON w.id = we.worksite_id
            JOIN 
                materials AS m ON we.material_id = m.id
            LEFT JOIN
                (
                    SELECT 
                        worksite_id, 
                        SUM(amount) AS total_amount
                    FROM 
                        payments
                    GROUP BY 
                        worksite_id
                ) AS total_paid ON total_paid.worksite_id = w.id
            GROUP BY 
                w.id, c.fullname, total_paid.total_amount;

        ");

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        respondWithSuccess('Worksites retrieved successfully', ['data' => $data]);
    } catch (Exception $e) {
        respondWithError('Failed to retrieve worksites: ' . $e->getMessage());
    }
}

function updateWorksite($pdo)
{

    $worksite_id = isset($_POST['worksite_id']) ? intval($_POST['worksite_id']) : null;
    $materials = isset($_POST['materials']) ? $_POST['materials'] : [];
    $in_progress = $_POST['in_progress'];

    if (!$worksite_id) {
        respondWithError('All fields are required');
    }

    $cost = 0;
    foreach ($materials as $material) {
        if (!isset($material['quantity'], $material['cost_by_piece'])) {
            respondWithError('Invalid material data provided');
        }
        $cost += $material['quantity'] * $material['cost_by_piece'];
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE worksites SET cost = ?, in_progress = ? WHERE id = ?");
        $stmt->execute([$cost, $in_progress, $worksite_id]);

        $insertWorksiteExpenses = $pdo->prepare(
            "INSERT INTO worksite_expenses (worksite_id, material_id, quantity, cost_by_piece) 
            VALUES (?, ?, ?, ?)"
        );
        $updateMainStorage = $pdo->prepare(
            "UPDATE main_storage SET quantity = quantity - ? WHERE id = ?"
        );
        $deleteWorksiteExpenses = $pdo->prepare(
            "DELETE FROM worksite_expenses WHERE worksite_id = ?"
        );

        foreach ($materials as $material) {
            $deleteWorksiteExpenses->execute([$worksite_id]);
        }

        foreach ($materials as $material) {
            $material_id = $material['material_id'];
            $quantity = $material['quantity'];
            $cost_by_piece = $material['cost_by_piece'];
            $isFromStorage = isset($material['from_storage']) && $material['from_storage'];

            if (!isset($material['material_id'], $material['quantity'], $material['cost_by_piece'])) {
                respondWithError('Invalid material data: ' . json_encode($material));
            }

            if ($isFromStorage && isset($material['id'])) {

                error_log("Updating main_storage: Reducing quantity by $quantity for id {$material['id']}");

                $updateResult = $updateMainStorage->execute([$quantity, $material['id']]);

                if ($updateResult === false) {
                    throw new Exception('Failed to update main_storage: ' . implode(' ', $updateMainStorage->errorInfo()));
                }
            }

            $insertResult = $insertWorksiteExpenses->execute([$worksite_id, $material_id, $quantity, $cost_by_piece]);

            if ($insertResult === false) {
                throw new Exception('Failed to insert into worksite_expenses: ' . implode(' ', $insertWorksiteExpenses->errorInfo()));
            }
        }



        $pdo->commit();
        respondWithSuccess('Worksite updated successfully');
    } catch (Exception $e) {
        $pdo->rollBack();
        respondWithError('Error: ' . $e->getMessage());
    }
}

function deleteWorksite($pdo)
{
    $id = $_POST['id'] ?? '';
    if (empty($id)) {
        respondWithError('ID is required');
    }

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM worksites WHERE id = ?");
        $stmt->execute([$id]);
        $pdo->commit();
        respondWithSuccess('Worksite deleted successfully');
    } catch (Exception $e) {
        $pdo->rollBack();
        respondWithError('Failed to delete worksite: ' . $e->getMessage());
    }
}

function getMaterialsAndMainStorage($pdo)
{
    try {

        $materialsQuery = "SELECT id as material_id, name FROM materials";
        $materialsStmt = $pdo->query($materialsQuery);
        $materials = $materialsStmt->fetchAll(PDO::FETCH_ASSOC);

        $mainStorageQuery = "
            SELECT ms.id, ms.material_id, ms.quantity, ms.cost_by_piece, m.name as material_name
            FROM main_storage ms
            JOIN materials m ON ms.material_id = m.id
        ";
        $mainStorageStmt = $pdo->query($mainStorageQuery);
        $mainStorage = $mainStorageStmt->fetchAll(PDO::FETCH_ASSOC);

        respondWithSuccess('Materials and storage data retrieved successfully', [
            'materials' => $materials,
            'main_storage' => $mainStorage
        ]);
    } catch (Exception $e) {
        respondWithError('Failed to retrieve materials and storage data: ' . $e->getMessage());
    }
}

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            createWorksite($pdo);
            break;
        case 'read':
            readWorksites($pdo);
            break;
        case 'update':
            updateWorksite($pdo);
            break;
        case 'delete':
            deleteWorksite($pdo);
            break;
        case 'getMaterialsAndMainStorage':
            getMaterialsAndMainStorage($pdo);
            break;
        default:
            respondWithError('Invalid action');
    }
} catch (PDOException $e) {
    respondWithError('Database error: ' . $e->getMessage());
}
