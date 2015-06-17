<?php

class Student extends BaseModel {

    public $id, $student_number, $course_number, $course_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_student_number', 'validate_course_number');
    }
    
    public static function all($course_id) {
        $query = DB::connection()->prepare('SELECT * FROM Student WHERE course_id = :course_id');
        $query->execute(array('course_id' => $course_id));
        $rows = $query->fetchAll();
        $students = array();
        foreach ($rows as $row) {
            $students[] = new Student(array(
                'id' => $row['id'],
                'student_number' => $row['student_number'],
                'course_number' => $row['course_number'],
                'course_id' => $row['course_id']
            ));
        }
        return $students;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Student WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $student = new Student(array(
                'id' => $row['id'],
                'student_number' => $row['student_number'],
                'course_number' => $row['course_number'],
                'course_id' => $row['course_id']
            ));
            return $student;
        }
        return null;
    }
    
    public static function find_by_course_number($course_number) {
        $query = DB::connection()->prepare('SELECT * FROM STUDENT WHERE course_number = :course_number LIMIT 1');
        $query->execute(array('course_number' => $course_number));
        $row = $query->fetch();
        if ($row) {
            $student = new Student(array(
                'id' => $row['id'],
                'student_number' => $row['student_number'],
                'course_number' => $row['course_number'],
                'course_id' => $row['course_id']
            ));
            return $student;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Student (student_number, course_number, course_id) VALUES (:student_number, :course_number, :course_id) RETURNING id');
        $query->execute(array('student_number' => $this->student_number, 'course_number' => $this->course_number, 'course_id' => $this->course_id));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function validate_student_number() {
        $errors = array();
        if ($this->student_number == '' || $this->student_number == null) {
            $errors[] = 'Opiskelijanumero ei saa olla tyhjä!';
        }
        if (strlen($this->student_number) < 3) {
            $errors[] = 'Opiskelijanumeron pituuden tulee olla vähintään kolme merkkiä!';
        }
        return $errors;
    }

    public function validate_course_number() {
        $errors = array();
        if ($this->course_number == '' || $this->course_number == null) {
            $errors[] = 'Kurssitunnus ei saa olla tyhjä!';
        }
        if (strlen($this->course_number) < 3) {
            $errors[] = 'Kurssitunnuksen pituuden tulee olla vähintään kolme merkkiä!';
        }
        return $errors;
    }

}
