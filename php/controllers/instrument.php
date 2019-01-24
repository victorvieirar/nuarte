<?php

function getInstruments($conn) {
    $sql = "SELECT * FROM instruments";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
}

?>