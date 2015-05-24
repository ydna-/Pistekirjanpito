<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function sandbox() {
        View::make('helloworld.html');
    }
    
    public static function login() {
        View::make('suunnitelmat/login.html');
    }
    
    public static function register() {
        View::make('suunnitelmat/register.html');
    }

    public static function course_add() {
        View::make('suunnitelmat/course_add.html');
    }
    
    public static function course_edit() {
        View::make('suunnitelmat/course_edit.html');
    }

    public static function course_list() {
        View::make('suunnitelmat/course_list.html');
    }

    public static function course_show() {
        View::make('suunnitelmat/course_show.html');
    }
    
    public static function exercise_add() {
        View::make('suunnitelmat/exercise_add.html');
    }
    
    public static function exercise_edit() {
        View::make('suunnitelmat/exercise_edit.html');
    }
    
    public static function exercise_show() {
        View::make('suunnitelmat/exercise_show.html');
    }

}
