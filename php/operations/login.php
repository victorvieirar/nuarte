<?php 

require_once '../config/database.php';

if(isset($_POST['login'])) {
    $enrollment = isset($_POST['enrollment']) ? $_POST['enrollment'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if(empty($enrollment) || empty($password)) {
        session_start();
        $_SESSION['msg'] = "Usuário/Senha inválido";
        header('location: ../../');
        exit;
    }

    $sql = "SELECT * FROM students WHERE enrollment = $enrollment AND password = $password";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    $student = $stmt->fetch();

    if(!empty($student)) {
        session_start();
        $_SESSION['student'] = $student;
        header('location: ../../usuario');
    } else {
        session_start();
        $_SESSION['msg'] = "Usuário/Senha incorreto";
        header('location: ../../');
        exit;
    }
}

?>