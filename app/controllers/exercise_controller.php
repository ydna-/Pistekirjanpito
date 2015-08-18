<?php

class ExerciseController extends BaseController {

    public static function exercise_show($course_id, $id) {
        self::check_logged_in();
        $exercise = Exercise::find($id);
        $course = Course::find($course_id);
        $problems = Problem::all_plus_star($id);
        usort($problems, function($a, $b) {
            if ($a->problem_number - $b->problem_number == 0) {
                if (strlen($a->problem_number) == strlen($b->problem_number)) {
                    return substr($a->problem_number, -1) - substr($b->problem_number, -1);
                } else {
                    return strlen($a->problem_number) - strlen($b->problem_number);
                }
            }
            return $a->problem_number - $b->problem_number;
        });
        foreach ($problems as $problem) {
            $problem->number_of_returned = Student::count_returned_by_problem($course->id, $exercise->id, $problem->id);
        }
        View::make('exercise/show.html', array('exercise' => $exercise, 'course' => $course, 'problems' => $problems));
    }

    public static function exercise_store($course_id) {
        self::check_logged_in();
        $params = $_POST;
        if (array_key_exists('star_exercises', $params)) {
            $attributes = array(
                'exercise_number' => $params['exercise_number'],
                'number_of_problems' => $params['number_of_problems'],
                'number_of_star_problems' => count($params['star_exercises']),
                'max_non_star_score' => $params['max_non_star_score'],
                'max_star_score' => $params['max_star_score'],
                'course_id' => $course_id
            );
            $number_of_problems = $params['number_of_problems'];
            $star_exercises = $params['star_exercises'];
            $exercise = new Exercise($attributes);
            $course = Course::find($course_id);
            $errors = $exercise->errors();
            if (count($errors) == 0) {
                $exercise->save();
                for ($i = 1; $i <= $number_of_problems; $i++) {
                    if (in_array($i, $star_exercises)) {
                        $problem = new Problem(array(
                            'problem_number' => $i,
                            'star_problem' => 1,
                            'exercise_id' => $exercise->id
                        ));
                        $problem->save();
                        $problem = new Problem(array(
                            'problem_number' => $i . 'k1',
                            'star_problem' => 1,
                            'exercise_id' => $exercise->id
                        ));
                        $problem->save();
                        $problem = new Problem(array(
                            'problem_number' => $i . 'k2',
                            'star_problem' => 1,
                            'exercise_id' => $exercise->id
                        ));
                        $problem->save();
                    } else {
                        $problem = new Problem(array(
                            'problem_number' => $i,
                            'star_problem' => 0,
                            'exercise_id' => $exercise->id
                        ));
                        $problem->save();
                    }
                }
                Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise->id, array('message' => 'Harjoitus on lisätty tietokantaan!'));
            } else {
                View::make('exercise/new.html', array('errors' => $errors, 'attributes' => $attributes, 'course' => $course));
            }
        } else {
            $attributes = array(
                'exercise_number' => $params['exercise_number'],
                'number_of_problems' => $params['number_of_problems'],
                'number_of_star_problems' => 0,
                'max_non_star_score' => $params['max_non_star_score'],
                'max_star_score' => 0,
                'course_id' => $course_id
            );
            $number_of_problems = $params['number_of_problems'];
            $exercise = new Exercise($attributes);
            $course = Course::find($course_id);
            $errors = $exercise->errors();
            if (count($errors) == 0) {
                $exercise->save();
                for ($i = 1; $i <= $number_of_problems; $i++) {
                    $problem = new Problem(array(
                        'problem_number' => $i,
                        'star_problem' => 0,
                        'exercise_id' => $exercise->id
                    ));
                    $problem->save();
                }
                Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise->id, array('message' => 'Harjoitus on lisätty tietokantaan!'));
            } else {
                View::make('exercise/new.html', array('errors' => $errors, 'attributes' => $attributes, 'course' => $course));
            }
        }
    }

    public static function exercise_create($course_id) {
        self::check_logged_in();
        $course = Course::find($course_id);
        View::make('exercise/new.html', array('course' => $course));
    }

    public static function exercise_edit($course_id, $id) {
        self::check_is_teacher();
        $exercise = Exercise::find($id);
        $course = Course::find($course_id);
        View::make('exercise/edit.html', array('attributes' => $exercise, 'course' => $course));
    }

    public static function exercise_update($course_id, $id) {
        self::check_is_teacher();
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'exercise_number' => $params['exercise_number'],
            'max_non_star_score' => $params['max_non_star_score'],
            'max_star_score' => $params['max_star_score'],
            'course_id' => $course_id
        );
        $exercise = new Exercise($attributes);
        $errors = $exercise->errors();
        if (count($errors) == 0) {
            $exercise->update();
            Redirect::to('/courses/' . $exercise->course_id . '/exercises/' . $exercise->id, array('message' => 'Harjoituksen tiedot on päivitetty!'));
        } else {
            View::make('exercise/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function exercise_destroy($course_id, $id) {
        self::check_is_teacher();
        $exercise = new Exercise(array('id' => $id));
        $exercise->destroy();
        Redirect::to('/courses/' . $course_id, array('message' => 'Harjoitus on poistettu tietokannasta!'));
    }

    public static function exercise_csv($course_id, $id) {
        $students = Student::all($course_id);
        $problems = Problem::all_plus_star($id);
        usort($problems, function($a, $b) {
            if ($a->problem_number - $b->problem_number == 0) {
                if (strlen($a->problem_number) == strlen($b->problem_number)) {
                    return substr($a->problem_number, -1) - substr($b->problem_number, -1);
                } else {
                    return strlen($a->problem_number) - strlen($b->problem_number);
                }
            }
            return $a->problem_number - $b->problem_number;
        });
        $array = array();
        $array[0][0] = 'tunnus';
        $i = 1;
        foreach ($problems as $problem) {
            $array[0][$i] = $problem->problem_number;
            $i++;
        }
        $i = 1;
        foreach ($students as $student) {
            $array[$i][0] = $student->course_number;
            $j = 1;
            foreach ($problems as $problem) {
                $return = ProblemReturn::find($problem->id, $student->id);
                if ($return) {
                    $array[$i][$j] = $return->mark;
                } else {
                    $array[$i][$j] = ' ';
                }
                $j++;
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

}
