<?php

$routes->get('/', function() {
    HomeController::index();
});

$routes->post('/scores', function() {
    HomeController::score_query();
});

$routes->get('/login', function() {
    UserController::login();
});

$routes->post('/login', function() {
    UserController::handle_login();
});

$routes->get('/register', function() {
    UserController::register();
});

$routes->post('/register', function() {
    UserController::handle_register();
});

$routes->get('/messages', function() {
    MessageController::message_list();
});

$routes->post('/messages/:id/accept', function($id) {
    MessageController::message_accept($id);
});

$routes->post('/messages/:id/decline', function($id) {
    MessageController::message_destroy($id);
});

$routes->post('/logout', function() {
    UserController::logout();
});

$routes->get('/users', function() {
    UserController::user_list();
});

$routes->post('/users/:id/destroy', function($id) {
    UserController::user_destroy($id);
});

$routes->get('/courses', function() {
    CourseController::course_list();
});

$routes->post('/courses', function() {
    CourseController::course_store();
});

$routes->get('/courses/new', function() {
    CourseController::course_create();
});

$routes->get('/courses/:id', function($id) {
    CourseController::course_show($id);
});

$routes->get('/courses/:id/edit', function($id) {
    CourseController::course_edit($id);
});

$routes->post('/courses/:id/edit', function($id) {
    CourseController::course_update($id);
});

$routes->post('/courses/:id/destroy', function($id) {
    CourseController::course_destroy($id);
});

$routes->post('/courses/:course_id/exercises', function($course_id) {
    ExerciseController::exercise_store($course_id);
});

$routes->get('/courses/:course_id/exercises/new', function($course_id) {
    ExerciseController::exercise_create($course_id);
});

$routes->get('/courses/:course_id/exercises/:id', function($course_id, $id) {
    ExerciseController::exercise_show($course_id, $id);
});

$routes->get('/courses/:course_id/exercises/:id/edit', function($course_id, $id) {
    ExerciseController::exercise_edit($course_id, $id);
});

$routes->post('/courses/:course_id/exercises/:id/edit', function($course_id, $id) {
    ExerciseController::exercise_update($course_id, $id);
});

$routes->post('/courses/:course_id/exercises/:id/destroy', function($course_id, $id) {
    ExerciseController::exercise_destroy($course_id, $id);
});

$routes->get('/courses/:course_id/students', function($course_id) {
    StudentController::student_list($course_id);
});

$routes->post('/courses/:course_id/students', function($course_id) {
    StudentController::student_store_one($course_id);
});

$routes->post('/courses/:course_id/students/csv', function($course_id) {
    StudentController::student_store($course_id);
});

$routes->get('/courses/:course_id/students/new', function($course_id) {
    StudentController::student_create($course_id);
});

$routes->get('/courses/:course_id/students/:student_id/edit', function($course_id, $student_id) {
    StudentController::student_edit($course_id, $student_id);
});

$routes->post('/courses/:course_id/students/:student_id/edit', function($course_id, $student_id) {
    StudentController::student_update($course_id, $student_id);
});

$routes->post('/courses/:course_id/students/:student_id/destroy', function($course_id, $student_id) {
    StudentController::student_destroy($course_id, $student_id);
});

$routes->get('/courses/:course_id/exercises/:exercise_id/log', function($course_id, $exercise_id) {
    LogController::log_create($course_id, $exercise_id);
});

$routes->post('/courses/:course_id/exercises/:exercise_id/log', function($course_id, $exercise_id) {
    LogController::log_store($course_id, $exercise_id);
});

$routes->get('/courses/:course_id/exercises/:exercise_id/log/first', function($course_id, $exercise_id) {
    LogController::log_first_create($course_id, $exercise_id);
});

$routes->post('/courses/:course_id/exercises/:exercise_id/log/first', function($course_id, $exercise_id) {
    LogController::log_first_store($course_id, $exercise_id);
});

$routes->get('/courses/:course_id/exercises/:exercise_id/log/second', function($course_id, $exercise_id) {
    LogController::log_second_create($course_id, $exercise_id);
});

$routes->post('/courses/:course_id/exercises/:exercise_id/log/second', function($course_id, $exercise_id) {
    LogController::log_second_store($course_id, $exercise_id);
});

$routes->get('/courses/:course_id/csv', function($course_id) {
    CourseController::course_csv($course_id);
});

$routes->get('/courses/:course_id/question_csv', function($course_id) {
    CourseController::course_question_csv($course_id);
});

$routes->get('/courses/:course_id/summary', function($course_id) {
    CourseController::course_summary_csv($course_id);
});

$routes->get('/courses/:course_id/exercises/:exercise_id/csv', function($course_id, $exercise_id) {
    ExerciseController::exercise_csv($course_id, $exercise_id);
});