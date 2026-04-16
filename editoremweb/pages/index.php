<?php

//puxa a config
require_once '../config/config.php';

$message = ""; //vai falar c deu certo

//checa c o user clico no botao de confirma o cdg
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    //pega o codigo da text area apos ter CTZ que o metodo ta post(aperto o butao)
    $code = $_POST['java_code'];

    //define o caminho pra o arquivo
    $filepath = JAVA_TEMP_DIR . "Main.java";
    
    //agora ele realment cria com o fileputcontents e bota oq tava na area de code
    if(file_put_contents($filepath, $code)){
        $message = "Arquivo foi salvo direito em /temp/Main.java"
    }else{
        $message = "erro: nao conseguiu salvar arquivo";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de teste</title>
</head>
<body>

<h2>salva codig</h2>
    
<p><strong>status</strong> <?php echo $message; ?></p>

<form method="POST">
    <textarea name="java_code" id="java_area" rows="10" cols="50" placeholder="public class Main{ ... }"></textarea>
    <br>
    <button type="submit">roda cdg</button>
</form>
</body>
</html>