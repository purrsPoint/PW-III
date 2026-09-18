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

export function setUIBusy(isBusy) {
    const {
        runButton,
        verifyButton,
        loading
    } = getElements();

    if (loading) {
        loading.style.display = isBusy ? "flex" : "none";
    }

    if (runButton) {
        runButton.disabled = isBusy;
    }

    if (verifyButton) {
        verifyButton.disabled = isBusy;
    }
}

export function setOutput(texto) {
    const { output } = getElements();

    if (output) {
        output.textContent = texto;
    }
}