<?php

require_once __DIR__ . "/database.php";
require_once __DIR__ . "/services/judge0.php";
require_once __DIR__ . "/utils/output.php";
require_once __DIR__ . "/sessao.php";

if(!userlogado()) {
    echo json_encode([
        "erro" => "Usuário não logado",
    ]);
    exit;
}
$usuario_id = pegarusuarioid();


$dados = json_decode(
    file_get_contents("php://input"),
    true
);

if (!is_array($dados)) {
    echo json_encode([
        "erro" => "Dados inválidos"
    ]);
    exit;
}

$exercicio_id = $dados["exercicio_id"] ?? null;
$codigo = $dados["codigo"] ?? null;

if (!$exercicio_id || $codigo === null) {
    echo json_encode([
        "erro" => "Dados incompletos."
    ]);
    exit;
}


if (trim($codigo) === "") {
    echo json_encode([
        "erro" => "O código não pode estar vazio."
    ]);
    exit;
}


$stmt = $pdo->prepare(
"SELECT aulas.posicao FROM aulas WHERE aulas.exercicio_id = ?"
);

$stmt->execute([$exercicio_id]);

$aula = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$aula) {
    echo json_encode([
        "erro" => "Aula não encontrada",
    ]);
    exit;
}

$stmt = $pdo->prepare(
"SELECT aula_atual FROM usuarios WHERE id = ?"
);

$stmt->execute([$usuario_id]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$usuario) {
    echo json_encode([
        "erro" => "Usuário não encontrado",
    ]);
    exit;
}
$posicaoAula = (int) $aula["posicao"];
$aula_atual = (int) $usuario["aula_atual"];


if ($posicaoAula > $aula_atual) {
    echo json_encode([
        "erro" => "Aula ainda não liberada."
    ]);
    exit;
}

$stmt = $pdo->prepare(
    "SELECT *
     FROM testes
     WHERE exercicio_id = ?"
);

$stmt->execute([$exercicio_id]);

$testes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!$testes) {
    echo json_encode([
        "erro" => "Testes não encontrados",
    ]);
    exit;
}


$judge0 = new Judge0();
$resultados = [];
$todasCorretas = true;

foreach ($testes as $teste) {

    $envio = $judge0->submitCodigo(
        $codigo,
        $teste["entrada"]
    );

    if (isset($envio["erro"])) {
        echo json_encode([
            "erro_execucao" => true,
            "mensagem" => $envio["erro"]
        ]);
        exit;
    }
    
    if (empty($envio["token"])) {
        echo json_encode([
            "erro_execucao" => true,
            "mensagem" => "Não foi possível iniciar a execução."
        ]);
        exit;
    }

    $token = $envio["token"];
    $tentativas = 0;
    $maxTentativas = 20;
    do {

        sleep(1);

        $resultado = $judge0->getSubmission($token);

        $statusId = $resultado["status"]["id"] ?? 0;

        $tentativas++;
        
    } while ($statusId <= 2 && $tentativas < $maxTentativas);

    if ($statusId <= 2) {
        echo json_encode([
            "erro_execucao" => true,
            "mensagem" => "Tempo limite excedido ao executar o código."
        ]);
        exit;
    }
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

    $checkcorreto = $saida === $saida_esperada;

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

if($todasCorretas && $posicaoAula === $aula_atual){
    $stmt = $pdo->prepare(
        "UPDATE usuarios SET aula_atual = aula_atual + 1 WHERE id = ? AND aula_atual = ?"
    );
    $stmt->execute([$usuario_id, $aula_atual]);
}
echo json_encode([
    "todasCorretas" => $todasCorretas,
    "resultados" => $resultados,
]);

