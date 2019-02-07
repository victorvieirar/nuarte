<?php

function getReservations($conn) {
    $sql = "SELECT * FROM reservations";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
}

function getCountInstrumentReservations($conn, $instrument) {
    $sql = "SELECT count(*) as 'reserves' FROM reservations WHERE instrument = ".$instrument['reference'];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetch();
}

function getInstrumentReservations($conn, $instrument) {
    $sql = "SELECT * FROM reservations WHERE instrument = ".$instrument['reference']." AND status = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
}

function getFirstInstrumentReservation($conn, $instrument) {
    $sql = "SELECT * FROM reservations WHERE instrument = ".$instrument['reference'];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetch();
}

function getStudentReservations($conn, $student) {
    $sql = "SELECT * FROM reservations WHERE studentEnrollment = ".$student['enrollment'];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
}

?>