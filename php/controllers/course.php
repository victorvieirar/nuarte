<?php

function getCourses($conn) {
    $sql = "SELECT * FROM courses";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
}

function getCoursesTagged($conn) {
    $courses = getCourses($conn);

    $coursesTagged = array();
    foreach($courses as $course) {
        $coursesTagged[$course['id']] = $course['name'];
    }

    return $coursesTagged;
}

?>