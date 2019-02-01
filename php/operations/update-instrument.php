<?php 

require_once '../config/database.php';

session_start();
if(!isset($_SESSION['admin'])) {
    header('location: ../../painel');
}

if(isset($_POST['add'])) {
    $reference = isset($_POST['reference']) ? $_POST['reference'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';

    if(empty($reference) || empty($name)) {
        $_SESSION['msg'] = "Dados não preenchidos corretamente";
        header('location: ../../usuario');
        exit;
    }

    $sql = "UPDATE instruments SET ";
    $sql .= "name = :name WHERE reference = :reference";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":name", $name);
    $stmt->bindValue(":reference", $reference);

    try {
        $success = $stmt->execute();
    } catch(PDOException $pdoe) {
        switch($pdoe->getCode()) {
            default:
                $_SESSION['msg'] = "Erro desconhecido ao tentar cadastrar instrumento. Tente novamente.";
                header('location: ../../painel');
                exit;
        }
    }

    if($success) {
        $_SESSION['msg'] = "Instrumento cadastrado com sucesso!";
        header('location: ../../painel');
    } else {
        $_SESSION['msg'] = "Erro ao cadastrar instrumento, tente novamente.";
        header('location: ../../painel');
        exit;
    }
}

?>