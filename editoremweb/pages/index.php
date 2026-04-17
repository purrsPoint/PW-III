<?php
require_once '../config/config.php';

$message = "";
$output = "";
$aluno_code = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['java_code'])) {
    $aluno_code = $_POST['java_code'];

    // Montagem do código completo
    $full_code = "public class Main {\n";
    $full_code .= "    public static void main(String[] args) {\n";
    $full_code .= "        " . $aluno_code . "\n";
    $full_code .= "    }\n";
    $full_code .= "}";

    $filepath = JAVA_TEMP_DIR . "Main.java";

    if (file_put_contents($filepath, $full_code)) {
        // Compila e Roda
        $compile = shell_exec("javac \"$filepath\" 2>&1");
        if ($compile) {
            $output = "Erro de Compilação:\n" . $compile;
        } else {
            $output = shell_exec("java -cp \"" . JAVA_TEMP_DIR . "\" Main 2>&1");
        }
        
        // Limpa os rastros
       @unlink($filepath);
       @unlink(JAVA_TEMP_DIR . "Main.class");
       
    }
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

    <h1>Editor de Java 🚀</h1>
    <p><?php echo $message; ?></p>

    <div class="editor-container">
        <form method="POST">
            <code class="fixed-code">public class Main {</code>
            <code class="fixed-code indent">public static void main(String[] args) {</code>
            
            <?php // o htmlespecialchars faz com q os caracteres especiais sejam interpretados de outra forma ent n da uns certos erros ?>
            <textarea name="java_code" rows="12" placeholder="// Escreva seu código aqui..."><?php echo htmlspecialchars($aluno_code); ?></textarea>
            
            <code class="fixed-code indent">}</code>
            <code class="fixed-code">}</code>
            <br>
            <button type="submit">Rodar Código</button>
        </form>
    </div>

    <?php if ($output): ?>
        <div class="console">
            <strong>Console:</strong><br>
            <?php echo htmlspecialchars($output); ?>
        </div>
    <?php endif; ?>

</body>
</html>