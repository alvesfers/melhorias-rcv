<?php
require 'config.php';
header('Content-Type: application/json');

$body = file_get_contents('php://input');
$data = json_decode($body, true);
if (!is_array($data)) {
    http_response_code(400);
    exit(json_encode(['error' => 'JSON inválido']));
}

$updateStmt = $pdo->prepare("
    UPDATE tools 
    SET level = ?, update_text = ?, difficulty = ?, devs = ?, hours = ?
    WHERE id = ?
");

$pdo->beginTransaction();
try {
    foreach ($data as $module) {
        if (!isset($module['tools']) || !is_array($module['tools'])) continue;
        foreach ($module['tools'] as $tool) {
            $updateStmt->execute([
                (int)$tool['level'],
                $tool['update'],
                $tool['difficulty'],
                (int)$tool['devs'],
                (int)$tool['hours'],
                (int)$tool['id']
            ]);
        }
    }
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
