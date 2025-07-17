<?php
require 'config.php';
header('Content-Type: application/json');

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (
    !is_array($data) ||
    !isset($data['id'], $data['level'], $data['update'], $data['difficulty'], $data['devs'], $data['hours'])
) {
    http_response_code(400);
    exit(json_encode(['error' => 'Dados inválidos']));
}

$stmt = $pdo->prepare("
    UPDATE tools
    SET level = ?, update_text = ?, difficulty = ?, devs = ?, hours = ?
    WHERE id = ?
");
try {
    $stmt->execute([
        (int)$data['level'],
        $data['update'],
        $data['difficulty'],
        (int)$data['devs'],
        (int)$data['hours'],
        (int)$data['id']
    ]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
