<?php
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

   <div class="editor-container">
    <form method="POST">
        <textarea name="java_code" rows="20" style="width:100%; font-family: monospace;"><?php 
            if (empty($aluno_code)) {
                // We provide the template, but the student CAN delete it.
                echo "import java.util.*;\n\npublic class Main {\n    public static void main(String[] args) {\n        \n    }\n}";
            } else {
                echo htmlspecialchars($aluno_code);
            }
        ?></textarea>
        <br>
        <button type="submit">Rodar Código</button>
    </form>
</div>

    <?php if ($output): ?>
        <div class="console">
            <pre><?php echo htmlspecialchars($output); ?></pre>
        </div>
    <?php endif; ?>
<script>
const editor = document.querySelector('textarea[name="java_code"]');

editor.addEventListener('keydown', function(e) {
    if (e.key === 'Tab') {
        e.preventDefault(); // Stop focus from leaving

        // Try 'insertText' first. This is the only way to keep Ctrl+Z working 
        // because it "fakes" a user typing action that the browser records.
        if (!document.execCommand("insertText", false, "\t")) {
            // Fallback for older browsers:
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.setRangeText('\t', start, end, 'end');
            
            // Manually trigger the input event for the fallback
            this.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }
});
</script>
</body>

</html>