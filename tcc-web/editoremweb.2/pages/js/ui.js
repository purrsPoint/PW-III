// Seleção dos elementos da página
export const editor = document.getElementById("editor");
export const stdin = document.getElementById("stdin");
export const runButton = document.getElementById("runButton");
export const verifyButton = document.getElementById("verifyButton");
export const output = document.getElementById("output");
export const loading = document.getElementById("loading");

// Pega o ID do exercício 
const editorContainer = document.querySelector(".editor-container");
export const exercicioId = editorContainer ? editorContainer.dataset.exercicioId : null;

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