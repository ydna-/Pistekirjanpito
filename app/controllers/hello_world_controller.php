<?php

class HelloWorldController extends BaseController {

    public static function index() {
        $courses = Course::all();
        View::make('home.html', array('courses' => $courses));
    }

    public static function sandbox() {
        $course = Course::find(1);
        $courses = Course::all();
        Kint::dump($course);
        Kint::dump($courses);
    }
    
    public static function login() {
        View::make('user/login.html');
    }
    
    public static function register() {
        View::make('user/register.html');
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
