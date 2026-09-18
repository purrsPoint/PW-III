import {
    getElements,
    getExercicioId,
    setUIBusy,
    setOutput,
    getEditorValue
} from "./ui.js";

import {
    enviarCodigo,
    checarResultado,
    verificarExercicios
} from "./api.js";


document.addEventListener("DOMContentLoaded", () => {

    const {
        stdin,
        runButton,
        verifyButton
    } = getElements();

    const exercicioId = getExercicioId();


    // =========================
    // RODAR CÓDIGO
    // =========================

    if (runButton) {

        runButton.addEventListener("click", async () => {

            setOutput("");
            setUIBusy(true);

            try {

                const codigo = getEditorValue();
                const entradaStdin = stdin ? stdin.value : "";

                const submitData = await enviarCodigo(
                    codigo,
                    entradaStdin
                );

                if (!submitData.success) {
                    throw new Error(
                        submitData.erro ||
                        "Não foi possível executar o código."
                    );
                }

                if (!submitData.token) {
                    throw new Error(
                        "O código foi enviado, mas nenhum token foi recebido."
                    );
                }

                const token = submitData.token;

                let finalizado = false;

                while (!finalizado) {

                    await new Promise(resolve => {
                        setTimeout(resolve, 1500);
                    });

                    const resultData = await checarResultado(token);

                    if (!resultData.success) {
                        throw new Error(
                            resultData.erro ||
                            "Erro ao consultar a execução."
                        );
                    }

                    finalizado = resultData.finalizado;

                    if (finalizado) {

                        if (resultData.compile_output) {
                            setOutput(
                                resultData.compile_output
                            );
                            return;
                        }

                        if (resultData.stderr) {
                            setOutput(
                                resultData.stderr
                            );
                            return;
                        }

                        setOutput(
                            resultData.stdout || "Sem saída"
                        );

                        return;
                    }
                }

            } catch (error) {

                setOutput(
                    error.message ||
                    "Erro ao executar o código."
                );

                console.error(error);

            } finally {

                setUIBusy(false);
            }
        });
    }


    // =========================
    // VERIFICAR SOLUÇÃO
    // =========================

    if (verifyButton) {

        verifyButton.addEventListener("click", async () => {

            setOutput("");
            setUIBusy(true);

            try {

                const codigo = getEditorValue();

                if (!exercicioId) {
                    throw new Error(
                        "Exercício não identificado."
                    );
                }

                const dados = await verificarExercicios(
                    exercicioId,
                    codigo
                );

                console.log(
                    "RESPOSTA DO PHP:",
                    dados
                );


                // =========================
                // ERRO GERAL
                // =========================

                if (dados.erro) {

                    setOutput(
                        dados.erro
                    );

                    return;
                }


                // =========================
                // ERRO DE EXECUÇÃO
                // =========================

                if (dados.erro_execucao) {

                    setOutput(
                        dados.mensagem ||
                        "Ocorreu um erro durante a execução."
                    );

                    return;
                }


                // =========================
                // ERRO DE COMPILAÇÃO
                // =========================

                if (dados.erro_compilacao) {

                    setOutput(
                        dados.mensagem ||
                        "O código possui um erro de compilação."
                    );

                    return;
                }


                // =========================
                // RESULTADOS INVÁLIDOS
                // =========================

                if (!Array.isArray(dados.resultados)) {

                    setOutput(
                        "O servidor não retornou os resultados dos testes."
                    );

                    return;
                }


                // =========================
                // TODOS OS TESTES CORRETOS
                // =========================

                if (dados.todasCorretas) {

                    setOutput(
                        "✓ Todos os testes estão corretos!"
                    );

                    return;
                }


                // =========================
                // TESTES
                // =========================

                const mensagens = [];

                dados.resultados.forEach((resultado, indice) => {

                    if (resultado.checkcorreto) {

                        mensagens.push(
                            `Teste ${indice + 1}: correto`
                        );

                    } else {

                        mensagens.push(
                            `Teste ${indice + 1}: incorreto`
                        );

                        mensagens.push(
                            `  Sua saída: ${resultado.saida || "(vazio)"}`
                        );

                        mensagens.push(
                            `  Esperado: ${resultado.saida_esperada || "(vazio)"}`
                        );
                    }
                });

                setOutput(
                    mensagens.join("\n")
                );

            } catch (error) {

                setOutput(
                    error.message ||
                    "Erro ao verificar a solução."
                );

                console.error(error);

            } finally {

                setUIBusy(false);
            }
        });
    }

});