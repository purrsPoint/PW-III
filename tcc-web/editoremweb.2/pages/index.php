<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Java School | Editor</title>

    <style>

        body{
            background: #111;
            color: white;
            font-family: Arial;
            padding: 20px;
        }

        .editor-container{
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        textarea{
            width: 100%;
            height: 400px;
            background: #1e1e1e;
            color: white;
            border: 1px solid #444;
            padding: 15px;
            font-family: monospace;
            resize: vertical;
        }

        button{
            width: 200px;
            padding: 10px;
            cursor: pointer;
        }

        .console{
            background: black;
            padding: 15px;
            min-height: 120px;
            white-space: pre-wrap;
            border: 1px solid #444;
        }

        .status{
            color: #aaa;
        }

    </style>

</head>

<body>

<div class="editor-container">

    <textarea id="editor">
import java.util.*;

public class Main {

    public static void main(String[] args) {

        System.out.println("Hello World");

    }
}
    </textarea>

    <textarea
        id="stdin"
        placeholder="Entrada (stdin)"
        style="height:120px;"
    ></textarea>

    <button id="runButton">
        Rodar Código
    </button>

    <div class="status" id="status">
        Aguardando execução...
    </div>

    <div class="console" id="output"></div>

</div>

<script>

const editor = document.getElementById("editor");

const stdin = document.getElementById("stdin");

const runButton = document.getElementById("runButton");

const output = document.getElementById("output");

const statusElement = document.getElementById("status");

/*
|--------------------------------------------------------------------------
| TAB SUPPORT
|--------------------------------------------------------------------------
*/

editor.addEventListener("keydown", function(e){

    if(e.key === "Tab"){

        e.preventDefault();

        const start = this.selectionStart;

        const end = this.selectionEnd;

        this.setRangeText(
            "    ",
            start,
            end,
            "end"
        );
    }
});

/*
|--------------------------------------------------------------------------
| EXECUTION
|--------------------------------------------------------------------------
*/

runButton.addEventListener("click", async () => {

    output.textContent = "";

    statusElement.textContent = "Enviando código...";

    /*
    |--------------------------------------------------------------------------
    | SUBMIT CODE
    |--------------------------------------------------------------------------
    */

    const response = await fetch(
        "../backend/submit.php",
        {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({

                codigo: editor.value,

                entrada: stdin.value
            })
        }
    );

    const submitData = await response.json();

    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    if(!submitData.success){

        output.textContent = submitData.erro;

        statusElement.textContent = "Erro";

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | TOKEN
    |--------------------------------------------------------------------------
    */

    const token = submitData.token;

    statusElement.textContent = "Código enviado";

    /*
    |--------------------------------------------------------------------------
    | START POLLING
    |--------------------------------------------------------------------------
    */

    const interval = setInterval(async () => {

        statusElement.textContent =
            "Executando código...";

        const resultResponse = await fetch(

            `../backend/result.php?token=${token}`

        );

        const resultData =
            await resultResponse.json();

        /*
        |--------------------------------------------------------------------------
        | FINISHED?
        |--------------------------------------------------------------------------
        */

        if(resultData.finalizado){

            clearInterval(interval);

            statusElement.textContent =
                "Execução finalizada";

            /*
            |--------------------------------------------------------------------------
            | PRIORITY OUTPUT
            |--------------------------------------------------------------------------
            */

            if(resultData.compile_output){

                output.textContent =
                    resultData.compile_output;

                return;
            }

            if(resultData.stderr){

                output.textContent =
                    resultData.stderr;

                return;
            }

            output.textContent =
                resultData.stdout || "Sem saída";
        }

    }, 1500);

});

</script>

</body>

</html>