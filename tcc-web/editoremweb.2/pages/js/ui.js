export function getElements() {
    return {
        stdin: document.getElementById("stdin"),
        runButton: document.getElementById("runButton"),
        verifyButton: document.getElementById("verifyButton"),
        output: document.getElementById("output"),
        loading: document.getElementById("loading")
    };
}
export function getExercicioId() {
    const editorContainer = document.querySelector(".editor-container");
    return editorContainer ? editorContainer.dataset.exercicioId : null;
}

let monacoEditor = null;

// Espera a DOM carregar antes de iniciar o Monaco Editor
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById('editor-container');
    if (!container) return;

    if (typeof require !== "undefined") {
        require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs' }});
        require(['vs/editor/editor.main'], function() {
            const codigoInicialElement = document.getElementById("codigo-inicial");
            const codigoInicial = codigoInicialElement ? codigoInicialElement.value : "";

            monacoEditor = monaco.editor.create(container, {
                value: codigoInicial,
                language: 'java',
                theme: 'vs-dark',
                automaticLayout: true,
                tabSize: 4,
                minimap: { enabled: false }
            });
        });
    }
});

export function getEditorValue() {
  return monacoEditor ? monacoEditor.getValue() : "";
}

// Alterna o estado de carregamento e trava/destrava os botões
export function setUIBusy(isBusy) {
    loading.style.display = isBusy ? "flex" : "none";
    runButton.disabled = isBusy;
    verifyButton.disabled = isBusy;
}

// Escreve o texto de resposta na caixa do console
export function setOutput(texto) {
    output.textContent = texto;
}