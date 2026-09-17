<?php

require_once "../backend/database.php";

$stmt = $pdo->prepare("SELECT * FROM exercicios");

$stmt->execute();
//pdo::fetch_assoc faz com que os dados sejam
// retornados como um array acessado pelos nomes das colunas
$exercicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Java School | Tarefas</title>
    <link rel="stylesheet" href="css/tarefas.css">

</head>

<body>

    <h1>Exercícios de Java</h1>

    <div class="tasks">

    <?php foreach ($exercicios as $t): ?>

    <a
        class = "task"
        href="index.php?p=<?= $t["id"]?>"
    >

        <div class="task-title">
            <?= htmlspecialchars($t["titulo"]) ?>
        </div>

        <div class="task-description">
            <?= htmlspecialchars($t["descricao"]) ?>
        </div>

    </a>

    <?php endforeach; ?>

    </div>

</body>

</html>
