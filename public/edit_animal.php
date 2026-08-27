<?php
include 'infra/connect.php';

$mensagem = '';
// Validação do ID da URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("ID do pet inválido ou não informado.");
}

// Processamento da atualização via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $especie = trim($_POST['especie'] ?? '');
    $raca = trim($_POST['raca'] ?? '');
    $idade = (isset($_POST['idade']) && $_POST['idade'] !== '') ? (int)$_POST['idade'] : null;
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);

    if (empty($nome) || empty($especie) || !$cliente_id) {
        $mensagem = "Por favor, preencha o nome, a espécie e selecione um cliente válido.";
    } else {
        $stmt_update = $conn->prepare("UPDATE pets SET nome = ?, especie = ?, raca = ?, idade = ?, cliente_id = ? WHERE id = ?");
        if ($stmt_update) {
            $stmt_update->bind_param("sssiii", $nome, $especie, $raca, $idade, $cliente_id, $id);

            if ($stmt_update->execute()) {
                $mensagem = "Pet atualizado com sucesso!";
            } else {
                $mensagem = "Erro ao atualizar: " . $stmt_update->error;
            }
            $stmt_update->close();
        } else {
            $mensagem = "Erro na preparação da consulta: " . $conn->error;
        }
    }
}

$stmt_select = $conn->prepare("SELECT nome, especie, raca, idade, cliente_id FROM pets WHERE id = ?");
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$pet = $result->fetch_assoc();
$stmt_select->close();

if (!$pet) {
    die("Pet não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pet</title>
</head>

<body>
    <h2>Editar Pet</h2>

    <?php if (!empty($mensagem)): ?>
        <p><strong><?= htmlspecialchars($mensagem) ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($pet['nome']) ?>" required>
        <br><br>

        <label for="especie">Espécie:</label>
        <input type="text" id="especie" name="especie" value="<?= htmlspecialchars($pet['especie']) ?>" required>
        <br><br>

        <label for="raca">Raça:</label>
        <input type="text" id="raca" name="raca" value="<?= htmlspecialchars($pet['raca'] ?? '') ?>">
        <br><br>

        <label for="idade">Idade:</label>
        <input type="number" id="idade" name="idade" value="<?= htmlspecialchars($pet['idade'] ?? '') ?>" min="0">
        <br><br>

        <label for="cliente_id">Cliente:</label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">Selecione o Cliente</option>
            <?php
            $sql = "SELECT id, nome FROM clientes ORDER BY nome ASC";
            $clientes = $conn->query($sql);

            if ($clientes && $clientes->num_rows > 0) {
                while ($cliente = $clientes->fetch_assoc()) {
                    $clienteId = (int)$cliente['id'];
                    $nomeCliente = htmlspecialchars($cliente['nome']);
                    $selected = ($pet['cliente_id'] == $clienteId) ? 'selected' : '';
                    
                    echo "<option value=\"{$clienteId}\" {$selected}>{$nomeCliente}</option>";
                }
            }
            ?>
        </select>
        <br><br>

        <button type="submit">Salvar Alterações</button>
    </form>
    <br>
    <button type="button" onclick="window.location.href='index.php'">Voltar</button>
</body>

</html>