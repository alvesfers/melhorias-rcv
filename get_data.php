<?php

require 'config.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, name FROM modules ORDER BY display_order");
$modules = [];
while ($mod = $stmt->fetch()) {
    $toolStmt = $pdo->prepare("
        SELECT 
          id, name, level, update_text AS `update`, difficulty, devs, hours 
        FROM tools 
        WHERE module_id = ? 
        ORDER BY display_order
    ");
    $toolStmt->execute([$mod['id']]);
    $tools = $toolStmt->fetchAll();
    $modules[] = [
        'id'    => (int)$mod['id'],
        'name'  => $mod['name'],
        'tools' => array_map(function ($t) {
            // castar tipos para JS
            return [
                'id'     => (int)$t['id'],
                'name'   => $t['name'],
                'level'  => (int)$t['level'],
                'update' => $t['update'],
                'difficulty' => $t['difficulty'],
                'devs'   => (int)$t['devs'],
                'hours'  => (int)$t['hours'],
            ];
        }, $tools)
    ];
}

echo json_encode($modules, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
