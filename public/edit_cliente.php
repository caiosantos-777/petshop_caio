<?php
include 'infra/connect.php';

$mensagem = '';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("ID de cliente inválido ou não informado.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $telefone = trim($_POST['telefone'] ?? '');

    if (empty($nome) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Por favor, preencha um nome e um e-mail válido.";
    } else {
   
        $stmt_update = $conn->prepare("UPDATE clientes SET nome = ?, email = ?, telefone = ? WHERE id = ?");
        
        if ($stmt_update) {
      
            $stmt_update->bind_param("sssi", $nome, $email, $telefone, $id);

            if ($stmt_update->execute()) {
                $mensagem = "Cliente atualizado com sucesso!";
            } else {
                $mensagem = "Erro ao atualizar: " . $stmt_update->error;
            }
            $stmt_update->close();
        } else {
            $mensagem = "Erro na preparação da consulta: " . $conn->error;
        }
    }
}


$stmt_select = $conn->prepare("SELECT nome, email, telefone FROM clientes WHERE id = ?");
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$cliente = $result->fetch_assoc();
$stmt_select->close();

if (!$cliente) {
    die("Cliente não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
</head>
<body>
    <h2>Editar Cliente</h2>

    <?php if (!empty($mensagem)): ?>
        <p><strong><?= htmlspecialchars($mensagem) ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome'] ?? '') ?>" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email'] ?? '') ?>" required>
        <br><br>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>">
        <br><br>

        <button type="submit">Salvar Alterações</button>
    </form> 
    <br>  

    <button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>
</html>