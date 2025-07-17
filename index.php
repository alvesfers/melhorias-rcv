<?php
require 'config.php';
?>
<?php include 'header.php'; ?>

<main class="flex-1 overflow-auto container mx-auto px-6 py-8">

    <section id="proposal" class="mb-12 bg-white rounded-lg shadow">
        <header class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-900">Proposta de Melhoria Geral</h2>
            <p class="mt-1 text-gray-600">
                Resumo executivo das três iniciativas principais para a plataforma Receiv.
            </p>
        </header>
        <div class="px-6 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-5 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-blue-600 mb-2">Dashboard de Métricas Dinâmicas</h3>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>Indicadores em tempo real</li>
                    <li>Filtros por usuário, equipe e período</li>
                    <li>Exportação direta de relatórios</li>
                </ul>
            </div>
            <div class="p-5 bg-gray-50 rounded-lg border-l-4 border-orange-500">
                <h3 class="text-lg font-bold text-orange-500 mb-2">Formulário de Feedback In-App</h3>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>Coleta de comentários sem sair da plataforma</li>
                    <li>Integração com canal de suporte interno</li>
                    <li>Relatórios de satisfação em dashboard</li>
                </ul>
            </div>
            <div class="p-5 bg-gray-50 rounded-lg border-l-4 border-green-500">
                <h3 class="text-lg font-bold text-green-500 mb-2">Otimização de Performance</h3>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>Revisão e indexação de queries críticas</li>
                    <li>Cache para relatórios pesados</li>
                    <li>Monitoramento de tempos de resposta</li>
                </ul>
            </div>
        </div>
    </section>

    <div id="modules" class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
        <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <i class="fas fa-search"></i>
            </div>
            <input
                type="text"
                placeholder="Buscar ferramenta..."
                x-model.debounce.300ms="query"
                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <button @click="init()"
            :disabled="loading"
            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition disabled:opacity-50">
            <i class="fas fa-sync-alt mr-2"></i>Recarregar
        </button>
    </div>

    <template x-for="(module, midx) in filteredModules" :key="module.id">
        <section class="mb-8 bg-white rounded-lg shadow">
            <header @click="toggleSection(midx)"
                class="flex justify-between items-center px-6 py-4 cursor-pointer hover:bg-gray-100">
                <h2 class="text-lg font-semibold text-blue-600 flex items-center space-x-2">
                    <i class="fas fa-folder-open"></i>
                    <span x-text="module.name"></span>
                </h2>
                <i class="fas fa-chevron-down text-gray-500 transform transition-transform"
                    :class="{ 'rotate-180': open === midx }"></i>
            </header>
            <div x-show="open === midx" x-collapse class="px-6 py-4 border-t border-gray-200">
                <table class="w-full table-auto">
                    <thead class="bg-gray-100 text-sm text-gray-600 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">Ferramenta</th>
                            <th class="px-3 py-2">O que Atualizar</th>
                            <th class="px-3 py-2 text-center">Dificuldade</th>
                            <th class="px-3 py-2 text-center">Devs</th>
                            <th class="px-3 py-2 text-center">Horas</th>
                            <th class="px-3 py-2 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="tool in module.tools" :key="tool.id">
                            <tr class="hover:bg-gray-50"
                                x-show="tool.name.toLowerCase().includes(query.toLowerCase())">
                                <td class="px-3 py-2 font-medium" x-text="tool.name"></td>
                                <td class="px-3 py-2 text-gray-700" x-text="tool.update||'—'"></td>
                                <td class="px-3 py-2 text-center">
                                    <span
                                        class="inline-block px-2 py-1 rounded-full text-xs"
                                        :class="{
                                            'bg-green-200 text-green-800': tool.difficulty==='Baixa',
                                            'bg-yellow-200 text-yellow-800': tool.difficulty==='Média',
                                            'bg-red-200 text-red-800': tool.difficulty==='Alta'
                                        }"
                                        x-text="tool.difficulty">
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-block bg-indigo-200 text-indigo-800 px-2 py-1 rounded-full text-xs"
                                        x-text="tool.devs"></span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs"
                                        x-text="tool.hours + 'h'"></span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <div class="inline-flex space-x-1">
                                        <a
                                            :href="tool.link"
                                            target="_blank"
                                            class="inline-flex items-center px-2 py-1 bg-green-100 text-green-600 rounded hover:bg-green-200 transition"
                                            title="Ver página">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button @click="editTool(tool)"
                                            class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition"
                                            title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </section>
    </template>

    <div x-show="filteredModules.length===0" class="text-center text-gray-500 py-10">
        Nenhuma ferramenta para: <strong x-text="query"></strong>
    </div>

</main>

<script>
    function app() {
        return {
            open: null,
            modules: [],
            query: '',
            loading: false,

            async init() {
                this.loading = true;
                try {
                    let res = await fetch('get_data.php');
                    this.modules = await res.json();
                } catch (e) {
                    console.error(e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao carregar',
                        text: e.message
                    });
                } finally {
                    this.loading = false;
                }
            },

            toggleSection(idx) {
                this.open = this.open === idx ? null : idx;
            },

            async editTool(tool) {
                let {
                    value,
                    isConfirmed
                } = await Swal.fire({
                    title: `✏️ Editar: ${tool.name}`,
                    html: ` <div class="space-y-4 text-left">
                                <label class="block font-medium">Nível</label>
                                <select id="sw-level" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                    <option ${tool.level===1?'selected':''} value="1">1</option>
                                    <option ${tool.level===2?'selected':''} value="2">2</option>
                                    <option ${tool.level===3?'selected':''} value="3">3</option>
                                    <option ${tool.level===4?'selected':''} value="4">4</option>
                                </select>
                                <label class="block font-medium">O que Atualizar</label>
                                <textarea id="sw-update" rows="3" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                    placeholder="Descreva as mudanças">${tool.update||''}</textarea>
                                <label class="block font-medium">Dificuldade</label>
                                <select id="sw-diff" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                    <option ${tool.difficulty==='Baixa'?'selected':''}>Baixa</option>
                                    <option ${tool.difficulty==='Média'?'selected':''}>Média</option>
                                    <option ${tool.difficulty==='Alta'?'selected':''}>Alta</option>
                                </select>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                    <label class="block font-medium">Devs</label>
                                    <input id="sw-devs" type="number" min="1" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" value="${tool.devs}">
                                    </div>
                                    <div>
                                    <label class="block font-medium">Horas</label>
                                    <input id="sw-hours" type="number" min="0" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" value="${tool.hours}">
                                    </div>
                                </div>
                            </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Salvar',
                    customClass: {
                        popup: 'bg-white p-6 rounded-lg shadow-lg',
                        confirmButton: 'bg-blue-500 px-4 py-2 text-white rounded-lg hover:bg-blue-600',
                        cancelButton: 'bg-gray-200 px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-300'
                    },
                    focusConfirm: false,
                    preConfirm: () => ({
                        id: tool.id,
                        level: +document.getElementById('sw-level').value,
                        update: document.getElementById('sw-update').value,
                        difficulty: document.getElementById('sw-diff').value,
                        devs: +document.getElementById('sw-devs').value,
                        hours: +document.getElementById('sw-hours').value
                    })
                });

                if (!isConfirmed) return;

                try {
                    let res = await fetch('update_tool.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(value)
                    });
                    let json = await res.json();
                    if (!json.success) throw new Error(json.error || 'Erro');
                    Object.assign(tool, value);
                    Swal.fire({
                        icon: 'success',
                        title: 'Salvo!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } catch (e) {
                    console.error(e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Falha ao salvar',
                        text: e.message
                    });
                }
            },

            get filteredModules() {
                if (!this.query.trim()) return this.modules;
                return this.modules
                    .map(m => ({
                        ...m,
                        tools: m.tools.filter(t =>
                            t.name.toLowerCase().includes(this.query.toLowerCase())
                        )
                    }))
                    .filter(m => m.tools.length);
            }
        }
    }
</script>

<?php include 'footer.php'; ?>