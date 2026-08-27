<?php

include '../../infra/conexao.php';

$id = $_GET['id'];
$sql = "SELECT * FROM pets WHERE id = $id";
$pet_editantes = $conn->query($sql);
$pet = $pet_editantes->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $raca = $_POST['raca'];
    $idade = $_POST['idade'];
    $cliente_id = $_POST['cliente_id'];

    $sql = "UPDATE pets SET nome='$nome', especie='$especie', raca='$raca', idade='$idade', cliente_id='$cliente_id' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Pet atualizado com sucesso!";
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
    <title>Adicionar Novo Pet</title>
</head>

<body>
    <h2>Adicionar Novo Pet</h2>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $pet['nome']; ?>" required>
        <br><br>
        <label for="especie">Espécie:</label>
        <input type="text" id="especie" name="especie" value="<?php echo $pet['especie']; ?>" required>
        <br><br>
        <label for="raca">Raça:</label>
        <input type="text" id="raca" name="raca" value="<?php echo $pet['raca']; ?>">
        <br><br>
        <label for="idade">Idade:</label>
        <input type="number" id="idade" name="idade" value="<?php echo $pet['idade']; ?>">
        <br><br>

        <select name="cliente_id" required>
            <option value="" >Selecione o Cliente</option>
            <?php
                $sql = "SELECT id, nome FROM clientes";
                $clientes = $conn->query($sql);
                while ($cliente = $clientes->fetch_assoc()) {
            ?>

            <option value="<?php echo $cliente['id'];?>" <?php if ($pet['cliente_id'] == $cliente['id']) echo 'selected'; ?>>
                <?php echo $cliente['nome'];?>
            </option>


            <?php
                } 
            ?>
        </select>
        <button type="submit">Cadastrar Pet</button>
    </form>
    <br>
    <button type="button" onclick="window.location.href='../../index.php'">Voltar</button>
</body>

</html>