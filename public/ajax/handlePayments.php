<?php
require '../../config/config.php';
require 'response.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

try {
    switch ($action) {
        case 'create':
            createPayment($pdo);
            break;
        case 'read':
            readPayments($pdo);
            break;
        case 'readPaymentTypes':
            readPaymentTypes($pdo);
            break;
        case 'update':
            updatePayment($pdo);
            break;
        case 'delete':
            deletePayment($pdo);
            break;
        default:
            respondWithError('Invalid action.');
    }
} catch (\Throwable $e) {

    respondWithError($e->getMessage());
}

function createPayment($pdo)
{
    $paymentType = $_POST['payment_type'] ?? '';
    $worksiteId = $_POST['worksite_id'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if ($paymentType && $worksiteId && $amount) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO payments (payment_type, worksite_id, amount) VALUES (?, ?, ?)");
            $stmt->execute([$paymentType, $worksiteId, $amount]);
            $pdo->commit();
            respondWithSuccess('Payment added successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to add payment: ' . $e->getMessage());
        }
    } else {
        respondWithError('Fields Are Required');
    }
}

function readPayments($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT p.id, p.worksite_id, pt.id AS payment_id, pt.name, p.amount FROM payments AS p JOIN payment_types AS pt on pt.id = p.payment_type");
        $stmt->execute();
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respondWithSuccess('', ['data' => $payments]);
    } catch (\Throwable $e) {
        respondWithError('Failed to retrieve payments: ' . $e->getMessage());
    }
}

function readPaymentTypes($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * from payment_types");
        $stmt->execute();
        $paymentTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respondWithSuccess('', ['data' => $paymentTypes]);
    } catch (\Throwable $e) {
        respondWithError('Failed to retrieve payment types: ' . $e->getMessage());
    }
}

function updatePayment($pdo)
{
    $editId = $_POST['editId'] ?? '';
    $editAmount = $_POST['editAmount'] ?? '';

    if ($editId && $editAmount) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("UPDATE payments SET amount = ? WHERE id  = ?");
            $stmt->execute([$editAmount, $editId]);
            $pdo->commit();
            respondWithSuccess('Payment updated successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to update payment: ' . $e->getMessage());
        }
    } else {
        respondWithError('Fields are required.');
    }
}

function deletePayment($pdo)
{
    $id = $_POST['id'] ?? '';

    if ($id) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM payments WHERE id = ?");
            $stmt->execute([$id]);
            $pdo->commit();
            respondWithSuccess('Payment deleted successfully.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            respondWithError('Failed to delete payment: ' . $e->getMessage());
        }
    } else {
        respondWithError('Invalid payment ID.');
    }
}
