<?php 

require_once '../config/database.php';

session_start();
if(!isset($_SESSION['student']) && !isset($_SESSION['admin'])) {
    echo json_encode(array('success' => false));
    exit;
}

if(isset($_POST['find'])) {
    $instrument = isset($_POST['instrument']) ? $_POST['instrument'] : '';

    if(empty($instrument)) {
        echo json_encode(array('success' => false));
        exit;
    }

    $sql = "SELECT * FROM reservations WHERE ";
    $sql .= "instrument = :instrument ORDER BY reservationEnd ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":instrument", $instrument);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    $success = $stmt->execute();
    $result = $stmt->fetchAll();

    $today = new DateTime('now');
    if(!empty($result)) {
        $firstReservation = new DateTime($result[0]['reservationDate']);

        $availableDays = array();    
        while($today < $firstReservation) {
            $availableDays[] = $today->format("j-n-Y");
            $today = $today->modify("+1 days");
        }
    

        for($i = 0; $i < count($result)-1; $i++) {
            $reservation = $result[$i];
            $nextReservation = $result[$i+1];

            if(!empty($nextReservation)) {
                $startInterval = new DateTime($reservation['reservationEnd']);
                $endInterval = new DateTime($nextReservation['reservationDate']);

                $continue = TRUE;
                while($continue) {
                    $startInterval->modify("+1 days");
                    if($startInterval == $endInterval) {
                        $continue = FALSE;
                    } else {
                        $availableDays[] = $startInterval->format("j-n-Y");
                    }
                }
            }
        }
    }

    if(!empty($result)) {
        $endReservation = new DateTime(end($result)['reservationEnd']);
    } else {
        $endReservation = new DateTime("yesterday");
    }

    for($i = 0; $i < 30; $i++) {
        $endReservation->modify("+1 days");
        $availableDays[] = $endReservation->format("j-n-Y");
    }

    if($success) {
        echo json_encode(array('success' => true, 'availableDays' => $availableDays));
        exit;
    } else {
        echo json_encode(array('success' => true));
        exit;
    }
}

?>