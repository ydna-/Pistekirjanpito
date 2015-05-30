<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/register', function() {
    HelloWorldController::register();
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

$routes->get('/courses/1/exercises/new', function() {
    HelloWorldController::exercise_add();
});

$routes->get('/courses/1/exercises/1', function() {
    HelloWorldController::exercise_show();
});

$routes->get('/courses/1/exercises/1/edit', function() {
    HelloWorldController::exercise_edit();
});