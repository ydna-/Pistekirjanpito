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

$routes->get('/courses/1/exercises/new', function() {
    HelloWorldController::exercise_add();
});

$routes->get('/courses/1/exercises/1', function() {
    HelloWorldController::exercise_show();
});

$routes->get('/courses/1/exercises/1/edit', function() {
    HelloWorldController::exercise_edit();
});
