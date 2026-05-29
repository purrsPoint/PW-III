<?php

header("Content-Type: application/json");

require_once __DIR__ ."/services/judge0.php";

$dados = json_decode(
    file_get_contents("php://input"),
    true
);

$codigo = $dados["codigo"] ?? "";
$entrada = $dados["entrada"] ?? "";

if(empty(trim($codigo))) {

    echo json_encode([
        "success" => false,
        "erro" => "Código vazio"
    ]);

    exit;
}

$judge0 = new Judge0();

$resultado = $judge0->submitCodigo(
    $codigo,
    $entrada
);

if(isset($resultado["erro"])){

    echo json_encode([
        "success" => false,
        "erro" => $resultado["erro"]
    ]);

    exit;
}

echo json_encode([
    "success" => true,
    "token" => $resultado["token"] ?? null
]);

?>