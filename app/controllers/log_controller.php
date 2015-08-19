<?php

class LogController extends BaseController {

    public static function log_create($course_id, $exercise_id) {
        self::check_logged_in();
        $course = Course::find($course_id);
        $exercise = Exercise::find($exercise_id);
        $problems = Problem::all($exercise_id);
        $questions = Question::all($exercise_id);
        $students = Student::all($course_id);
        $returns = ProblemReturn::all_by_exercise($exercise_id);
        $answers = Answer::all_by_exercise($exercise_id);
        View::make('log/new.html', array('course' => $course, 'exercise' => $exercise, 'problems' => $problems, 'questions' => $questions, 'students' => $students, 'returns' => $returns, 'answers' => $answers, 'option' => ''));
    }

    public static function log_first_create($course_id, $exercise_id) {
        self::check_logged_in();
        $course = Course::find($course_id);
        $exercise = Exercise::find($exercise_id);
        $problems = Problem::all_first($exercise_id);
        $students = Student::all($course_id);
        $returns = ProblemReturn::all_first_by_exercise($exercise_id);
        View::make('log/new.html', array('course' => $course, 'exercise' => $exercise, 'problems' => $problems, 'students' => $students, 'returns' => $returns, 'option' => '/first'));
    }

    public static function log_second_create($course_id, $exercise_id) {
        self::check_logged_in();
        $course = Course::find($course_id);
        $exercise = Exercise::find($exercise_id);
        $problems = Problem::all_second($exercise_id);
        $students = Student::all($course_id);
        $returns = ProblemReturn::all_second_by_exercise($exercise_id);
        View::make('log/new.html', array('course' => $course, 'exercise' => $exercise, 'problems' => $problems, 'students' => $students, 'returns' => $returns, 'option' => '/second'));
    }

    public static function log_store($course_id, $exercise_id) {
        self::check_logged_in();
        $params = $_POST;
        if ($params['course_number'] !== "") {
            $problems = Problem::all($exercise_id);
            foreach ($problems as $problem) {
                if (array_key_exists($problem->id, $params)) {
                    $mark = $params[$problem->id][0];
                } else {
                    $mark = ' ';
                }
                $attributes = array(
                    'mark' => $mark,
                    'problem_id' => $problem->id,
                    'student_id' => $params['course_number']
                );
                $problemreturn = new ProblemReturn($attributes);
                if (ProblemReturn::find($problem->id, $params['course_number'])) {
                    $problemreturn->update();
                } else {
                    $problemreturn->save();
                }
            }
            $questions = Question::all($exercise_id);
            foreach ($questions as $question) {
                if (array_key_exists('q' . $question->id, $params)) {
                    $mark = $params['q' . $question->id][0];
                } else {
                    $mark = ' ';
                }
                $attributes = array(
                    'mark' => $mark,
                    'question_id' => $question->id,
                    'student_id' => $params['course_number']
                );
                $answer = new Answer($attributes);
                if (Answer::find($question->id, $params['course_number'])) {
                    $answer->update();
                } else {
                    $answer->save();
                }
            }
            Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log', array('message' => 'Pisteet kirjattu tietokantaan!'));
        } else {
            Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log', array('error' => 'Kurssitunnusta ei löydy!'));
        }
    }

    public static function log_first_store($course_id, $exercise_id) {
        self::check_logged_in();
        $params = $_POST;
        if ($params['course_number'] !== "") {
            $problems = Problem::all_first($exercise_id);
            foreach ($problems as $problem) {
                $number = substr($problem->problem_number, 0, -2);
                $previous = ProblemReturn::find_by_number($number, $exercise_id, $params['course_number']);
                if ($previous && $previous->mark == 'O') {
                    Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log/first', array('error' => 'Opiskelijalle on jo kirjattu tehtävä ' . $number . ' oikein!'));
                    return;
                }
            }
            foreach ($problems as $problem) {
                if (array_key_exists($problem->id, $params)) {
                    $mark = $params[$problem->id][0];
                } else {
                    $mark = ' ';
                }
                $attributes = array(
                    'mark' => $mark,
                    'problem_id' => $problem->id,
                    'student_id' => $params['course_number']
                );
                $problemreturn = new ProblemReturn($attributes);
                if (ProblemReturn::find($problem->id, $params['course_number'])) {
                    $problemreturn->update();
                } else {
                    $problemreturn->save();
                }
            }
            Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log/first', array('message' => 'Pisteet kirjattu tietokantaan!'));
        } else {
            Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log/first', array('error' => 'Kurssitunnusta ei löydy!'));
        }
    }

    public static function log_second_store($course_id, $exercise_id) {
        self::check_logged_in();
        $params = $_POST;
        if ($params['course_number'] !== "") {
            $problems = Problem::all_second($exercise_id);
            foreach ($problems as $problem) {
                $number = substr($problem->problem_number, 0, -2);
                $previous = ProblemReturn::find_by_number($number, $exercise_id, $params['course_number']);
                if ($previous && $previous->mark == 'O') {
                    Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log/second', array('error' => 'Opiskelijalle on jo kirjattu tehtävä ' . $number . ' oikein!'));
                    return;
                }
                $number = $number . "k1";
                $previous = ProblemReturn::find_by_number($number, $exercise_id, $params['course_number']);
                if ($previous && $previous->mark == 'O') {
                    Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log/second', array('error' => 'Opiskelijalle on jo kirjattu tehtävä ' . $number . ' oikein!'));
                    return;
                }
            }
            foreach ($problems as $problem) {
                if (array_key_exists($problem->id, $params)) {
                    $mark = $params[$problem->id][0];
                } else {
                    $mark = ' ';
                }
                $attributes = array(
                    'mark' => $mark,
                    'problem_id' => $problem->id,
                    'student_id' => $params['course_number']
                );
                $problemreturn = new ProblemReturn($attributes);
                if (ProblemReturn::find($problem->id, $params['course_number'])) {
                    $problemreturn->update();
                } else {
                    $problemreturn->save();
                }
            }
            Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log/second', array('message' => 'Pisteet kirjattu tietokantaan!'));
        } else {
            Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise_id . '/log/second', array('error' => 'Kurssitunnusta ei löydy!'));
        }
    }

}
