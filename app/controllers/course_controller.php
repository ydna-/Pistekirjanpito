<?php

class CourseController extends BaseController {

    public static function course_list() {
        self::check_logged_in();
        $courses = Course::all();
        View::make('course/list.html', array('courses' => $courses));
    }

    public static function course_show($id) {
        self::check_logged_in();
        $course = Course::find($id);
        View::make('course/show.html', array('course' => $course));
    }

    public static function course_store() {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'name' => $params['name'],
            'term' => $params['term']
        );
        $course = new Course($attributes);
        $errors = $course->errors();
        if (count($errors) == 0) {
            $course->save();
            Redirect::to('/courses/' . $course->id, array('message' => 'Kurssi on lisätty tietokantaan!'));
        } else {
            View::make('course/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function course_create() {
        self::check_logged_in();
        View::make('course/new.html');
    }

    public static function course_edit($id) {
        self::check_logged_in();
        $course = Course::find($id);
        View::make('course/edit.html', array('attributes' => $course));
    }

    public static function course_update($id) {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'term' => $params['term']
        );
        $course = new Course($attributes);
        $errors = $course->errors();
        if (count($errors) == 0) {
            $course->update();
            Redirect::to('/courses/' . $course->id, array('message' => 'Kurssin tiedot on päivitetty!'));
        } else {
            View::make('course/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function course_destroy($id) {
        self::check_logged_in();
        $course = new Course(array('id' => $id));
        $course->destroy();
        Redirect::to('/courses', array('message' => 'Kurssi on poistettu tietokannasta!'));
    }

}
