<?php

class ExerciseController extends BaseController {

    public static function exercise_show($id) {
        $exercise = Exercise::find($id);
        View::make('exercise/exercise_show.html', array('exercise' => $exercise));
    }
    
}