<?php 

require_once '../config/database.php';

session_start();
if(!isset($_SESSION['student'])) {
    header('location: ../../usuario');
}

if(isset($_POST['update'])) {
    $enrollment = isset($_POST['enrollment']) ? $_POST['enrollment'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $course = isset($_POST['course']) ? $_POST['course'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if(empty($enrollment) || empty($name) || empty($email) || empty($course) || empty($password)) {
        $_SESSION['msg'] = "Dados não preenchidos corretamente";
        header('location: ../../');
        exit;
    }

    $sql = "UPDATE students SET ";
    $sql .= "name = :name, email = :email, course = :course, password = :password WHERE enrollment = :enrollment";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":name", $name);
    $stmt->bindValue(":email", $email);
    $stmt->bindValue(":course", $course);
    $stmt->bindValue(":password", $password);
    $stmt->bindValue(":enrollment", $enrollment);
    $success = $stmt->execute();

    if($success) {
        $student = array('enrollment' => $enrollment, 'name' => $name, 'email' => $email, 'course' => $course, 'password' => $password); 
        $_SESSION['student'] = $student;
        $_SESSION['msg'] = "Dados atualizados com sucesso.";
        header('location: ../../usuario');
    } else {
        $_SESSION['msg'] = "Erro ao atualizar dados, tente novamente.";
        header('location: ../../');
        exit;
    }
}

?>