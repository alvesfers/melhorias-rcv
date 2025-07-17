<?php
// header.php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR" x-data="app()" x-init="init()">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Receiv · Planejamento</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        nav ul li button {
            padding: 1rem;
        }

        nav ul li button:hover {
            color: #ff4b14;
            padding: 0.5rem;
            background-color: #2d3748;
        }

        li ul li{
            padding: 0.3rem 0rem 0rem 0rem;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="flex flex-col h-screen">

        <header class="flex items-center justify-between bg-gray-900 h-12 px-4 border-b border-gray-800">
            <img src="logo.png" alt="Receiv" class="h-6 w-auto">

            <div class="flex items-center space-x-2 mx-auto">
                <input type="text" placeholder="Cliente"
                    class="h-8 px-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500">
                <select class="h-8 px-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500">
                    <option>Tipo</option>
                </select>
                <select class="h-8 px-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500">
                    <option>Credor</option>
                </select>
                <select class="h-8 px-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500">
                    <option>Ativos/Inativos</option>
                </select>
                <button class="h-8 w-8 flex items-center justify-center bg-blue-500 text-white rounded text-sm hover:bg-blue-600 transition">
                    <i class="fas fa-search text-xs"></i>
                </button>
            </div>

            <a href="#" class="text-green-400 text-2xl hover:text-green-500">
                <i class="fab fa-whatsapp"></i>
            </a>
        </header>

        <div class="flex flex-1 overflow-hidden">

            <aside x-data="{ open: true }"
                :class="open ? 'w-64' : 'w-16'"
                class="bg-gray-900 text-white flex flex-col transition-all duration-200">

                <div class="flex items-center justify-between h-12 px-4">

                    <button @click="open = !open" class="p-2 hover:bg-gray-800 rounded">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>

                <div x-show="open" class="px-3 mt-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Buscar"
                            class="w-full pl-10 pr-3 py-2 bg-gray-800 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>

                <nav class="mt-4 flex-1 overflow-auto">
                    <ul class="space-y-1 px-2">
                        <?php
                        $mods = $pdo->query("SELECT id,name,icon FROM modules ORDER BY display_order")
                            ->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($mods as $m): ?>
                            <li x-data="{ openItem: false }">
                                <button @click="openItem = !openItem"
                                    class="flex w-full items-center space-x-3 px-2 py-2 rounded hover:bg-gray-800 transition">
                                    <i class="<?= $m['icon'] ?> w-5 text-center"></i>
                                    <span x-show="open" class="flex-1 text-sm text-left"><?= htmlspecialchars($m['name']) ?></span>
                                    <i x-show="open"
                                        class="fas fa-chevron-down transition-transform"
                                        :class="openItem ? 'rotate-180' : ''"></i>
                                </button>
                                <ul x-show="open && openItem" class="mt-1 space-y-1 pl-3" x-collapse>
                                    <?php
                                    // Pega as ferramentas deste módulo
                                    $stmt = $pdo->prepare("SELECT name,link FROM tools WHERE module_id=? ORDER BY display_order");
                                    $stmt->execute([$m['id']]);
                                    while ($t = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                        <li>
                                            <a href="<?= htmlspecialchars($t['link']) ?>"
                                                class="flex items-center space-x-2 px-2 py-1 rounded hover:bg-gray-800 transition">
                                                <i class="fas fa-chevron-right w-4"></i>
                                                <span class="text-sm"><?= htmlspecialchars($t['name']) ?></span>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <div x-show="open" class="p-4 border-t border-gray-800">
                    <a href="#" class="flex items-center space-x-2 hover:text-red-400 transition">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="text-sm">Sair</span>
                    </a>
                </div>
            </aside>