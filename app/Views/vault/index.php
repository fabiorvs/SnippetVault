<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ScriptVault</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('css/estilos.css') ?>" />
</head>

<body>
    <div class="app">
        <header>
            <div class="title">
                <div class="logo">{ }</div>
                <div>
                    <h1>ScriptVault</h1>
                    <div style="color:var(--muted);font-size:12px">Meus Códigos</div>
                </div>
            </div>
            <div class="row">
                <button class="btn" id="newSnippet">+ Novo snippet</button>
                <button class="btn secondary" id="exportDb" title="Exportar .sqlite">Exportar</button>
                <label class="btn ghost" for="importDb" title="Importar .sqlite" style="cursor:pointer">Importar<input
                        type="file" id="importDb" accept="application/octet-stream,.sqlite,.db"
                        style="display:none"></label>
            </div>
        </header>

        <div class="toolbar">
            <input id="search" class="input" placeholder="Buscar título, conteúdo ou tags (⌘/Ctrl + K)"
                style="min-width:280px;flex:1" />
            <select id="sort">
                <option value="updated">Ordenar: Atualizados</option>
                <option value="created">Ordenar: Criados</option>
                <option value="alpha">Ordenar: A–Z</option>
            </select>
            <select id="filter">
                <option value="all">Todos</option>
                <option value="pinned">Fixados</option>
                <option value="trash">Lixeira</option>
                <option value="lang">Somente linguagem…</option>
            </select>
            <select id="filterLang" style="display:none"></select>
            <span class="chip">Autosave com debounce · Ctrl/⌘ + S força salvar</span>
        </div>

        <div class="layout">
            <aside class="panel pad">
                <div class="section-title">Snippets</div>
                <div class="list" id="snippetList"></div>
                <div class="empty" id="emptyState" style="display:none">Sem snippets. Clique em <b>Novo snippet</b>.
                </div>
            </aside>

            <main class="panel pad">
                <div class="editor-shell" id="editorShell" style="display:none">
                    <input id="title" class="input title-input" placeholder="Título" maxlength="140" />
                    <div class="row" style="gap:10px;flex-wrap:wrap">
                        <select id="language" class="input"></select>
                        <input id="tags" class="input" placeholder="tags, separadas, por vírgula"
                            style="flex:1;min-width:220px" />
                        <label class="input" style="display:flex;align-items:center;gap:8px"><input type="checkbox"
                                id="pinned">
                            Fixar</label>
                        <button class="btn secondary" id="deleteBtn">Mover p/ Lixeira</button>
                        <button class="btn" id="saveBtn">Salvar agora</button>
                        <button class="btn" id="downloadBtn" title="Baixar arquivo">Baixar</button>
                        <button class="btn secondary" id="copyBtn"
                            title="Copiar para área de transferência">Copiar</button>
                    </div>
                    <div id="editor"></div>
                    <div class="footerbar">
                        <div>Atalhos: <span class="kbd">Ctrl/⌘ + S</span> salvar · <span class="kbd">Ctrl/⌘ + F</span>
                            buscar no
                            editor</div>
                        <div id="meta"></div>
                    </div>
                </div>
                <div id="welcome" class="empty">Selecione ou crie um snippet para começar.</div>
            </main>
        </div>
    </div>
    <div id="toastHost" class="toast-host" aria-live="polite" aria-atomic="true"></div>

    <div id="palette" class="palette-backdrop" style="display:none">
        <div class="palette" role="dialog" aria-modal="true" aria-label="Command Palette">
            <div class="palette-top">
                <div class="palette-icon">⌘P</div>
                <input id="paletteSearch" class="palette-input" placeholder="Buscar snippet… (Enter abre)"
                    autocomplete="off" />
                <button class="palette-close" id="paletteClose" title="Fechar">Esc</button>
            </div>
            <div class="palette-hint">
                Dicas: <span class="kbd">↑</span>/<span class="kbd">↓</span> navegar • <span class="kbd">Enter</span>
                abrir • <span class="kbd">Esc</span> fechar
            </div>
            <div id="paletteList" class="palette-list"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SQLite WASM -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.10.2/sql-wasm.js"></script>
    <!-- Monaco AMD loader -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.49.0/min/vs/loader.min.js"></script>


    <script>
    window.APP_CONFIG = {
        baseApi: "<?= site_url('api') ?>",
        baseUrl: "<?= site_url() ?>"
    };
    </script>

    <script src="<?= base_url('js/scripts.js') ?>"></script>
</body>

</html>