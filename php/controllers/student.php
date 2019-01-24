<?php

function getStudents($conn) {
    $sql = "SELECT * FROM students";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
}

?>