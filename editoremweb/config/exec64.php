<?php
/*
 * WHY BASE64?
 * 1. PREVENTION: Code contains symbols like &, ", < that break JSON and HTTP requests.
 * 2. INTEGRITY: Base64 turns code into a safe alphanumeric string, ensuring the 
 * compiler receives EXACTLY what the student typed without corruption.
 * 3. COMPLIANCE: It is the official recommendation for the Judge0 API to avoid 400 Errors.
 */
function execode($aluno_code) {
    // Boilerplate with Imports//boilerplate são partes do cdg q vao ser imprimidas e n podem ser mudadas
    
    $full_code = "import java.util.*;\nimport java.io.*;\npublic class Main {\n";
    $full_code .= "public static void main(String[] args){\n";
    $full_code .= $aluno_code . "\n";
    $full_code .= "}\n}";

    $url = "https://ce.judge0.com/submissions?base64_encoded=true&wait=false";

    $dados = [
        "language_id" => 62, // Java (OpenJDK 13.0.1)
        "source_code" => base64_encode($full_code)
    ];
    
    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content"=> json_encode($dados)
        ]
    ];
    $cntxx = stream_context_create($options);
    $resl = @file_get_contents($url, false, $cntxx);

    if($resl === FALSE){
        return "erro ao conectar com api";
    }

    $rpapi = json_decode($resl, true);
    $token = $rpapi["token"] ?? null;

    if(!$token){
        return "sem token";
    }

    //fazer o polling -- ficar checando se retornou
    $urlresult = "https://ce.judge0.com/submissions/$token?base64_encoded=true";
    $max = 10;
    $try = 0;

    do{

    $final = file_get_contents($urlresult);
    $finaldata = json_decode($final, true);

    // 'status_id': 1 = In Queue/fila, 2 = Processing/indo, 3 = Accepted (Done).
    $status = $finaldata["status"]["id"] ?? 0;

        if ($status <= 2) {
            sleep(1); //da um segundinho par n ficar spammando na api
        }
        $try++;

    } while ($status <= 2 && $try < $max);

    // Status 6 = Compilation Error (Syntax errors like missing semicolons).
    if ($status === 6) {
        // We MUST base64_decode the output because we requested base64 in the URL.
        return "Erro de Compilação:\n" . base64_decode($finaldata["compile_output"] ?? "");
    }

    // Status > 3 = Runtime errors (StackOverflow, Divide by Zero, etc.).
    if ($status > 3) {
        return "Erro de Execução (" . $finaldata["status"]["description"] . "):\n" . 
               base64_decode($finaldata["stderr"] ?? "");
    }
    
    // Status 3 = Success. Return the 'stdout' (Standard Output).
    return base64_decode($finaldata["stdout"] ?? "codigo retornado com sucesso");
    }
?>