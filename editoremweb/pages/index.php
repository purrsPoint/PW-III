<?php
require_once '../config/config.php';
require_once __DIR__ . '/../config/exec64.php';

$message = "";
$output = "";
$aluno_code = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['java_code'])) {

    $aluno_code = $_POST['java_code'];

    //envia pra api
    $output = execode($aluno_code);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Java School | Editor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <h1>Editor de Java</h1>
    <p><?php echo $message; ?></p>

    <div class="editor-container">
        <form method="POST">
            <code class="fixed-code">public class Main {</code>
            <code class="fixed-code indent">public static void main(String[] args) {</code>

            <?php // o htmlespecialchars faz com q os caracteres especiais sejam interpretados de outra forma ent n da uns certos erros ?>
            <textarea name="java_code" rows="12"
                placeholder="// Escreva seu código aqui..."><?php echo htmlspecialchars($aluno_code); ?></textarea>

            <code class="fixed-code indent">}</code>
            <code class="fixed-code">}</code>
            <br>
            <button type="submit">Rodar Código</button>
        </form>
    </div>

    <?php if ($output): ?>
        <div class="console">
            <pre><?php echo htmlspecialchars($output); ?></pre>
        </div>
    <?php endif; ?>

</body>

</html>