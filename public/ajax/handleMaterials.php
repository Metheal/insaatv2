<?php
require '../../config/config.php';
require 'response.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

try {
    switch ($action) {
        case 'create':
            createMaterial($pdo);
            break;
        case 'read':
            readMaterials($pdo);
            break;
        case 'update':
            updateMaterial($pdo);
            break;
        case 'delete':
            deleteMaterial($pdo);
            break;
        default:
            respondWithError('Invalid action.');
    }
} catch (\Throwable $e) {

    respondWithError($e->getMessage());
}

function createMaterial($pdo)
{
    $name = $_POST['name'] ?? '';

    if ($name) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO materials (name) VALUES (?)");
            $stmt->execute([$name]);
            $pdo->commit();
            respondWithSuccess('Material added successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to add material: ' . $e->getMessage());
        }
    } else {
        respondWithError('Name field is required.');
    }
}

function readMaterials($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM materials");
        $stmt->execute();
        $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respondWithSuccess('', ['data' => $materials]);
    } catch (\Throwable $e) {
        respondWithError('Failed to retrieve materials: ' . $e->getMessage());
    }
}

function updateMaterial($pdo)
{
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';

    if ($id && $name) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("UPDATE materials SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            $pdo->commit();
            respondWithSuccess('Material updated successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to update material: ' . $e->getMessage());
        }
    } else {
        respondWithError('ID and name fields are required.');
    }
}

function deleteMaterial($pdo)
{
    $id = $_POST['id'] ?? '';

    if ($id) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM materials WHERE id = ?");
            $stmt->execute([$id]);
            $pdo->commit();
            respondWithSuccess('Material deleted successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to delete material: ' . $e->getMessage());
        }
    } else {
        respondWithError('Invalid material ID.');
    }
}
