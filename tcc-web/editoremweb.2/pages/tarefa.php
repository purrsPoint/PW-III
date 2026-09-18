<?php
    require_once "../backend/database.php";
    require_once "../backend/sessao.php";

    precisalogar();
    
    $exercicioId = $_GET["p"] ?? null;

    if (!$exercicioId) {
        die("Exercício não encontrado");
    }
    $usuario_id = pegarusuarioid();
    
    $stmt = $pdo->prepare(
        "SELECT aula_atual
         FROM usuarios
         WHERE id = ?"
    );
    
    $stmt->execute([$usuario_id]);
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        die("Usuário não encontrado");
    }
    
    $aula_atual = (int) $usuario["aula_atual"];
    
    $stmt = $pdo->prepare(
        "SELECT posicao
         FROM aulas
         WHERE exercicio_id = ?"
    );
    
    $stmt->execute([$exercicioId]);
    
    $aula = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$aula) {
        die("Aula não encontrada");
    }
    
    if ((int) $aula["posicao"] > $aula_atual) {
        header("Location: aulas.php");
        exit;
    }
    $stmt = $pdo->prepare("SELECT * FROM exercicios WHERE id = ?");
    $stmt->execute([$exercicioId]);
    $exercicio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exercicio) {
        die("Exercício não encontrado");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editor</title>
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
        
        <!-- Guarda p código inicial -->
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
<script type="module" src="js/editor.js?v=2"></script>
</body>
</html>