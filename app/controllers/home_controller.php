<?php

class HomeController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function score_show() {
        View::make('scores/show.html');
    }

    public static function score_query() {
        $params = $_POST;
        $student = Student::find_by_course_number($params['course_number']);
        if ($student) {
            $exercises = Exercise::all($student->course_id);
            $returns = ProblemReturn::all($student->id);
            $points = array();
            for ($i = 1; $i <= count($exercises); $i++) {
                $points[$i] = 0;
            }
            foreach ($returns as $return) {
                $problem = Problem::find($return->problem_id);
                $exercise = Exercise::find($problem->exercise_id);
                if ($return->mark == 'O') {
                    $points[$exercise->exercise_number] ++;
                }
            }
            View::make('scores/show.html', array('points' => $points, 'course' => Course::find($student->course_id)));
        } else {
            View::make('home.html', array('error' => 'Kyseistä kurssitunnusta ei löydy!'));
        }
    }

}
