<?php

class Home2Controller extends BaseController {

    public static function index() {
        $courses = Course::all();
        View::make('home.html', array('courses' => $courses));
    }

}
