<?php
require '../../config/config.php';
require 'response.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

try {
    switch ($action) {
        case 'create':
            createCustomer($pdo);
            break;
        case 'read':
            readCustomers($pdo);
            break;
        case 'update':
            updateCustomer($pdo);
            break;
        case 'delete':
            deleteCustomer($pdo);
            break;
        default:
            respondWithError('Invalid action.');
    }
} catch (\Throwable $e) {

    respondWithError('An error occurred: ' . $e->getMessage());
}

function createCustomer($pdo)
{
    $fullname = $_POST['fullname'] ?? '';
    $gsm = $_POST['gsm'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($fullname && $gsm && $email) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO customers (fullname, gsm, email) VALUES (?, ?, ?)");
            $stmt->execute([$fullname, $gsm, $email]);
            $pdo->commit();
            respondWithSuccess('Customer added successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to add customer: ' . $e->getMessage());
        }
    } else {
        respondWithError('All fields are required.');
    }
}

function readCustomers($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM customers");
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respondWithSuccess('', ['data' => $customers]);
    } catch (\Throwable $e) {
        respondWithError('Failed to retrieve customers: ' . $e->getMessage());
    }
}

function updateCustomer($pdo)
{
    $id = $_POST['id'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $gsm = $_POST['gsm'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($id && $fullname && $gsm && $email) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("UPDATE customers SET fullname = ?, gsm = ?, email = ? WHERE id = ?");
            $stmt->execute([$fullname, $gsm, $email, $id]);
            $pdo->commit();
            respondWithSuccess('Customer updated successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to update customer: ' . $e->getMessage());
        }
    } else {
        respondWithError('All fields are required.');
    }
}

function deleteCustomer($pdo)
{
    $id = $_POST['id'] ?? '';

    if ($id) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
            $stmt->execute([$id]);
            $pdo->commit();
            respondWithSuccess('Customer deleted successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to delete customer: ' . $e->getMessage());
        }
    } else {
        respondWithError('Invalid customer ID.');
    }
}
