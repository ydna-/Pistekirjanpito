<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
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

$routes->post('/logout', function() {
    UserController::logout();
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