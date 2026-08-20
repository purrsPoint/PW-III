<?php

require_once __DIR__ . "/database.php";
require_once __DIR__ . "/services/judge0.php";
require_once __DIR__ . "/utils/output.php";


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
$resultados = [];
$todasCorretas = true;

foreach ($testes as $teste) {

    $envio = $judge0->submitCodigo(
        $codigo,
        $teste["entrada"]
    );

    $token = $envio["token"];

    do {

        sleep(1);

        $resultado = $judge0->getSubmission($token);

        $statusId = $resultado["status"]["id"];

    } while ($statusId <= 2);

    $errocompilacao = decodificar($resultado["compile_output"]?? "");
    $stderr = decodificar($resultado["stderr"]?? "");

    if($statusId === 6 || !empty($errocompilacao)){
        echo json_encode([ 
            "erro_compilacao" => true,
            "mensagem" => $errocompilacao ?: "Erro de compilação",
        ]);
        exit;
    }

    if(!empty($stderr)){
        echo json_encode([
            "erro_execucao" => true,
            "mensagem" => $stderr,
        ]);
        exit;
    }

    $saida = normalizarOutput(decodificar($resultado["stdout"]));
    $saida_esperada = normalizarOutput($teste["saida_esperada"]);

    $checkcorreto = strtoupper($saida) === strtoupper($saida_esperada);

    if(!$checkcorreto){
        $todasCorretas = false;
    }

    $resultados[] = [
        "checkcorreto" => $checkcorreto,
        "saida" => $saida,
        "saida_esperada" => $saida_esperada,
        "status" => $resultado["status"],
    ];
}
echo json_encode([
    "todasCorretas" => $todasCorretas,
    "resultados" => $resultados,
]);

