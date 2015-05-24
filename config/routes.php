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
    HelloWorldController::course_list();
});

$routes->get('/courses/1', function() {
    HelloWorldController::course_show();
});

$routes->get('/courses/new', function() {
    HelloWorldController::course_add();
});

$routes->get('/courses/1/edit', function() {
    HelloWorldController::course_edit();
});

$routes->get('/courses/1/exercises/1', function() {
    HelloWorldController::exercise_show();
});

$routes->get('/courses/1/exercises/new', function() {
    HelloWorldController::exercise_add();
});

$routes->get('/courses/1/exercises/1/edit', function() {
    HelloWorldController::exercise_edit();
});