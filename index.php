<?php
include 'infra/connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Petshop</title>
</head>

<body>
    <h2>Petshop</h2>

    <button type="button" onclick="window.location.href='public/cadastrar_cliente.php'">Cadastrar Cliente</button>
    <button type="button" onclick="window.location.href='public/cadastrar_pet.php'">Cadastrar Pet</button>

    <br>
    <h2>Lista de Clientes</h2>

    <table>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Ações</th>
        <?php
        include '../infra/connect.php';
        $sql = "SELECT * FROM clientes";
        $clientes = $conn->query($sql);
        while ($cliente = $clientes->fetch_assoc()) {
        ?>

            <tr>
                <td><?php echo $cliente['id']; ?></td>
                <td><?php echo $cliente['nome']; ?></td>
                <td><?php echo $cliente['email']; ?></td>
                <td><?php echo $cliente['telefone']; ?></td>
                <td>
                    <button type="button" onclick="window.location.href='public/edit_cliente.php?id=<?php echo $cliente['id']; ?>'">Editar</button>
                    <button type="button" onclick="if (confirm('Tem certeza que deseja excluir este cliente?')) { window.location.href='public/clientes/apagar_cliente.php?id=<?php echo $cliente['id']; ?>'; }">Excluir</button>
                </td>
            </tr>

        <?php
        }
        ?>
    </table>

    <h2>Lista de Pets</h2>
    <table>
        <th>ID</th>
        <th>Nome</th>
        <th>Espécie</th>
        <th>Raça</th>
        <th>Idade</th>
        <th>ID do Cliente</th>
        <th>Ações</th>
        <?php
        $sql = "SELECT * FROM pets";
        $pets = $conn->query($sql);
        while ($pet = $pets->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $pet['id']; ?></td>
                <td><?php echo $pet['nome']; ?></td>
                <td><?php echo $pet['especie']; ?></td>
                <td><?php echo $pet['raca']; ?></td>
                <td><?php echo $pet['idade']; ?></td>
                <td><?php echo $pet['cliente_id']; ?></td>
                <td>
                    <button type="button" onclick="window.location.href='public/edit_animal.php?id=<?php echo $pet['id']; ?>'">Editar</button>
                    <button type="button" onclick="if (confirm('Tem certeza que deseja excluir este pet?')) { window.location.href='public/pets/apagar_animal.php?id=<?php echo $pet['id']; ?>'; }">Excluir</button>
                </td>
            </tr>

        <?php
        }
        ?>
</body>

</html>