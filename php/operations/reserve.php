<?php 

require_once '../config/database.php';
require_once '../controllers/reservation.php';

$DAYS_OFFSET = 10;

session_start();
if(!isset($_SESSION['student'])) {
    header('location: ../../usuario');
}

if(isset($_POST['reserve'])) {
    $instrument = isset($_POST['instrument']) ? $_POST['instrument'] : '';
    $reservationDate = isset($_POST['reservationDate']) ? $_POST['reservationDate'] : '';
    $studentEnrollment = isset($_POST['studentEnrollment']) ? $_POST['studentEnrollment'] : '';

    $reservationDate = DateTime::createFromFormat('d-m-Y', $reservationDate);
    $reservationDate = $reservationDate->format("Y-m-d");

    if(empty($instrument) || empty($reservationDate) || empty($studentEnrollment)) {
        $_SESSION['msg'] = "Dados não preenchidos corretamente";
        header('location: ../../usuario');
        exit;
    }

    $reservationDateDT = new DateTime($reservationDate);
    $reservationEnd = $reservationDateDT->modify("+".$DAYS_OFFSET." days");

    $conflict = FALSE;
    $conflictStart = NULL;

    $reservations = getInstrumentReservations($conn, array('reference' => $instrument));
    foreach($reservations as $reservation) {
        if($reservation['reservationDate'] <= $reservationEnd) {
            $conflict = TRUE;
            $conflictStart = $reservation['reservationDate'];
        }
    }

    if($conflict) {
        $start = new DateTime($reservationDate);
        $end = new DateTime($conflictStart);
        $end = $end->modify('-1 days');
        $interval = $start->diff($end);
        $offset = $interval->format('%R%a days');
        $reservationEnd = $start->modify(offset);
    }

    $sql = "INSERT INTO reservations VALUES ";
    $sql .= "(:reservationDate, :reservationEnd, :instrument, :studentEnrollment)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":reservationDate", $reservationDate);
    $stmt->bindValue(":reservationEnd", $reservationEnd->format('Y-m-d H:i:s'));
    $stmt->bindValue(":instrument", $instrument);
    $stmt->bindValue(":studentEnrollment", $studentEnrollment);

    try {
        $success = $stmt->execute();
    } catch(PDOException $pdoe) {
        switch($pdoe->getCode()) {
            case 23000:
                $_SESSION['msg'] = "Você já reservou esse instrumento.";
                header('location: ../../usuario');
                exit;
            default:
                $_SESSION['msg'] = "Erro desconhecido ao tentar reservar instrumento. Tente novamente.";
                header('location: ../../usuario');
                exit;
        }
    }

    if($success) {
        $_SESSION['msg'] = "Reserva feita com sucesso!";
        header('location: ../../usuario');
    } else {
        $_SESSION['msg'] = "Erro ao realizar reserva, tente novamente.";
        header('location: ../../usuario');
        exit;
    }
}

?>