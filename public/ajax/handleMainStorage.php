<?php
require '../../config/config.php';
require 'response.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'create':
        createMainStorage($pdo);
        break;
    case 'read':
        readMainStorage($pdo);
        break;
    case 'readAverage':
        readMainStorageAverage($pdo);
        break;
    case 'update':
        updateMainStorage($pdo);
        break;
    case 'delete':
        deleteMainStorage($pdo);
        break;
    default:
        respondWithError('Invalid action.');
}

function createMainStorage($pdo)
{
    $material_id = isset($_POST['material_id']) ? intval($_POST['material_id']) : null;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;
    $cost_by_piece = isset($_POST['cost_by_piece']) ? floatval($_POST['cost_by_piece']) : null;

    if ($material_id !== null && $quantity !== null && $cost_by_piece !== null) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT id, quantity FROM main_storage WHERE material_id = ? AND cost_by_piece = ?");
            $stmt->execute([$material_id, $cost_by_piece]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($record) {

                $stmt = $pdo->prepare("UPDATE main_storage SET quantity = quantity + ? WHERE id = ?");
                $stmt->execute([$quantity, $record['id']]);
            } else {

                $stmt = $pdo->prepare("INSERT INTO main_storage (material_id, quantity, cost_by_piece) VALUES (?, ?, ?)");
                $stmt->execute([$material_id, $quantity, $cost_by_piece]);
            }

            $pdo->commit();
            respondWithSuccess('Material added successfully.');
        } catch (Exception $e) {
            $pdo->rollBack();
            respondWithError('Failed to add material: ' . $e->getMessage());
        }
    } else {
        respondWithError('All fields are required.');
    }
}

function readMainStorage($pdo)
{
    $stmt = $pdo->prepare("SELECT main_storage.*, materials.name FROM main_storage 
                            JOIN materials ON main_storage.material_id = materials.id");
    if ($stmt->execute()) {
        $mainStorageEntries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respondWithSuccess('', ['data' => $mainStorageEntries]);
    } else {
        respondWithError('Failed to retrieve materials.');
    }
}

function readMainStorageAverage($pdo)
{
    $stmt = $pdo->prepare("SELECT 
                                materials.name, 
                                SUM(main_storage.quantity) AS total_quantity, 
                                SUM(main_storage.quantity * main_storage.cost_by_piece) / NULLIF(SUM(main_storage.quantity), 0) AS average_cost
                            FROM main_storage 
                            JOIN materials ON main_storage.material_id = materials.id 
                            GROUP BY materials.id;");
    if ($stmt->execute()) {
        $mainStorageEntries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respondWithSuccess('', ['data' => $mainStorageEntries]);
    } else {
        respondWithError('Failed to retrieve materials.');
    }
}

function updateMainStorage($pdo)
{
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $cost_by_piece = $_POST['cost_by_piece'];

    if ($id && $quantity && $cost_by_piece) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE main_storage SET quantity = ?, cost_by_piece = ? WHERE id = ?");
            $stmt->execute([$quantity, $cost_by_piece, $id]);

            $pdo->commit();
            respondWithSuccess('Material updated successfully.');
        } catch (\Throwable $e) {
            $pdo->rollback();
            respondWithError('Failed to update material.');
        }
    } else {
        respondWithError('All fields are required.');
    }
}

function deleteMainStorage($pdo)
{
    $id = $_POST['id'];

    if ($id) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("DELETE FROM main_storage WHERE id = ?");
            $stmt->execute([$id]);

            $pdo->commit();
            respondWithSuccess('Material deleted successfully.');
        } catch (\Throwable $e) {
            $pdo->rollback();
            respondWithError('Failed to delete material.');
        }
    } else {
        respondWithError('Invalid material ID.');
    }
}
