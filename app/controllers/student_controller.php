<?php

class StudentController extends BaseController {

    public static function student_list($course_id) {
        self::check_logged_in();
        $students = Student::all($course_id);
        $course = Course::find($course_id);
        View::make('student/list.html', array('students' => $students, 'course' => $course));
    }

    public static function student_store($course_id) {
        self::check_logged_in();
        $fh = fopen($_FILES['file']['tmp_name'], 'r+');
        $students = array();
        while (($row = fgetcsv($fh, 8192, ",")) !== FALSE) {
            $attributes = array(
                'student_number' => $row[0],
                'course_number' => $row[1],
                'course_id' => $course_id
            );
            $student = new Student($attributes);
            $errors = $student->errors();
            if (count($errors) == 0) {
                $student->save();
            } else {
                View::make('student/new.html', array('errors' => $errors, 'course' => Course::find($course_id)));
            }
        }
        Redirect::to('/courses/' . $course_id . '/students', array('message' => 'Opiskelijat on lisätty tietokantaan!'));
    }

    public static function student_create($course_id) {
        self::check_logged_in();
        $course = Course::find($course_id);
        View::make('student/new.html', array('course' => $course));
    }

}
