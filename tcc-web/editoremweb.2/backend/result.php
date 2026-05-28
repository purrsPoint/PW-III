<?php

header("Content-Type: application/json");

require_once __DIR__ ."/services/judge0.php";
require_once __DIR__ ."/utils/output.php";

$token = $_GET["token"] ?? "";

if(empty(trim($token))){

    echo json_decode([
        "success" => false,
        "erro" => "Token invalido"
    ]);

    exit;
}

$judge0 = new judge0();

$resultadoExecucao = $judge0->getSubmission($token);

if(isset($result["erro"])){

    echo json_encode([
        "success" => false,
        "erro" => $result["erro"]
    ]);

    exit;
}

$statusId = $resultadoExecucao["status"]["id"] ?? 0;

$resposta = [

    "success" => true,
    "finalizado" => $statusId > 2;
    "status" => $resultadoExecucao["status"] ?? null
];

if($statusId > 2){
    $resposta["stdout"] = normalizarOutput(
        decodificar(
            $resultadoExecucao["stdout"] ?? ""
        )
    );

    $resposta["stderr"] = normalizarOutput(
        decodificar(
            $resultadoExecucao["stderr"] ?? ""
        )
    );

    $resposta["compile_output"] = normalizarOutput(
        decodificar(
            $resultadoExecucao["compile_output"] ?? ""
        )
    );

    echo json_encode($resposta);
}