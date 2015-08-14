<?php

class StudentController extends BaseController {

    public static function student_list($course_id) {
        self::check_logged_in();
        $students = Student::all($course_id);
        $course = Course::find($course_id);
        View::make('student/list.html', array('students' => $students, 'course' => $course));
    }
    
    public static function student_store_one($course_id) {
        self::check_is_teacher();
        $params = $_POST;
        $attributes = array(
            'student_number' => $params['student_number'],
            'course_number' => $params['course_number'],
            'course_id' => $course_id
        );
        $student = new Student($attributes);
        $errors = $student->errors();
        if (count($errors) == 0) {
            $student->save();
            Redirect::to('/courses/' . $course_id . '/students', array('message' => 'Opiskelija on lisätty tietokantaan!'));
        } else {
            View::make('student/new.html', array('errors' => $errors, 'student' => $student));
        }
    }

    public static function student_store($course_id) {
        self::check_is_teacher();
        try {
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
        } catch (Exception $e) {
            View::make('student/new.html', array('errors' => array('Virhe! ' . $e->getMessage()), 'course' => Course::find($course_id)));
        }
    }

    public static function student_create($course_id) {
        self::check_is_teacher();
        $course = Course::find($course_id);
        View::make('student/new.html', array('course' => $course));
    }

    public static function student_edit($course_id, $student_id) {
        self::check_is_teacher();
        $student = Student::find($student_id);
        $course = Course::find($course_id);
        View::make('student/edit.html', array('course' => $course, 'student' => $student));
    }

    public static function student_update($course_id, $student_id) {
        self::check_is_teacher();
        $params = $_POST;
        $attributes = array(
            'id' => $student_id,
            'student_number' => $params['student_number'],
            'course_number' => $params['course_number'],
            'course_id' => $course_id
        );
        $student = new Student($attributes);
        $errors = $student->errors();
        if (count($errors) == 0) {
            $student->update();
            Redirect::to('/courses/' . $course_id . '/students', array('message' => 'Opiskelijan tiedot on päivitetty!'));
        } else {
            View::make('student/edit.html', array('errors' => $errors, 'student' => $student));
        }
    }

    public static function student_destroy($course_id, $student_id) {
        self::check_is_teacher();
        $student = new Student(array('id' => $student_id));
        $student->destroy();
        Redirect::to('/courses/' . $course_id . '/students', array('message' => 'Opiskelija on poistettu tietokannasta!'));
    }

}
