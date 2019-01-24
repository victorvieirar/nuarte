<?php 

require_once '../config/database.php';

session_start();
if(!isset($_SESSION['student']) && !isset($_SESSION['admin'])) {
    echo json_encode(array('success' => false));
    exit;
}

if(isset($_POST['cancel'])) {
    $studentEnrollment = isset($_POST['studentEnrollment']) ? $_POST['studentEnrollment'] : '';
    $reservationDate = isset($_POST['reservationDate']) ? $_POST['reservationDate'] : '';
    $instrument = isset($_POST['instrument']) ? $_POST['instrument'] : '';

    if(empty($studentEnrollment) || empty($reservationDate) || empty($instrument)) {
        echo json_encode(array('success' => false));
        exit;
    }

    $sql = "DELETE FROM reservations WHERE ";
    $sql .= "studentEnrollment = :studentEnrollment AND reservationDate = :reservationDate AND instrument = :instrument";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":studentEnrollment", $studentEnrollment);
    $stmt->bindValue(":reservationDate", $reservationDate);
    $stmt->bindValue(":instrument", $instrument);

    $success = $stmt->execute();

    if($success) {
        echo json_encode(array('success' => true));
        exit;
    } else {
        echo json_encode(array('success' => true));
        exit;
    }
}

?>