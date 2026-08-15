<?php

require_once __DIR__ . "/database.php";
require_once __DIR__ . "/services/judge0.php";

$dados = json_decode(
    file_get_contents("php://input"),
    true
);

$exercicio_id = $dados["exercicio_id"];
$codigo = $dados["codigo"];


$stmt = $pdo->prepare(
    "SELECT *
     FROM testes
     WHERE exercicio_id = ?"
);

$stmt->execute([$exercicio_id]);

$testes = $stmt->fetchAll(PDO::FETCH_ASSOC);


$judge0 = new Judge0();


foreach ($testes as $teste) {

    $envio = $judge0->submitCodigo(
        $codigo,
        $teste["entrada"]
    );

    echo json_encode($envio);

    exit;
}