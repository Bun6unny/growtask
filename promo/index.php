<?php
header('Content-Type: application/json');

$promo = trim($_POST['promo'] ?? '');
$validPromo = "GROWTASK2026";

if ($promo == $validPromo) {
    echo json_encode([
        'valid' => true
    ]);
} else {
    echo json_encode([
        'valid' => false
    ]);
}