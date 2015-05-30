<?php

class CourseController extends BaseController {
    
    public static function course_list() {
        $courses = Course::all();
        View::make('course/list.html', array('courses' => $courses));
    }
    
    public static function course_show($id) {
        $course = Course::find($id);
        View::make('course/show.html', array('course' => $course));
    }
    
    public static function course_store() {
        $params = $_POST;
        $course = new Course(array(
            'name' => $params['name'],
            'term' => $params['term']
        ));
        $course->save();
        Redirect::to('/courses/' . $course->id, array('message' => 'Kurssi on lisätty tietokantaan!'));
    }
    
    public static function course_create() {
        View::make('course/new.html');
    }
    
}