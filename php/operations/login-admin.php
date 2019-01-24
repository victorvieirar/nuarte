<?php 

require_once '../config/database.php';

if(isset($_POST['login'])) {
    $user = isset($_POST['user']) ? $_POST['user'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if(empty($user) || empty($password)) {
        session_start();
        $_SESSION['msg'] = "Usuário/Senha inválido";
        header('location: ../../painel');
        exit;
    }

    $sql = "SELECT * FROM admins WHERE user = '$user' AND password = $password";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    $admin = $stmt->fetch();

    if(!empty($admin)) {
        session_start();
        $_SESSION['admin'] = $admin;
        header('location: ../../painel');
    } else {
        session_start();
        $_SESSION['msg'] = "Usuário/Senha incorreto";
        header('location: ../../');
        exit;
    }
}

?>