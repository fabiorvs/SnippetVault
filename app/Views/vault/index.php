<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Code</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    :root {
        --bg: #0f1115;
        --surface: #151922;
        --muted: #a7b0c0;
        --text: #e8eef7;
        --primary: #5b8cff;
        --primary-2: #74a1ff;
        --border: #1f2532;
        --card: #0f1421;
        --shadow: 0 10px 30px rgba(0, 0, 0, .3);
        --radius: 16px;
    }

    * {
        box-sizing: border-box
    }

    html,
    body {
        height: 100%
    }

    body {
        margin: 0;
        background: linear-gradient(180deg, #0b0d12, #0f1115 40%);
        color: var(--text);
        font-family: Inter, system-ui, Segoe UI, Roboto, Arial, sans-serif
    }

    .app {
        max-width: 1400px;
        margin: 24px auto;
        padding: 0 16px 32px
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        gap: 12px
    }

    .title {
        display: flex;
        align-items: center;
        gap: 12px
    }

    .logo {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), #6b73ff);
        display: grid;
        place-items: center;
        font-weight: 800;
        box-shadow: var(--shadow)
    }

    h1 {
        font-size: 22px;
        margin: 0
    }

    .btn {
        border: 0;
        background: var(--primary);
        color: #0b0d12;
        padding: 10px 14px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: var(--shadow)
    }

    .btn:hover {
        filter: brightness(1.05)
    }

    .btn.secondary {
        background: #232a37;
        color: var(--text)
    }

    .btn.ghost {
        background: transparent;
        color: var(--muted);
        box-shadow: none
    }

    .toolbar {
        background: color-mix(in srgb, var(--surface) 92%, #000 8%);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        box-shadow: var(--shadow)
    }

    .input,
    select {
        background: #0f1421;
        border: 1px solid #232a37;
        color: var(--text);
        padding: 10px 12px;
        border-radius: 12px;
        outline: none
    }

    .input:focus,
    select:focus {
        box-shadow: 0 0 0 2px color-mix(in srgb, var(--primary) 30%, transparent);
        border-color: var(--primary-2)
    }

    .chip {
        background: #1b2231;
        color: var(--muted);
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        border: 1px solid #262f42
    }

    .layout {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 16px;
        margin-top: 16px
    }

    @media (max-width:1100px) {
        .layout {
            grid-template-columns: 1fr
        }
    }

    .panel {
        background: color-mix(in srgb, var(--surface) 92%, #000 8%);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow)
    }

    .panel.pad {
        padding: 14px
    }

    .list {
        display: grid;
        gap: 10px
    }

    .card {
        display: flex;
        flex-direction: column;
        gap: 6px;
        background: var(--card);
        border: 1px solid #232a37;
        border-radius: 14px;
        padding: 10px 12px;
        cursor: pointer
    }

    .card:hover {
        transform: translateY(-1px)
    }

    .card .meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        color: var(--muted);
        font-size: 12px
    }

    .pill {
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid #2a3650;
        background: #0b0f19
    }

    .row {
        display: flex;
        align-items: center;
        gap: 8px
    }

    .row.right {
        justify-content: flex-end
    }

    .icon-btn {
        background: transparent;
        border: 1px solid #2a3650;
        color: var(--text);
        border-radius: 10px;
        padding: 6px 8px;
        cursor: pointer
    }

    .icon-btn:hover {
        border-color: var(--primary-2)
    }

    .editor-shell {
        display: grid;
        gap: 10px
    }

    .title-input {
        font-size: 18px;
        font-weight: 700
    }

    #editor {
        height: 60vh;
        border: 1px solid #232a37;
        border-radius: 12px;
        overflow: hidden
    }

    .footerbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 6px;
        color: var(--muted);
        font-size: 12px
    }

    .section-title {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--muted);
        margin: 8px 0 10px
    }

    .empty {
        padding: 24px 8px;
        text-align: center;
        color: var(--muted);
        border: 1px dashed #263146;
        border-radius: 14px
    }

    .kbd {
        border: 1px solid #2a3650;
        background: #0b0f19;
        border-radius: 8px;
        padding: 2px 6px;
        font-size: 12px;
        color: var(--muted)
    }

    .badge-lang {
        font-weight: 700
    }
    </style>
</head>

<body>
    <div class="app">
        <header>
            <div class="title">
                <div class="logo">{ }</div>
                <div>
                    <h1>Code</h1>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SQLite WASM -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.10.2/sql-wasm.js"></script>
    <!-- Monaco AMD loader -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.49.0/min/vs/loader.min.js"></script>
    <script>
    // =========================
    // CodeVault Online (CI4)
    // =========================
    // Requisitos no HTML:
    // - manter jQuery e Monaco loader (vs/loader)
    // - REMOVER sql.js + tudo de localStorage/SQLite
    //
    // Endpoints (sessão auth no CI4):
    // GET    /api/snippets?q=&filter=&sort=&lang=
    // POST   /api/snippets
    // GET    /api/snippets/{id}
    // PUT    /api/snippets/{id}
    // DELETE /api/snippets/{id}   (hard delete)

    // ====== Monaco ======
    let monaco, editor;

    // ====== Estado ======
    let currentId = null;
    let debounceTimer = null;
    let currentFilter = {
        q: '',
        filter: 'all',
        sort: 'updated',
        lang: ''
    };
    let lastOpened = null;

    // Linguagens suportadas (label -> monaco id, extensão)
    const LANGS = [
        ['Auto (plain text)', 'plaintext', 'txt'],
        ['JavaScript', 'javascript', 'js'],
        ['TypeScript', 'typescript', 'ts'],
        ['HTML', 'html', 'html'],
        ['CSS', 'css', 'css'],
        ['JSON', 'json', 'json'],
        ['SQL', 'sql', 'sql'],
        ['CSV', 'plaintext', 'csv'],
        ['PHP', 'php', 'php'],
        ['Python', 'python', 'py'],
        ['Java', 'java', 'java'],
        ['C#', 'csharp', 'cs'],
        ['Go', 'go', 'go'],
        ['Rust', 'rust', 'rs'],
        ['Shell', 'shell', 'sh'],
        ['YAML', 'yaml', 'yml'],
        ['Markdown', 'markdown', 'md']
    ];

    function fillLanguageSelects() {
        const $lang = $('#language').empty();
        const $filterLang = $('#filterLang').empty();
        LANGS.forEach(([label, id]) => {
            $lang.append(`<option value="${id}">${label}</option>`);
            $filterLang.append(`<option value="${id}">${label}</option>`);
        });
    }

    // ====== Util ======
    function fmt(iso) {
        if (!iso) return '';
        const d = new Date(iso);
        return d.toLocaleString([], {
            dateStyle: 'short',
            timeStyle: 'short'
        });
    }

    function escapeHtml(str) {
        return String(str).replace(/[&<>"']/g, s => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [s]));
    }

    function getExtByLang(id) {
        const found = LANGS.find(([, langId]) => langId === id);
        return found ? found[2] : 'txt';
    }

    // ====== API ======
    // Ajuste o BASE_API se seu app estiver em subpasta.
    const BASE_API = '<?= site_url('api') ?>';

    async function apiFetch(path, opts = {}) {
        const res = await fetch(`${BASE_API}${path}`, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                ...(opts.body ? {
                    'Content-Type': 'application/json'
                } : {}),
                ...(opts.headers || {})
            },
            ...opts
        });

        // tenta parsear json sempre
        let json = null;
        try {
            json = await res.json();
        } catch (e) {
            /* ignore */ }

        if (!res.ok) {
            const msg = (json && (json.message || json.error)) ? (json.message || json.error) :
                `Erro HTTP ${res.status}`;
            throw new Error(msg);
        }
        return json;
    }

    const api = {
        async list(opts) {
            const qs = new URLSearchParams(opts || {}).toString();
            return apiFetch(`/snippets?${qs}`);
        },
        async get(id) {
            return apiFetch(`/snippets/${encodeURIComponent(id)}`);
        },
        async create() {
            return apiFetch(`/snippets`, {
                method: 'POST'
            });
        },
        async update(id, payload) {
            return apiFetch(`/snippets/${encodeURIComponent(id)}`, {
                method: 'PUT',
                body: JSON.stringify(payload || {})
            });
        },
        async destroy(id) {
            return apiFetch(`/snippets/${encodeURIComponent(id)}`, {
                method: 'DELETE'
            });
        }
    };

    // ====== CRUD / lógica ======
    async function refreshList() {
        currentFilter = {
            q: $('#search').val().trim(),
            filter: $('#filter').val(),
            sort: $('#sort').val(),
            lang: $('#filterLang').val()
        };

        const res = await api.list(currentFilter);
        const rows = res.data || [];

        const $list = $('#snippetList').empty();
        if (rows.length === 0) {
            $('#emptyState').show();
        } else {
            $('#emptyState').hide();
        }

        rows.forEach(r => {
            const tags = (r.tags || '').split(',').map(s => s.trim()).filter(Boolean);
            const label = (LANGS.find(([, id]) => id === r.language) || [])[0] || r.language;

            const $c = $('<div class="card"></div>')
                .attr('data-id', r.id)
                .append(`
          <div class="row" style="justify-content:space-between">
            <div style="font-weight:700;max-width:70%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
              ${escapeHtml(r.title || '(sem título)')}
            </div>
            <span class="pill badge-lang">${escapeHtml(label)}</span>
          </div>
        `)
                .append(`
          <div style="color:var(--muted);max-height:3.8em;overflow:hidden">
            ${escapeHtml(((r.preview || '') + '').replace(/\n/g, ' '))}
          </div>
        `)
                .append(`
          <div class="meta">
            <span class="pill">Atualizado: ${fmt(r.updated_at)}</span>
            <span class="pill">Criado: ${fmt(r.created_at)}</span>
            ${r.pinned ? '<span class="pill">📌 fixado</span>' : ''}
            ${tags.map(t => `<span class="pill">#${escapeHtml(t)}</span>`).join('')}
          </div>
        `);

            $list.append($c);
        });
    }

    async function openSnippet(id) {
        const res = await api.get(id);
        const s = res.data;
        if (!s) return;

        currentId = s.id;
        lastOpened = {
            ...s
        };

        $('#welcome').hide();
        $('#editorShell').show();

        $('#title').val(s.title || '');
        $('#tags').val(s.tags || '');
        $('#language').val(s.language || 'plaintext');
        $('#pinned').prop('checked', !!Number(s.pinned));
        $('#deleteBtn').text(Number(s.deleted) ? 'Excluir definitivamente' : 'Mover p/ Lixeira');
        $('#meta').text(
            `ID ${(s.id || '').slice(0, 8)}… • Criado ${fmt(s.created_at)} • Atualizado ${fmt(s.updated_at)}`);

        if (editor) editor.dispose();

        editor = monaco.editor.create(document.getElementById('editor'), {
            value: s.content || '',
            language: s.language || 'plaintext',
            theme: 'vs-dark',
            automaticLayout: true,
            fontLigatures: true,
            fontSize: 14,
            minimap: {
                enabled: true
            },
            wordWrap: 'on',
        });

        editor.onDidChangeModelContent(() => scheduleSave());
    }

    function scheduleSave() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(saveFromEditor, 600);
    }

    async function saveFromEditor() {
        if (!currentId) return;

        const payload = {
            title: $('#title').val().trim(),
            language: $('#language').val(),
            tags: $('#tags').val().trim(),
            pinned: $('#pinned').is(':checked'),
            // deleted não muda aqui (só no deleteBtn)
            content: editor ? editor.getValue() : ''
        };

        await api.update(currentId, payload);
        await refreshList();

        // atualiza meta (opcional: pode re-buscar do servidor)
        $('#meta').text(`ID ${currentId.slice(0, 8)}… • Salvo ${fmt(new Date().toISOString())}`);
    }

    async function softDeleteCurrent() {
        if (!currentId) return;
        await api.update(currentId, {
            deleted: true,
            pinned: false
        });
        currentId = null;
        $('#editorShell').hide();
        $('#welcome').show();
        await refreshList();
    }

    async function hardDeleteCurrent() {
        if (!currentId) return;
        await api.destroy(currentId);
        currentId = null;
        $('#editorShell').hide();
        $('#welcome').show();
        await refreshList();
    }

    async function restoreCurrent() {
        if (!currentId) return;
        await api.update(currentId, {
            deleted: false
        });
        await refreshList();
        await openSnippet(currentId);
    }

    function downloadCurrent() {
        if (!currentId || !editor) return;

        const title = ($('#title').val().trim() || 'snippet').replace(/[^a-z0-9_-]/gi, '_');
        const ext = getExtByLang($('#language').val());
        const blob = new Blob([editor.getValue()], {
            type: 'text/plain;charset=utf-8'
        });

        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = `${title}.${ext}`;
        a.click();
        URL.revokeObjectURL(a.href);
    }

    async function copyCurrent() {
        if (!editor) return;
        try {
            await navigator.clipboard.writeText(editor.getValue());
            alert('Copiado para a área de transferência.');
        } catch (e) {
            alert('Não foi possível copiar: ' + e);
        }
    }

    // ====== Export/Import (Online)
    // Export: baixa JSON dos snippets (backup rápido)
    async function exportJson() {
        // pega tudo sem filtro (mantendo deleted)
        const res = await api.list({
            q: '',
            filter: 'all',
            sort: 'updated',
            lang: ''
        });
        const data = res.data || [];
        const blob = new Blob([JSON.stringify({
            exported_at: new Date().toISOString(),
            snippets: data
        }, null, 2)], {
            type: 'application/json;charset=utf-8'
        });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'codevault-backup.json';
        a.click();
        URL.revokeObjectURL(a.href);
    }

    // Import: lê JSON e sobe como novos snippets (simples; não sobrescreve)
    async function importJsonFile(file) {
        const text = await file.text();
        let parsed = null;
        try {
            parsed = JSON.parse(text);
        } catch (e) {
            alert('JSON inválido');
            return;
        }

        const snippets = Array.isArray(parsed?.snippets) ? parsed.snippets : (Array.isArray(parsed) ? parsed : []);
        if (!snippets.length) return alert('Nenhum snippet no arquivo.');

        // cria um por um (simples e seguro)
        for (const s of snippets) {
            const created = await api.create();
            const id = created.id;
            await api.update(id, {
                title: s.title || 'Importado',
                language: s.language || 'plaintext',
                tags: s.tags || '',
                pinned: !!Number(s.pinned),
                deleted: !!Number(s.deleted),
                content: s.content || ''
            });
        }

        alert('Importação concluída!');
        await refreshList();
    }

    // ====== UI ======
    function bootUI() {
        fillLanguageSelects();
        $('#sort').val('updated');
        $('#filter').val('all');

        refreshList().catch(e => alert(e.message));

        $('#newSnippet').on('click', async () => {
            try {
                const created = await api.create();
                await refreshList();
                await openSnippet(created.id);
            } catch (e) {
                alert(e.message);
            }
        });

        $('#snippetList').on('click', '.card', function() {
            openSnippet($(this).data('id')).catch(e => alert(e.message));
        });

        $('#search').on('input', () => refreshList().catch(() => {}));

        $('#sort, #filter').on('change', function() {
            if ($('#filter').val() === 'lang') $('#filterLang').show();
            else $('#filterLang').hide();
            refreshList().catch(e => alert(e.message));
        });

        $('#filterLang').on('change', () => refreshList().catch(e => alert(e.message)));

        // editor inputs
        const scheduleSaveInputs = () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(saveFromEditor, 500);
        };
        $('#title, #tags, #language, #pinned').on('input change', scheduleSaveInputs);

        $('#saveBtn').on('click', () => saveFromEditor().catch(e => alert(e.message)));

        $('#deleteBtn').on('click', async () => {
            try {
                if (!currentId) return;

                // se o filtro atual é lixeira, assume que quer hard delete
                const inTrashView = ($('#filter').val() === 'trash');

                // tenta inferir status atual (pela label do botão ou pelo lastOpened)
                const isDeleted = String($('#deleteBtn').text()).toLowerCase().includes('excluir') || !!
                    Number(lastOpened?.deleted);

                if (inTrashView || isDeleted) {
                    if (confirm('Excluir definitivamente?')) await hardDeleteCurrent();
                } else {
                    await softDeleteCurrent();
                }
            } catch (e) {
                alert(e.message);
            }
        });

        $('#downloadBtn').on('click', downloadCurrent);
        $('#copyBtn').on('click', () => copyCurrent());

        // atalhos
        $(document).on('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                $('#search').focus();
            }
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                e.preventDefault();
                saveFromEditor().catch(() => {});
            }
            if (e.key.toLowerCase() === 'n' && e.target.tagName !== 'INPUT' && e.target.tagName !==
                'TEXTAREA') {
                e.preventDefault();
                $('#newSnippet').click();
            }
        });

        // Export/Import substituindo o antigo SQLite
        $('#exportDb').off('click').on('click', () => exportJson().catch(e => alert(e.message)));

        $('#importDb').off('change').on('change', function() {
            if (!(this.files && this.files[0])) return;
            const f = this.files[0];
            importJsonFile(f).catch(e => alert('Falha ao importar: ' + e.message));
            this.value = '';
        });
    }

    function loadMonaco() {
        window.require.config({
            paths: {
                'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.49.0/min/vs'
            }
        });
        window.require(['vs/editor/editor.main'], function() {
            monaco = window.monaco;
            // pronto -> liga UI
            bootUI();
        });
    }

    // ====== Inicialização ======
    $(function() {
        // Apenas carrega o Monaco e segue
        loadMonaco();
    });
    </script>
</body>

</html>