<?php

class HelloWorldController extends BaseController {

    public static function index() {
        $courses = Course::all();
        View::make('home.html', array('courses' => $courses));
    }

    public static function sandbox() {
        if (defined('CRYPT_SHA512') && CRYPT_SHA512) {
            echo('JEE!');
        } else {
            echo('Ou nou :(');
        }
    }

    public static function exercise_add() {
        View::make('exercise/add.html');
    }

    public static function exercise_edit() {
        View::make('exercise/edit.html');
    }

    public static function exercise_show() {
        View::make('exercise/show.html');
    }

}
