<?php

class CourseController extends BaseController {

    public static function course_list() {
        self::check_logged_in();
        $courses = Course::all();
        foreach ($courses as $course) {
            $course->number_of_students = Student::count($course->id);
            $course->number_of_returned = Student::count_returned($course->id);
        }
        View::make('course/list.html', array('courses' => $courses));
    }

    public static function course_show($id) {
        self::check_logged_in();
        $course = Course::find($id);
        $course->number_of_students = Student::count($id);
        $course->number_of_returned = Student::count_returned($id);
        $exercises = Exercise::all($id);
        foreach ($exercises as $exercise) {
            $exercise->number_of_returned = Student::count_returned_by_exercise($course->id, $exercise->id);
        }
        View::make('course/show.html', array('course' => $course, 'exercises' => $exercises));
    }

    public static function course_store() {
        self::check_is_teacher();
        $params = $_POST;
        $attributes = array(
            'name' => $params['name'],
            'term' => $params['term'],
            'total_problems' => $params['total_problems'],
            'total_star_problems' => $params['total_star_problems']
        );
        $course = new Course($attributes);
        $errors = $course->errors();
        if (count($errors) == 0) {
            $course->save();
            Redirect::to('/courses/' . $course->id, array('message' => 'Kurssi on lisätty tietokantaan!'));
        } else {
            View::make('course/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function course_create() {
        self::check_is_teacher();
        View::make('course/new.html');
    }

    public static function course_edit($id) {
        self::check_is_teacher();
        $course = Course::find($id);
        View::make('course/edit.html', array('attributes' => $course));
    }

    public static function course_update($id) {
        self::check_is_teacher();
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'term' => $params['term'],
            'total_problems' => $params['total_problems'],
            'total_star_problems' => $params['total_star_problems']
        );
        $course = new Course($attributes);
        $errors = $course->errors();
        if (count($errors) == 0) {
            $course->update();
            Redirect::to('/courses/' . $course->id, array('message' => 'Kurssin tiedot on päivitetty!'));
        } else {
            View::make('course/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function course_destroy($id) {
        self::check_is_teacher();
        $course = new Course(array('id' => $id));
        $course->destroy();
        Redirect::to('/courses', array('message' => 'Kurssi on poistettu tietokannasta!'));
    }

    public static function course_csv($id) {
        self::check_logged_in();
        $course = Course::find($id);
        $exercises = Exercise::all($id);
        $final_array = array();
        foreach ($exercises as $exercise) {
            $array = ProblemReturn::exercise_table($exercise->id);
            $final_array = array_merge($final_array, $array);
        }
        try {
            $file = fopen('php://temp', 'w');
            foreach ($final_array as $row) {
                fputcsv($file, $row, ',');
            }
            fseek($file, 0);
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $course->name . '_' . $course->term . '.csv' . '";');
            fpassthru($file);
        } catch (Exception $e) {
            Kint::dump($final_array);
        }
    }

    /*
    public static function course_csv($id) {
        $students = Student::all($id);
        $exercises = Exercise::all($id);
        $array = array();
        $array[0][0] = 'tunnus';
        $i = 1;
        foreach ($exercises as $exercise) {
            $array[0][$i] = $exercise->exercise_number;
            $array[0][$i + 1] = $exercise->exercise_number;
            $i = $i + 2;
        }
        $i = 1;
        foreach ($students as $student) {
            $array[$i][0] = $student->course_number;
            $j = 1;
            foreach ($exercises as $exercise) {
                $problems = Problem::all($exercise->id);
                $array[$i][$j] = 0;
                $array[$i][$j + 1] = 0;
                foreach ($problems as $problem) {
                    $return = ProblemReturn::find($problem->id, $student->id);
                    if (!$problem->star_problem) {
                        if ($return && $return->mark == 'O') {
                            $array[$i][$j]++;
                        }
                    } else {
                        if ($return && $return->mark == 'O') {
                            $array[$i][$j + 1]++;
                        }
                    }
                }
                $j = $j + 2;
            }
            $i++;
        }
        try {
            $file = fopen('php://temp', 'w');
            foreach ($array as $row) {
                fputcsv($file, $row, ',');
            }
            fseek($file, 0);
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $id . '.csv' . '";');
            fpassthru($file);
        } catch (Exception $e) {
            Kint::dump($array);
        }
    }
    */
}
