<?php
include 'infra/connect.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura e sanitarização dos inputs
    $nome = trim($_POST['nome'] ?? '');
    $especie = trim($_POST['especie'] ?? '');
    $raca = trim($_POST['raca'] ?? '');
    // Caso a idade esteja vazia, define como NULL, senão converte para inteiro
    $idade = (isset($_POST['idade']) && $_POST['idade'] !== '') ? (int)$_POST['idade'] : null;
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);

    // Validação básica
    if (empty($nome) || empty($especie) || !$cliente_id) {
        $mensagem = "Por favor, preencha o nome, a espécie e selecione um cliente válido.";
    } else {
        // Prepared Statement para inserção segura
        $stmt = $conn->prepare("INSERT INTO pets (nome, especie, raca, idade, cliente_id) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt) {
            // "sssii": 3 strings (nome, especie, raca) e 2 inteiros (idade, cliente_id)
            $stmt->bind_param("sssii", $nome, $especie, $raca, $idade, $cliente_id);

            if ($stmt->execute()) {
                $mensagem = "Novo pet cadastrado com sucesso!";
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
    <title>Adicionar Animal</title>
</head>
<body>
    <h2>Adicionar Animal</h2>

    <?php if (!empty($mensagem)): ?>
        <p><strong><?= htmlspecialchars($mensagem) ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br><br>

        <label for="especie">Espécie:</label>
        <input type="text" id="especie" name="especie" required>
        <br><br>

        <label for="raca">Raça:</label>
        <input type="text" id="raca" name="raca">
        <br><br>

        <label for="idade">Idade:</label>
        <input type="number" id="idade" name="idade" min="0">
        <br><br>

        <label for="cliente_id">Cliente:</label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">Selecione o Cliente</option>
            <?php
            $sql = "SELECT id, nome FROM clientes ORDER BY nome ASC";
            $clientes = $conn->query($sql);

            if ($clientes && $clientes->num_rows > 0) {
                while ($cliente = $clientes->fetch_assoc()) {
                    $id = (int)$cliente['id'];
                    $nomeCliente = htmlspecialchars($cliente['nome']);
                    echo "<option value=\"{$id}\">{$nomeCliente}</option>";
                }
            }
            ?>
        </select>
        <br><br>

        <button type="submit">Cadastrar Pet</button>
    </form>
    <br>

    <button type="button" onclick="window.location.href='index.php'">Voltar</button>
</body>
</html>