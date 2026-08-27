<?php

include '../infra/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $raca = $_POST['raca'];
    $idade = $_POST['idade'];
    $cliente_id = $_POST['cliente_id'];
    $sql = "INSERT INTO pets (nome, especie, raca, idade, cliente_id) VALUES ('$nome', '$especie', '$raca', '$idade', '$cliente_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Novo pet cadastrado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Animal</title>
</head>
<body>
    <h2>Adicionar Animal</h2>
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
        <input type="number" id="idade" name="idade">
        <br><br>
        <select name="cliente_id" required>
            <option value="">Selecione o Cliente</option>
            <?php
                $sql = "SELECT id, nome FROM clientes";
                $clientes = $conn->query($sql);
                while ($cliente = $clientes->fetch_assoc()) {
            ?>
            <option value="<?php echo $cliente['id'];?>"><?php echo $cliente['nome'];?></option>
            <?php
                } 
            ?>
        </select>
        <button type="submit">Cadastrar Pet</button>
    </form>
    <br>
    <button type="button" onclick="window.location.href='../index.php'">Voltar</button>
</body>

</html>