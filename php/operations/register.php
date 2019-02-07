<?php 

require_once '../config/database.php';

if(isset($_POST['register'])) {
    $enrollment = isset($_POST['enrollment']) ? $_POST['enrollment'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $course = isset($_POST['course']) ? $_POST['course'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if(empty($enrollment) || empty($name) || empty($email) || empty($course) || empty($password)) {
        session_start();
        $_SESSION['msg'] = "Dados não preenchidos corretamente";
        header('location: ../../');
        exit;
    }

    $sql = "INSERT INTO students VALUES ";
    $sql .= "(:enrollment, :name, :email, :course, :password, default)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":enrollment", $enrollment);
    $stmt->bindValue(":name", $name);
    $stmt->bindValue(":email", $email);
    $stmt->bindValue(":course", $course);
    $stmt->bindValue(":password", $password);
    $success = $stmt->execute();

    if($success) {
        session_start();
        $student = array('enrollment' => $enrollment, 'name' => $name, 'email' => $email, 'course' => $couse, 'password' => $password); 
        $_SESSION['student'] = $student;
        header('location: ../../usuario');
    } else {
        session_start();
        $_SESSION['msg'] = "Erro ao cadastrar. Usuário já cadastrado, tente novamente.";
        header('location: ../../');
        exit;
    }
}

?>