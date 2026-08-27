<?php
$id = $_GET['id'];
include 'infra/connect.php';

$sql = "DELETE FROM pets WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo "Pet excluído!<br>";
    echo "<button type='button' onclick=\"window.location.href='../index.php'\">Voltar</button>";
} else {
    echo "Erro ao excluir pet: " . $conn->error;
}