import { editor, stdin, runButton, verifyButton, exercicioId, setUIBusy, setOutput } from "./ui.js";
import { enviarCodigo, checarResultado, verificarExercicios } from "./api.js";

// Suporte pro TAB no textarea */
editor.addEventListener("keydown", function (e) {
    if (e.key === "Tab") {
        e.preventDefault();
        const inicio = this.selectionStart;
        const fim = this.selectionEnd;
        this.setRangeText("    ", inicio, fim, "end");
    }
});

// Evento do botão Rodar Código 
runButton.addEventListener("click", async () => {
    setOutput("");
    setUIBusy(true);

    try {
        const submitData = await enviarCodigo(editor.value, stdin.value);
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


// Evento do botão Verificar Solução 
verifyButton.addEventListener("click", async () => {
    setOutput("Validando Solução");
    setUIBusy(true);

    try {
        const dados = await verificarExercicios(exercicioId, editor.value);
        setUIBusy(false);

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