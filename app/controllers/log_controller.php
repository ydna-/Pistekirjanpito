<?php

class LogController extends BaseController {

    public static function log_create($course_id, $exercise_id) {
        self::check_logged_in();
        $course = Course::find($course_id);
        $exercise = Exercise::find($exercise_id);
        $problems = Problem::all($exercise_id);
        $students = Student::all($course_id);
        View::make('log/new.html', array('course' => $course, 'exercise' => $exercise, 'problems' => $problems, 'students' => $students));
    }

    public static function log_store($course_id, $exercise_id) {
        self::check_logged_in();
        $params = $_POST;
        $problems = Problem::all($exercise_id);
        foreach ($problems as $problem) {
            if (strlen($problem->problem_number) < 3) {
                if (array_key_exists($problem->problem_number, $params)) {
                    $mark = strtoupper($params[$problem->problem_number]);
                    $mark = $mark[0];
                } else {
                    $mark = ' ';
                }
                $attributes = array(
                    'mark' => $mark,
                    'problem_id' => $problem->id,
                    'student_id' => $params['course_number']
                );
                $problemreturn = new ProblemReturn($attributes);
                $problemreturn->save();
            }
        }
        Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log', array('message' => 'Pisteet kirjattu tietokantaan!'));
    }

}
