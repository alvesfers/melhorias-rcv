<?php

if (! function_exists('loadEnv')) {
    function loadEnv(string $filePath): void
    {
        if (! file_exists($filePath)) {
            throw new Exception("Arquivo .env não encontrado: {$filePath}");
        }
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }
            list($name, $value) = explode('=', $line, 2);
            $name  = trim($name);
            $value = trim($value);
            if ((substr($value, 0, 1) === '"'  && substr($value, -1) === '"')
                || (substr($value, 0, 1) === "'" && substr($value, -1) === "'")
            ) {
                $value = substr($value, 1, -1);
            }
            if (! isset($_ENV[$name])) {
                $_ENV[$name] = $value;
            }
        }
    }
}

loadEnv(__DIR__ . '/.env');

$host    = $_ENV['DB_HOST']     ?? '127.0.0.1';
$port    = $_ENV['DB_PORT']     ?? '3306';
$db      = $_ENV['DB_NAME']     ?? '';      
$user    = $_ENV['DB_USER']     ?? '';        
$pass    = $_ENV['DB_PASS']     ?? '';   
$charset = $_ENV['DB_CHARSET']  ?? 'utf8mb4';

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new RuntimeException('Erro ao conectar ao banco: ' . $e->getMessage());
}
