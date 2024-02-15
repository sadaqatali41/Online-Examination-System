<?php

class Dashboard {

    public static function totalCenters($conn) {
        $stmt = $conn->prepare("SELECT c.id FROM centers c WHERE 1");
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res->num_rows;
    }

    public static function totalCourseCategories($conn) {
        $stmt = $conn->prepare("SELECT c.id FROM course_category c WHERE 1");
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res->num_rows;
    }

    public static function totalCourses($conn) {
        $stmt = $conn->prepare("SELECT c.id FROM courses c WHERE 1");
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res->num_rows;
    }

    public static function totalQuestions($conn) {
        $stmt = $conn->prepare("SELECT c.id FROM questions c WHERE 1");
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res->num_rows;
    }

    public static function totalEligibilityCriteria($conn) {
        $stmt = $conn->prepare("SELECT c.id FROM eligibility_criteria c WHERE 1");
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res->num_rows;
    }

    public static function totalExamSchedule($conn) {
        $stmt = $conn->prepare("SELECT c.id FROM exam_schedule c WHERE 1");
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res->num_rows;
    }

    public static function totalStudents($conn) {
        $stmt = $conn->prepare("SELECT c.id FROM students c WHERE 1");
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res->num_rows;
    }
}