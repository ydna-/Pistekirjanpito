<?php

class HomeController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function score_query() {
        $params = $_POST;
        $student = Student::find_by_course_number($params['course_number']);
        if ($student) {
            $exercises = Exercise::all($student->course_id);
            $returns = ProblemReturn::all_by_student($student->id);
            $points = array();
            $marks = array();
            $i = 1;
            foreach ($exercises as $exercise) {
                $points[$i]['total_points'] = 0;
                $points[$i]['number_of_problems'] = $exercise->number_of_problems;
                $marks[$exercise->exercise_number] = array();
                $problems = Problem::all($exercise->id);
                $problems = array_merge($problems, Problem::all_first($exercise->id));
                $problems = array_merge($problems, Problem::all_second($exercise->id));
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
                    $marks[$exercise->exercise_number][$problem->problem_number] = null;
                }
                $i++;
            }
            foreach ($returns as $return) {
                $problem = Problem::find($return->problem_id);
                $exercise = Exercise::find($problem->exercise_id);
                if ($return->mark == 'O') {
                    $points[$exercise->exercise_number]['total_points']++;
                }
                $marks[$exercise->exercise_number][$problem->problem_number] = $return->mark;
            }
            View::make('score/list.html', array('points' => $points, 'marks' => $marks, 'course' => Course::find($student->course_id)));
        } else {
            View::make('home.html', array('error' => 'Kyseistä kurssitunnusta ei löydy!'));
        }
    }

}
