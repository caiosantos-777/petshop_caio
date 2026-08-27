<?php
include 'infra/connect.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitarização e limpeza dos campos
    $nome = trim($_POST['nome'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $telefone = trim($_POST['telefone'] ?? '');

    // Validação básica
    if (empty($nome) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Por favor, preencha um nome e um e-mail válido.";
    } else {
        $stmt = $conn->prepare("INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sss", $nome, $email, $telefone);

            if ($stmt->execute()) {
                $mensagem = "Novo cliente cadastrado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $mensagem = "Erro na preparação da consulta: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Novo Cliente</title>
</head>
<body>
    <h2>Cadastrar Novo Cliente</h2>

    <?php if (!empty($mensagem)): ?>
        <p><strong><?= htmlspecialchars($mensagem) ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone">
        <br><br>

        <button type="submit">Cadastrar</button>
    </form> 
    <br>  

    <button type="button" onclick="window.location.href='../../index.php'">Voltar</button>
</body>
</html>