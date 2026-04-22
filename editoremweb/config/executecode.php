<?php


function execode($aluno_code)
{

    $full_code = "\npublic class Main {\n";
    $full_code .= "     public static void main(String[] args){\n";

    //onde o codigo do aluno vai
    $full_code .= "         " . $aluno_code . "\n";

    $full_code .= "     }\n";
    $full_code .= "}";

    //url da api
    $url = "https://ce.judge0.com/submissions";

    //oque vai mandar pra api 
    //id62 é o do java
    //source_code precisa ser base64 pq a api qr

    $dados = [
        "language_id" => 62,
        "source_code" => $full_code,
        "base64_encoded" => false
    ];

    //preparando o post, são as regras de como vai mandar basicamente
    $options = [
        "http" => [
            // \r\n é a forma correta de quebrar pq http é veio e estranho
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($dados)
        ]
    ];

    //tranforma as regras em algo utilizavel
    $contextenv = stream_context_create($options);

    //envia pro sv o codigo dps de ser passado 
    $resultado = file_get_contents($url, false, $contextenv);

    if ($resultado === FALSE) {
        return "erro na hora de conectar com a api";
    }

    //retorna o resultado da api
    $respostaapi = json_decode($resultado, true);

    $token = $respostaapi["token"] ?? null;

    if (!$token) {
        return "erro: nao veio token da api";
    }

    //buscar resultado da execução
    $url_result = "https://ce.judge0.com/submissions/$token?base64_encoded=false";

    $max_tries = 10;
    $try = 0;

    do {

        $final = file_get_contents($url_result);

        if ($final === FALSE) {
            return "erro ao buscar resultado da execucao";
        }

        $dadosfinal = json_decode($final, true);

        $status = $dadosfinal["status"]["id"] ?? 0;

        // 1 = in queue
        // 2 = processing
        if ($status <= 2) {
            sleep(1);
        }

        $try++;

    } while ($status <= 2 && $try < $max_tries);

    //erro na hora de compilar
    if (!empty($dadosfinal["compile_output"])) {
        return "Erro de compilação:\n" . $dadosfinal["compile_output"];
    }

    //erro de execução
    if (!empty($dadosfinal["stderr"])) {
        return "Erro de execução:\n" . $dadosfinal["stderr"];
    }

    //retorna output
    return $dadosfinal["stdout"] ?? "sem saída.";
}