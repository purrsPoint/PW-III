<?php

require_once "../backend/database.php";
require_once "../backend/sessao.php";

precisalogar();

$nome = pegarnomeusuario();
$usuarioid = pegarusuarioid();

$stmt = $pdo->prepare("SELECT aula_atual FROM usuarios WHERE id =  ?");

$stmt->execute([$usuarioid]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$usuario){
    header("Location: ../backend/logout.php");
    exit;
}

$aula_atual = (int) $usuario["aula_atual"];

$stmt = $pdo->prepare("SELECT * FROM aulas ORDER BY posicao ASC");

$stmt->execute();
//pdo::fetch_assoc faz com que os dados sejam
// retornados como um array acessado pelos nomes das colunas
$aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Aulas</title>

    <link rel="stylesheet" href="css/aulas.css">
 
</head>

<body>
    <a href="usuario.php">
        <?= htmlspecialchars($nome) ?>
    </a>
<?php foreach ($aulas as $a): ?>

    <?php
    $posicao = (int) $a["posicao"];

    $concluido = $posicao < $aula_atual;
    $afazer = $posicao === $aula_atual;
    $fechada = $posicao > $aula_atual;
    ?>

    <?php if ($concluido || $afazer): ?>
    <a class="aula <?= $concluido ? "concluida" : "afazer" ?>" href="aula.php?id=<?= $a["id"]?>">

    <?php else: ?>

   <div class="aula_fechada">

    <?php endif; ?>

    <h2><?= htmlspecialchars($a["titulo"]) ?></h2>


    <?php if ($concluido): ?>

    <p>concluída</p>

    <?php elseif ($afazer): ?>

    <p>a fazer</p>

    <?php else: ?>

    <p>fechada</p>

    <?php endif; ?>


    <?php if ($afazer || $concluido): ?>

    </a>

    <?php else: ?>

    </div>

    <?php endif; ?>

<?php endforeach; ?>

</body>

</html>
