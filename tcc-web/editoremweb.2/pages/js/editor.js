import { getElements, getExercicioId, setUIBusy, setOutput, getEditorValue } from "./ui.js";
import { enviarCodigo, checarResultado, verificarExercicios } from "./api.js";

document.addEventListener("DOMContentLoaded", () => {
    const { stdin, runButton, verifyButton } = getElements();
    const exercicioId = getExercicioId();

    // Evento do botão Rodar Código 
    if (runButton) {
        runButton.addEventListener("click", async () => {
            setOutput("");
            setUIBusy(true);

            try {
                const codigo = getEditorValue();
                const entradaStdin = stdin ? stdin.value : "";
                const submitData = await enviarCodigo(codigo, entradaStdin);
                if (!submitData.success) throw new Error(submitData.erro);

                const token = submitData.token;

                // polling até o Judge0 finalizar
                const interval = setInterval(async () => {
                    try {
                        const resultData = await checarResultado(token);

                        if (resultData.finalizado) {
                            clearInterval(interval);
                            setUIBusy(false);

                            if (resultData.compile_output) {
                                setOutput(resultData.compile_output);
                                return;
                            }
                            if (resultData.stderr) {
                                setOutput(resultData.stderr);
                                return;
                            }
                            setOutput(resultData.stdout || "Sem saída");
                        }
                    } catch (error) {
                        clearInterval(interval);
                        setUIBusy(false);
                        setOutput("Erro ao consultar execução.");
                        console.error(error);
                    }
                }, 1500);

            } catch (error) {
                setUIBusy(false);
                setOutput(error.message);
                console.error(error);
            }
        });
    }

    // Evento do botão Verificar Solução 
    if (verifyButton) {
        verifyButton.addEventListener("click", async () => {
            setOutput("Validando Solução");
            setUIBusy(true);

            try {
                const codigo = getEditorValue();
                const dados = await verificarExercicios(exercicioId, codigo);
                setUIBusy(false);

                if (dados.erro_compilacao) {
                    setOutput(dados.mensagem);
                    return;
                }
                if (dados.todasCorretas) {
                    setOutput("Tarefa Concluída");
                } else {
                    let detalhes = "Testes falharam:\n\n";
                    dados.resultados.forEach((res, index) => {
                        const status = res.checkcorreto ? "passou" : "falhou";
                        detalhes += `Teste ${index + 1}: [${status}]\n`;
                        if (!res.checkcorreto) {
                            detalhes += `  Saída obtida: ${res.saida || "Sem saída"}\n`;
                            detalhes += `  Esperado: ${res.saida_esperada}\n\n`;
                        }
                    });
                    setOutput(detalhes);
                }
            } catch (error) {
                setUIBusy(false);
                setOutput("Erro ao conectar com o servidor de verificação.");
                console.error(error);
            }
        });
    }
});