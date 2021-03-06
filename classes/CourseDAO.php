<?php

class CourseDAO
{
    public function retrieveAll($school = "", $search = "")
    {
        $sql = "SELECT course, school, title, description, DATE_FORMAT(exam_date, '%Y%m%d') AS 'exam date', TIME_FORMAT(exam_start, '%k%i') AS 'exam start', TIME_FORMAT(exam_end, '%k%i') AS 'exam end' FROM courses";

        if ($school == "all") $school = "";

        if ($school or $search) {
            $sql .= " WHERE ";
        }

        if ($school) {
            $sql .= " school = :school";
        }

        if ($search) {
            if ($school) {
                $sql .= " AND ";
            }
            $search = "%" . $search . "%";
            $sql .= " title LIKE :search";
        }

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        
        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        if ($school) $query->bindParam(':school', $school, PDO::PARAM_STR);
        if ($search) $query->bindParam(':search', $search, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Returns my result set on success.
        return $result;
    }

    public function retrieveAllSchools()
    {
        $sql = "SELECT DISTINCT school FROM courses";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Returns my result set on success.
        return $result;
    }

    public function retrieveByCode($courseCode)
    {
        $sql = "SELECT course, school, title, description, DATE_FORMAT(exam_date, '%Y%m%d') AS 'exam date', TIME_FORMAT(exam_start, '%k%i') AS 'exam start', TIME_FORMAT(exam_end, '%k%i') AS 'exam end' FROM courses WHERE courses.course = :courseCode";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Returns my result set on success.
        return $result;
    }

    public function sectionsCount($courseCode)
    {
        $sql = "SELECT COUNT(section) AS 'sections_offered' FROM sections WHERE course = :courseCode";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Returns my result set on success.
        return $result;
    }

    public function searchPrerequisites($courseCode)
    {
        $sql = "SELECT prerequisite FROM prerequisites WHERE course = :courseCode";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

        $query->execute();

        $result = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($result, $row['prerequisite']);
        }

        // Returns my result set on success.
        return $result;
    }

    public function searchPrerequisitesOf($courseCode)
    {
        $sql = "SELECT course FROM prerequisites WHERE prerequisite = :courseCode";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

        $query->execute();

        $result = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($result, $row['course']);
        }

        // Returns my result set on success.
        return $result;
    }

    public function addCourses($course)
    {
        $sql = 'INSERT INTO courses (course,school,title,description,exam_date,exam_start,exam_end) VALUES (:course, :school, :title, :description, :exam_date, :exam_start, :exam_end)';

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);

        $query->bindParam(':course', $course->courseCode, PDO::PARAM_STR);
        $query->bindParam(':school', $course->school, PDO::PARAM_STR);
        $query->bindParam(':title', $course->title, PDO::PARAM_STR);
        $query->bindParam(':description', $course->description, PDO::PARAM_STR);
        $query->bindParam(':exam_date', $course->examDate, PDO::PARAM_STR);
        $query->bindParam(':exam_start', $course->examStart, PDO::PARAM_STR);
        $query->bindParam(':exam_end', $course->examEnd, PDO::PARAM_STR);


        $isAddOK = False;
        if ($query->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public function addCompletedCourses($user_id, $course)
    {
        $sql = 'INSERT INTO courses_completed (user_id,course) VALUES (:user_id, :course)';

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);

        $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $query->bindParam(':course', $course, PDO::PARAM_STR);

        $isAddOK = False;
        if ($query->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public function addPrerequisites($course, $prerequisite)
    {
        $sql = 'INSERT INTO prerequisites (course,prerequisite) VALUES (:course, :prerequisite)';

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);

        $query->bindParam(':course', $course, PDO::PARAM_STR);
        $query->bindParam(':prerequisite', $prerequisite, PDO::PARAM_STR);
        $isAddOK = False;
        if ($query->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }


}