<?php

    require_once "../backend/database.php";

    $tarefa = $_GET["p"] ?? 1;

    $stmt = $pdo->prepare(
    "SELECT * FROM exercicios WHERE id = ?"
    );

    $stmt->execute([$tarefa]);
    //pdo::fetch_assoc faz com que o resultado seja
    // retornado como um array acessado pelo nome das colunas
    $exercicio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exercicio) {
        die("exercicio não encontrado");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Java School | Editor</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="editor-container" data-exercicio-id="<?= (int)$exercicio["id"] ?>">
            <h1>
                <?= htmlspecialchars($exercicio["titulo"]) ?>
            </h1>
            
            <div>
                <?= nl2br(htmlspecialchars($exercicio["descricao"]))?>
            </div>
    
            <textarea id="editor"><?= htmlspecialchars($exercicio["codigo_inicial"]) ?></textarea>
    
            <textarea id="stdin" placeholder="Entrada (stdin)" style="height:120px;"></textarea>

            <div class="button-group">
                <button id="runButton">Rodar Código</button>
                <button id="verifyButton">Verificar Solução</button>
            </div>
    
            <div id="loading" class="loading-container" style="display:none;">
                <div class="spinner"></div>
            </div>
    
            <div class="console" id="output"></div>
        </div>

<script type="module" src="js/editor.js"></script>
</body>

</html>
