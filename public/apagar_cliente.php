<?php
$id = $_GET['id'];
include 'infra/connect.php';

$sql = "DELETE FROM clientes WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo "Cliente excluído!<br>";
    echo "<button type='button' onclick=\"window.location.href='index.php'\">Voltar</button>";
} else {
    echo "Erro ao excluir cliente: " . $conn->error;
}
?>