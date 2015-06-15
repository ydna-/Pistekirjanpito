<?php

class ExerciseController extends BaseController {

    public static function exercise_show($course_id, $id) {
        self::check_logged_in();
        $exercise = Exercise::find($id);
        $course = Course::find($course_id);
        View::make('exercise/show.html', array('exercise' => $exercise, 'course' => $course));
    }

    public static function exercise_store($course_id) {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'exercise_number' => $params['exercise_number'],
            'number_of_exercises' => $params['number_of_exercises'],
            'number_of_star_exercises' => count($params['star_exercises']),
            'course_id' => $course_id
        );
        $number_of_exercises = $params['number_of_exercises'];
        $star_exercises = $params['star_exercises'];
        $exercise = new Exercise($attributes);
        $course = Course::find($course_id);
        $errors = $exercise->errors();
        if (count($errors) == 0) {
            $exercise->save();
            for ($i = 1; $i <= $number_of_exercises; $i++) {
                $problem = new Problem(array(
                    'problem_number' => $i,
                    'exercise_id' => $exercise->id
                ));
                $problem->save();
            }
            for ($i = 0; $i < count($star_exercises); $i++) {
                $problem = new Problem(array(
                    'problem_number' => $star_exercises[$i] . 'k1',
                    'exercise_id' => $exercise->id
                ));
                $problem->save();
                $problem = new Problem(array(
                    'problem_number' => $star_exercises[$i] . 'k2',
                    'exercise_id' => $exercise->id
                ));
                $problem->save();
            }
            Redirect::to('/courses/' . $course_id . '/exercises/' . $exercise->id, array('message' => 'Harjoitus on lisätty tietokantaan!'));
        } else {
            View::make('exercise/new.html', array('errors' => $errors, 'attributes' => $attributes, 'course' => $course));
        }
    }

    public static function exercise_create($course_id) {
        self::check_logged_in();
        $course = Course::find($course_id);
        View::make('exercise/new.html', array('course' => $course));
    }

    public static function exercise_edit($course_id, $id) {
        self::check_logged_in();
        $exercise = Exercise::find($id);
        $course = Course::find($course_id);
        View::make('exercise/edit.html', array('attributes' => $exercise, 'course' => $course));
    }

    public static function exercise_update($course_id, $id) {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'exercise_number' => $params['exercise_number'],
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
        self::check_logged_in();
        $exercise = new Exercise(array('id' => $id));
        $exercise->destroy();
        Redirect::to('/courses/' . $course_id, array('message' => 'Harjoitus on poistettu tietokannasta!'));
    }

}
