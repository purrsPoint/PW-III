<?php

    require_once "../backend/database.php";

    $tarefa = $_GET["p"] ?? 1;

    $stmt = $pdo->prepare(
    "SELECT * FROM exercicios WHERE id = ?"
    );

    $stmt->execute([$tarefa]);
    //pdo::fetch_assoc faz com que o resultado seja
    // retornado como um array acessado pelo nome das colunas
    $exercicio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exercicio) {
        die("exercicio não encontrado");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Java School | Editor</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="editor-container">
        <h1>
            <?= $exercicio["titulo"] ?>
        </h1>
    <div>
        <?= nl2br(htmlspecialchars($exercicio["descricao"]))?>
    </div>
        <textarea id="editor">
            <?= $exercicio["codigo_inicial"] ?>
        </textarea>

        <textarea id="stdin" placeholder="Entrada (stdin)" style="height:120px;"></textarea>

        <button id="runButton">
            Rodar Código
        </button>

        <div id="loading" class="loading-container">

            <div class="spinner"></div>

        </div>

        <div class="console" id="output"></div>

    </div>

    <script>

        const editor =
            document.getElementById("editor");

        const stdin =
            document.getElementById("stdin");

        const runButton =
            document.getElementById("runButton");

        const output =
            document.getElementById("output");

        const loading =
            document.getElementById("loading");

        /* suporte para TAB */

        editor.addEventListener(
            "keydown",
            function (e) {

                if (e.key === "Tab") {

                    e.preventDefault();

                    const inicio =
                        this.selectionStart;

                    const fim =
                        this.selectionEnd;

                    this.setRangeText(
                        "    ",
                        inicio,
                        fim,
                        "end"
                    );
                }
            }
        );

        /* execução */

        runButton.addEventListener(
            "click",
            async () => {

                output.textContent = "";

                loading.style.display = "flex";

                runButton.disabled = true;

                try {

                    /* envia código */

                    const response =
                        await fetch(
                            "../backend/submit.php",
                            {

                                method: "POST",

                                headers: {
                                    "Content-Type":
                                        "application/json"
                                },

                                body: JSON.stringify({

                                    codigo:
                                        editor.value,

                                    entrada:
                                        stdin.value
                                })
                            }
                        );

                    const submitData =
                        await response.json();

                    if (!submitData.success) {

                        throw new Error(
                            submitData.erro
                        );
                    }

                    const token =
                        submitData.token;

                    /* inicia polling */

                    const interval =
                        setInterval(
                            async () => {

                                try {

                                    const resultResponse =
                                        await fetch(
                                            `../backend/result.php?token=${token}`
                                        );

                                    const resultData =
                                        await resultResponse.json();

                                    /* finalizou */

                                    if (resultData.finalizado) {

                                        clearInterval(
                                            interval
                                        );

                                        loading.style.display =
                                            "none";

                                        runButton.disabled =
                                            false;

                                        if (
                                            resultData.compile_output
                                        ) {

                                            output.textContent =
                                                resultData.compile_output;

                                            return;
                                        }

                                        if (
                                            resultData.stderr
                                        ) {

                                            output.textContent =
                                                resultData.stderr;

                                            return;
                                        }

                                        output.textContent =
                                            resultData.stdout ||
                                            "Sem saída";
                                    }

                                } catch (error) {

                                    clearInterval(
                                        interval
                                    );

                                    loading.style.display =
                                        "none";

                                    runButton.disabled =
                                        false;

                                    output.textContent =
                                        "Erro ao consultar execução.";

                                    console.error(
                                        error
                                    );
                                }

                            },
                            1500
                        );

                } catch (error) {

                    loading.style.display =
                        "none";

                    runButton.disabled =
                        false;

                    output.textContent =
                        error.message;

                    console.error(error);
                }

            }
        );

        //temporario
         fetch("../backend/verificar_resultado.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                exercicio_id: <?= $exercicio["id"] ?>,
                codigo: editor.value
            })
        })
        .then(resposta => resposta.json())
        .then(dados => {
            console.log(dados);
        });

    </script>

</body>

</html>
