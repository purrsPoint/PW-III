<?php
    require_once "../backend/database.php";

    $tarefa = $_GET["p"] ?? 1;

    $stmt = $pdo->prepare("SELECT * FROM exercicios WHERE id = ?");
    $stmt->execute([$tarefa]);
    $exercicio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exercicio) {
        die("Exercício não encontrado");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Java School | Editor</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Monaco Editor via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.39.0/min/vs/loader.min.js"></script>
</head>
<body style="background-color: white; color: black!important;">

    <div class="editor-container" data-exercicio-id="<?= (int)$exercicio["id"] ?>">
        <h1><?= htmlspecialchars($exercicio["titulo"]) ?></h1>
        
        <div>
            <?= nl2br(htmlspecialchars($exercicio["descricao"]))?>
        </div>

        <!-- Div para o Monaco Editor -->
        <div id="editor-container" style="background-color: #1e1e1e;height: 350px; padding-top: 20px; border: 1px solid #ccc; margin-bottom: 15px;"></div>
        
        <!-- Guardar código inicial -->
        <textarea id="codigo-inicial" style="display:none;"><?= htmlspecialchars($exercicio["codigo_inicial"]) ?></textarea>

        <textarea id="stdin" placeholder="Entrada (stdin)" style="height:120px;"></textarea>

        <div class="button-group">
            <button id="runButton">Rodar Código</button>
            <button id="verifyButton">Verificar Solução</button>
        </div>

        <div id="loading" class="loading-container" style="display:none;">
            <div class="spinner"></div>
        </div>

        <div class="console" style="color: white;" id="output"></div>
    </div>

    <script type="module" src="js/editor.js"></script>
</body>
</html>