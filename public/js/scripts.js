// =========================
// CodeVault Online (CI4) - Premium UX
// =========================

// ====== Monaco ======
let monaco, editor;

// ====== Estado ======
let currentId = null;
let debounceTimer = null;
let saving = false;
let dirty = false;
let lastPayloadHash = "";
let paletteOpen = false;
let paletteIndex = 0;
let paletteRows = [];

// Linguagens (label, monacoId, ext)
const LANGS = [
  ["Auto (plain text)", "plaintext", "txt"],
  ["JavaScript", "javascript", "js"],
  ["TypeScript", "typescript", "ts"],
  ["HTML", "html", "html"],
  ["CSS", "css", "css"],
  ["JSON", "json", "json"],
  ["SQL", "sql", "sql"],
  ["CSV", "plaintext", "csv"],
  ["PHP", "php", "php"],
  ["Python", "python", "py"],
  ["Java", "java", "java"],
  ["C#", "csharp", "cs"],
  ["Go", "go", "go"],
  ["Rust", "rust", "rs"],
  ["Shell", "shell", "sh"],
  ["YAML", "yaml", "yml"],
  ["Markdown", "markdown", "md"],
];

function fillLanguageSelects() {
  const $lang = $("#language").empty();
  const $filterLang = $("#filterLang").empty();
  LANGS.forEach(([label, id]) => {
    $lang.append(`<option value="${id}">${label}</option>`);
    $filterLang.append(`<option value="${id}">${label}</option>`);
  });
}

// ====== Utils ======
function fmt(iso) {
  if (!iso) return "";
  const d = new Date(iso);
  return d.toLocaleString([], {
    dateStyle: "short",
    timeStyle: "short",
  });
}

function escapeHtml(str) {
  return String(str).replace(
    /[&<>"']/g,
    (s) =>
      ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
      })[s],
  );
}

function getExtByLang(id) {
  const found = LANGS.find(([, langId]) => langId === id);
  return found ? found[2] : "txt";
}

function hashPayload(p) {
  // hash simples p/ dirty-check (sem crypto)
  return JSON.stringify(p);
}

// ====== Toast ======
function toast(type, title, msg, ms = 2200) {
  const $t = $(`
      <div class="toast ${type}">
        <div class="t-dot"></div>
        <div class="t-body">
          <div class="t-title"></div>
          <p class="t-msg"></p>
        </div>
        <button class="t-x" title="Fechar">✕</button>
      </div>
    `);
  $t.find(".t-title").text(title || "");
  $t.find(".t-msg").text(msg || "");
  $t.find(".t-x").on("click", () => $t.remove());
  $("#toastHost").append($t);
  setTimeout(() => $t.fadeOut(120, () => $t.remove()), ms);
}

// ====== Status pill (no footerbar - vamos reutilizar #meta) ======
function setStatus(state, text) {
  // state: ok | saving | err | idle
  const cls =
    state === "ok"
      ? "ok"
      : state === "saving"
        ? "saving"
        : state === "err"
          ? "err"
          : "";
  const label =
    text ||
    (state === "saving"
      ? "Salvando…"
      : state === "ok"
        ? "Salvo"
        : state === "err"
          ? "Erro ao salvar"
          : "");
  $("#meta").html(`
      <span class="status-pill ${cls}">
        <span class="status-dot"></span>
        <b>${escapeHtml(label)}</b>
      </span>
    `);
}

// ====== API ======
const BASE_API = window.APP_CONFIG.baseApi;

async function apiFetch(path, opts = {}) {
  const res = await fetch(`${BASE_API}${path}`, {
    credentials: "same-origin",
    headers: {
      Accept: "application/json",
      ...(opts.body
        ? {
            "Content-Type": "application/json",
          }
        : {}),
      ...(opts.headers || {}),
    },
    ...opts,
  });

  let json = null;
  try {
    json = await res.json();
  } catch (e) {}

  if (!res.ok) {
    const msg =
      json && (json.message || json.error)
        ? json.message || json.error
        : `Erro HTTP ${res.status}`;
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
      method: "POST",
    });
  },
  async update(id, payload) {
    return apiFetch(`/snippets/${encodeURIComponent(id)}`, {
      method: "PUT",
      body: JSON.stringify(payload || {}),
    });
  },
  async destroy(id) {
    return apiFetch(`/snippets/${encodeURIComponent(id)}`, {
      method: "DELETE",
    });
  },
};

// ====== UI state ======
function currentListOptions() {
  return {
    q: $("#search").val().trim(),
    filter: $("#filter").val(),
    sort: $("#sort").val(),
    lang: $("#filterLang").val(),
  };
}

// ====== Render list with pinned section + clickable tags + hover actions ======
async function refreshList() {
  const res = await api.list(currentListOptions());
  const rows = res.data || [];

  const pinned = rows.filter(
    (r) => Number(r.pinned) === 1 && Number(r.deleted) === 0,
  );
  const rest = rows.filter(
    (r) => !(Number(r.pinned) === 1 && Number(r.deleted) === 0),
  );

  const $list = $("#snippetList").empty();

  if (rows.length === 0) {
    $("#emptyState").show();
    return;
  }
  $("#emptyState").hide();

  const renderSection = (title, items) => {
    if (!items.length) return;

    $list.append(`
        <div class="section-row">
          <div class="section-title">${escapeHtml(title)}</div>
          <div class="section-count">${items.length}</div>
        </div>
      `);

    items.forEach((r) => {
      const tags = (r.tags || "")
        .split(",")
        .map((s) => s.trim())
        .filter(Boolean);
      const label =
        (LANGS.find(([, id]) => id === r.language) || [])[0] || r.language;

      const $c = $(`
          <div class="card" data-id="${escapeHtml(r.id)}">
            <div class="actions">
              <button class="action-btn act-copy" title="Copiar">⧉</button>
              <button class="action-btn act-pin" title="${Number(r.pinned) ? "Desafixar" : "Fixar"}">📌</button>
              <button class="action-btn danger act-del" title="${Number(r.deleted) ? "Excluir definitivamente" : "Mover p/ Lixeira"}">🗑</button>
            </div>

            <div class="row" style="justify-content:space-between">
              <div style="font-weight:800;max-width:70%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                ${escapeHtml(r.title || "(sem título)")}
              </div>
              <span class="pill badge-lang">${escapeHtml(label)}</span>
            </div>

            <div style="color:var(--muted);max-height:3.8em;overflow:hidden;font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace">
              ${escapeHtml(((r.preview || "") + "").replace(/\n/g, " "))}
            </div>

            <div class="meta">
              <span class="pill">Atualizado: ${fmt(r.updated_at)}</span>
              ${Number(r.pinned) ? '<span class="pill">📌 fixado</span>' : ""}
              ${tags.map((t) => `<span class="pill tag-chip" data-tag="${escapeHtml(t)}">#${escapeHtml(t)}</span>`).join("")}
            </div>
          </div>
        `);

      if (currentId && r.id === currentId) $c.addClass("selected");

      // open
      $c.on("click", async (e) => {
        // se clicar em botões, não abre
        if ($(e.target).closest(".actions").length) return;
        await openSnippet(r.id);
      });

      // tag chip click => filtra (busca por tag)
      $c.find(".tag-chip").on("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        const tag = $(e.currentTarget).data("tag") || "";
        $("#search").val(tag);
        toast("ok", "Filtro", `Buscando por tag: #${tag}`);
        refreshList();
      });

      // hover actions
      $c.find(".act-copy").on("click", async (e) => {
        e.preventDefault();
        e.stopPropagation();
        // copia preview (rápido); se quiser copia completo, podemos buscar pelo id
        try {
          const full = await api.get(r.id);
          const content = full?.data?.content ?? "";
          await navigator.clipboard.writeText(content);
          toast(
            "ok",
            "Copiado",
            "Snippet copiado para a área de transferência.",
          );
        } catch (err) {
          toast("err", "Falhou", err.message || "Não foi possível copiar.");
        }
      });

      $c.find(".act-pin").on("click", async (e) => {
        e.preventDefault();
        e.stopPropagation();
        try {
          await api.update(r.id, {
            pinned: !Number(r.pinned),
            deleted: false,
          });
          toast(
            "ok",
            "Atualizado",
            !Number(r.pinned) ? "Snippet fixado." : "Snippet desafixado.",
          );
          await refreshList();
        } catch (err) {
          toast("err", "Falhou", err.message || "Não foi possível atualizar.");
        }
      });

      $c.find(".act-del").on("click", async (e) => {
        e.preventDefault();
        e.stopPropagation();
        try {
          if (Number(r.deleted)) {
            if (!confirm("Excluir definitivamente?")) return;
            await api.destroy(r.id);
            toast("warn", "Removido", "Snippet excluído definitivamente.");
          } else {
            await api.update(r.id, {
              deleted: true,
              pinned: false,
            });
            toast("warn", "Lixeira", "Snippet movido para a lixeira.");
          }
          if (currentId === r.id) {
            currentId = null;
            $("#editorShell").hide();
            $("#welcome").show();
          }
          await refreshList();
        } catch (err) {
          toast("err", "Falhou", err.message || "Não foi possível excluir.");
        }
      });

      $list.append($c);
    });
  };

  renderSection("📌 Fixados", pinned);
  renderSection("Snippets", rest);
}

// ====== Open snippet ======
async function openSnippet(id) {
  // se tem alterações não salvas, salva antes
  if (dirty && currentId) {
    await saveFromEditor(true);
  }

  const res = await api.get(id);
  const s = res.data;
  if (!s) return;

  currentId = s.id;
  dirty = false;
  lastPayloadHash = "";

  $("#welcome").hide();
  $("#editorShell").show();

  $("#title").val(s.title || "");
  $("#tags").val(s.tags || "");
  $("#language").val(s.language || "plaintext");
  $("#pinned").prop("checked", !!Number(s.pinned));
  $("#deleteBtn").text(
    Number(s.deleted) ? "Excluir definitivamente" : "Mover p/ Lixeira",
  );

  // status
  setStatus("ok", `Aberto • Atualizado ${fmt(s.updated_at)}`);

  // lista marca selecionado
  $("#snippetList .card").removeClass("selected");
  $(`#snippetList .card[data-id="${CSS.escape(s.id)}"]`).addClass("selected");

  if (editor) editor.dispose();
  editor = monaco.editor.create(document.getElementById("editor"), {
    value: s.content || "",
    language: s.language || "plaintext",
    theme: "vs-dark",
    automaticLayout: true,
    fontLigatures: true,
    fontSize: 14,
    minimap: {
      enabled: true,
    },
    wordWrap: "on",
  });

  editor.onDidChangeModelContent(() => {
    dirty = true;
    scheduleSave();
    setStatus("saving", "Editando…");
  });
}

function scheduleSave() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => saveFromEditor(false), 700);
}

async function saveFromEditor(force) {
  if (!currentId) return;
  if (!editor) return;

  const payload = {
    title: $("#title").val().trim(),
    language: $("#language").val(),
    tags: $("#tags").val().trim(),
    pinned: $("#pinned").is(":checked"),
    content: editor.getValue(),
  };

  const h = hashPayload(payload);

  // dirty check: só salva se mudou ou se for forced
  if (!force && h === lastPayloadHash) return;
  if (!force && !dirty) return;

  try {
    saving = true;
    setStatus("saving", "Salvando…");
    await api.update(currentId, payload);
    lastPayloadHash = h;
    dirty = false;
    saving = false;
    setStatus("ok", "Salvo");
    await refreshList();
  } catch (err) {
    saving = false;
    setStatus("err", "Erro ao salvar");
    toast(
      "err",
      "Erro ao salvar",
      err.message || "Falha ao salvar. Tente novamente.",
    );
  }
}

// ====== Buttons (editor area) ======
function downloadCurrent() {
  if (!currentId || !editor) return;
  const title = ($("#title").val().trim() || "snippet").replace(
    /[^a-z0-9_-]/gi,
    "_",
  );
  const ext = getExtByLang($("#language").val());
  const blob = new Blob([editor.getValue()], {
    type: "text/plain;charset=utf-8",
  });

  const a = document.createElement("a");
  a.href = URL.createObjectURL(blob);
  a.download = `${title}.${ext}`;
  a.click();
  URL.revokeObjectURL(a.href);
}

async function copyCurrent() {
  if (!editor) return;
  try {
    await navigator.clipboard.writeText(editor.getValue());
    toast("ok", "Copiado", "Conteúdo copiado para a área de transferência.");
  } catch (e) {
    toast("err", "Falhou", "Não foi possível copiar.");
  }
}

// ====== Export/Import (premium: JSON) ======
async function exportJson() {
  try {
    // pega tudo (inclusive lixeira) passando filter=all
    const res = await api.list({
      q: "",
      filter: "all",
      sort: "updated",
      lang: "",
    });
    const rows = res.data || [];

    // baixa completo buscando content de cada um (para backup real)
    const full = [];
    for (const r of rows) {
      const x = await api.get(r.id);
      full.push(x.data);
    }

    const blob = new Blob(
      [
        JSON.stringify(
          {
            exported_at: new Date().toISOString(),
            snippets: full,
          },
          null,
          2,
        ),
      ],
      {
        type: "application/json;charset=utf-8",
      },
    );
    const a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = "codevault-backup.json";
    a.click();
    URL.revokeObjectURL(a.href);

    toast("ok", "Backup", "Backup JSON gerado com sucesso.");
  } catch (err) {
    toast("err", "Backup falhou", err.message || "Não foi possível exportar.");
  }
}

async function importJsonFile(file) {
  try {
    const text = await file.text();
    const parsed = JSON.parse(text);
    const snippets = Array.isArray(parsed?.snippets) ? parsed.snippets : [];

    if (!snippets.length) {
      toast("warn", "Importação", "Nenhum snippet encontrado no arquivo.");
      return;
    }

    // importa como novos
    for (const s of snippets) {
      const created = await api.create();
      await api.update(created.id, {
        title: s.title || "Importado",
        language: s.language || "plaintext",
        tags: s.tags || "",
        pinned: !!Number(s.pinned),
        deleted: !!Number(s.deleted),
        content: s.content || "",
      });
    }

    toast("ok", "Importação", "Importação concluída!");
    await refreshList();
  } catch (err) {
    toast(
      "err",
      "Importação falhou",
      err.message || "Não foi possível importar.",
    );
  }
}

// ====== Command Palette ======
async function openPalette() {
  paletteOpen = true;
  $("#palette").show();
  $("#paletteSearch").val("").focus();
  paletteIndex = 0;

  // carrega lista rápida (sem filtro, 200 últimos)
  const res = await api.list({
    q: "",
    filter: "all",
    sort: "updated",
    lang: "",
  });
  paletteRows = (res.data || []).slice(0, 200);

  renderPalette("");
}

function closePalette() {
  paletteOpen = false;
  $("#palette").hide();
}

function renderPalette(q) {
  const qq = (q || "").trim().toLowerCase();
  let list = paletteRows;

  if (qq) {
    list = paletteRows.filter((r) => {
      const t = (r.title || "").toLowerCase();
      const g = (r.tags || "").toLowerCase();
      const p = (r.preview || "").toLowerCase();
      return t.includes(qq) || g.includes(qq) || p.includes(qq);
    });
  }

  const $pl = $("#paletteList").empty();
  if (!list.length) {
    $pl.append(`<div class="empty">Nada encontrado.</div>`);
    return;
  }

  // clamp index
  if (paletteIndex >= list.length) paletteIndex = list.length - 1;
  if (paletteIndex < 0) paletteIndex = 0;

  list.forEach((r, idx) => {
    const label =
      (LANGS.find(([, id]) => id === r.language) || [])[0] || r.language;
    const $it = $(`
        <div class="p-item ${idx === paletteIndex ? "active" : ""}" data-id="${escapeHtml(r.id)}">
          <div>
            <div class="p-title">${escapeHtml(r.title || "(sem título)")}</div>
            <div class="p-meta">${escapeHtml((r.tags || "").trim())} • ${fmt(r.updated_at)}</div>
          </div>
          <div class="p-badge">${escapeHtml(label)}</div>
        </div>
      `);
    $it.on("click", async () => {
      closePalette();
      await openSnippet(r.id);
    });
    $pl.append($it);
  });

  // guarda lista filtrada atual para navegação
  window.__paletteFiltered = list;
}

// ====== Boot UI ======
function bootUI() {
  fillLanguageSelects();

  $("#sort").val("updated");
  $("#filter").val("all");
  $("#filterLang").hide();

  refreshList().catch((e) => toast("err", "Falhou", e.message));

  // new
  $("#newSnippet").on("click", async () => {
    try {
      const created = await api.create();
      toast("ok", "Criado", "Novo snippet criado.");
      await refreshList();
      await openSnippet(created.id);
    } catch (e) {
      toast("err", "Falhou", e.message);
    }
  });

  // search & filters
  $("#search").on("input", () => refreshList().catch(() => {}));
  $("#sort, #filter").on("change", () => {
    if ($("#filter").val() === "lang") $("#filterLang").show();
    else $("#filterLang").hide();
    refreshList().catch((e) => toast("err", "Falhou", e.message));
  });
  $("#filterLang").on("change", () =>
    refreshList().catch((e) => toast("err", "Falhou", e.message)),
  );

  // editor inputs -> schedule save
  const scheduleInputs = () => {
    dirty = true;
    scheduleSave();
    setStatus("saving", "Editando…");
  };
  $("#title, #tags, #language, #pinned").on("input change", scheduleInputs);

  // buttons
  $("#saveBtn").on("click", () => saveFromEditor(true));
  $("#downloadBtn").on("click", downloadCurrent);
  $("#copyBtn").on("click", copyCurrent);

  $("#deleteBtn").on("click", async () => {
    if (!currentId) return;
    try {
      const inTrashView = $("#filter").val() === "trash";
      const isTrash =
        String($("#deleteBtn").text()).toLowerCase().includes("excluir") ||
        inTrashView;

      if (isTrash) {
        if (!confirm("Excluir definitivamente?")) return;
        await api.destroy(currentId);
        toast("warn", "Removido", "Snippet excluído definitivamente.");
      } else {
        await api.update(currentId, {
          deleted: true,
          pinned: false,
        });
        toast("warn", "Lixeira", "Snippet movido para a lixeira.");
      }

      currentId = null;
      $("#editorShell").hide();
      $("#welcome").show();
      await refreshList();
    } catch (e) {
      toast("err", "Falhou", e.message);
    }
  });

  // Export/Import (agora JSON)
  $("#exportDb").off("click").on("click", exportJson);
  $("#importDb")
    .off("change")
    .on("change", function () {
      if (!(this.files && this.files[0])) return;
      importJsonFile(this.files[0]);
      this.value = "";
    });

  // Palette events
  $("#paletteClose").on("click", closePalette);
  $("#palette").on("click", function (e) {
    if (e.target === this) closePalette();
  });
  $("#paletteSearch").on("input", function () {
    renderPalette($(this).val());
  });
  $("#paletteSearch").on("keydown", async function (e) {
    const list = window.__paletteFiltered || [];
    if (e.key === "ArrowDown") {
      e.preventDefault();
      paletteIndex++;
      renderPalette($(this).val());
    }
    if (e.key === "ArrowUp") {
      e.preventDefault();
      paletteIndex--;
      renderPalette($(this).val());
    }
    if (e.key === "Enter") {
      e.preventDefault();
      const item = list[paletteIndex];
      if (item) {
        closePalette();
        await openSnippet(item.id);
      }
    }
    if (e.key === "Escape") {
      e.preventDefault();
      closePalette();
    }
  });

  // Global shortcuts
  $(document).on("keydown", (e) => {
    // Ctrl/Cmd+K (já tem)
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "k") {
      e.preventDefault();
      $("#search").focus();
    }

    // Ctrl/Cmd+P (Palette)
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "p") {
      e.preventDefault();
      if (!paletteOpen)
        openPalette().catch((err) => toast("err", "Falhou", err.message));
    }

    // Ctrl/Cmd+S
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "s") {
      e.preventDefault();
      saveFromEditor(true);
    }

    // Esc closes palette
    if (e.key === "Escape" && paletteOpen) {
      e.preventDefault();
      closePalette();
    }

    // N
    if (
      e.key.toLowerCase() === "n" &&
      e.target.tagName !== "INPUT" &&
      e.target.tagName !== "TEXTAREA"
    ) {
      e.preventDefault();
      $("#newSnippet").click();
    }
  });
}

function loadMonaco() {
  window.require.config({
    paths: {
      vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.49.0/min/vs",
    },
  });
  window.require(["vs/editor/editor.main"], function () {
    monaco = window.monaco;
    bootUI();
    setStatus("ok", "Pronto");
    toast("ok", "CodeVault", "Bem-vindo! Ctrl+P para buscar rápido.");
  });
}

$(function () {
  loadMonaco();
});
