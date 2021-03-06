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

    public static function course_question_csv($id) {
        self::check_logged_in();
        $course = Course::find($id);
        $exercises = Exercise::all($id);
        $final_array = array();
        foreach ($exercises as $exercise) {
            $array = Answer::exercise_table($exercise->id);
            $final_array = array_merge($final_array, $array);
        }
        try {
            $file = fopen('php://temp', 'w');
            foreach ($final_array as $row) {
                fputcsv($file, $row, ',');
            }
            fseek($file, 0);
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $course->name . '_' . $course->term . '_questions.csv' . '";');
            fpassthru($file);
        } catch (Exception $e) {
            Kint::dump($final_array);
        }
    }

    public static function course_summary_csv($id) {
        self::check_logged_in();
        $course = Course::find($id);
        $students = Student::all($id);
        $total_non_star = $course->total_problems;
        $total_star = $course->total_star_problems;
        $array = array();
        $array[0][0] = 'Opiskelijanumero';
        $array[0][1] = 'Kurssitunnus';
        $array[0][2] = 'Tehdyt tähdettömät yhteensä';
        $array[0][3] = 'Tehdyt tähdelliset yhteensä';
        $array[0][4] = 'Prosentit tähdettömistä';
        $array[0][5] = 'Prosentit tähdellisistä';
        $i = 1;
        foreach ($students as $student) {
            $array[$i][0] = $student->student_number;
            $array[$i][1] = $student->course_number;
            $array[$i][2] = Student::count_correct_non_star_exercises_by_student($id, $student->id);
            $array[$i][3] = Student::count_correct_star_exercises_by_student($id, $student->id);
            if ($total_non_star != 0) {
                $array[$i][4] = round(100*($array[$i][2]/$total_non_star));
            } else {
                $array[$i][4] = -1;
            }
            if ($total_star != 0) {
                $array[$i][5] = round(100*($array[$i][3]/$total_star));
            } else {
                $array[$i][5] = -1;
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
            header('Content-Disposition: attachment; filename="' . $course->name . '.csv' . '";');
            fpassthru($file);
        } catch (Exception $e) {
            Kint::dump($array);
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
