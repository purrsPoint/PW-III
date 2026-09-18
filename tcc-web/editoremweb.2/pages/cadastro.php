<?php

$erro = $_GET["erro"] ?? "";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Java School | Criar conta</title>

    <link rel="stylesheet" href="css/conta.css">

</head>

<body>

    <main class="conta-container">

        <div class="conta-box">

            <h1>Criar conta</h1>

            <?php if ($erro === "preenchimento"): ?>

                <p class="erro">
                    Preencha todos os campos.
                </p>

            <?php elseif ($erro === "email_invalido"): ?>

                <p class="erro">
                    Digite um e-mail válido.
                </p>

            <?php elseif ($erro === "senha_curta"): ?>

                <p class="erro">
                    A senha deve ter pelo menos 8 caracteres.
                </p>

            <?php elseif ($erro === "email_existente"): ?>

                <p class="erro">
                    Este e-mail já está cadastrado.
                </p>

            <?php endif; ?>

            <form
                action="../backend/cadastro.php"
                method="POST"
            >

                <label for="nome">
                    Nome
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    required
                >

                <label for="email">
                    E-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                >

                <label for="senha">
                    Senha
                </label>

                <input
                    type="password"
                    id="senha"
                    name="senha"
                    required
                    minlength="8"
                >

                <button type="submit">
                    Criar conta
                </button>

            </form>

            <p class="conta-link">
                Já possui uma conta?
                <a href="login.php">
                    Entrar
                </a>
            </p>

        </div>

    </main>

</body>

</html>