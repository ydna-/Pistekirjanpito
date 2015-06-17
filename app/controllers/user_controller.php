<?php

class UserController extends BaseController {

    public static function login() {
        View::make('user/login.html');
    }

    public static function handle_login() {
        $params = $_POST;
        $user = User::authenticate($params['email'], $params['password']);
        if (!$user) {
            View::make('user/login.html', array('error' => 'Väärä sähköpostiosoite tai salasana!', 'email' => $params['email']));
        } else {
            $_SESSION['user'] = $user->id;
            Redirect::to('/courses', array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
        }
    }

    public static function register() {
        $courses = Course::all();
        View::make('user/register.html', array('courses' => $courses));
    }

    public static function handle_register() {
        $params = $_POST;
        $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
        $hash = crypt($params['password'], '$2a$12$' . $salt);
        if ($params['role'] == "teacher") {
            $attributes = array(
                'name' => $params['name'],
                'email' => $params['email'],
                'password' => $hash,
                'is_teacher' => true
            );
            $user = new User($attributes);
            $errors = $user->errors();
            if (count($errors) == 0) {
                $user->save();
                Redirect::to('/courses', array('message' => 'Rekisteröityminen onnistui!'));
            } else {
                View::make('user/register.html', array('errors' => $errors, 'name' => $params['name'], 'email' => $params['email']));
            }
            Kint::dump($params);
        } else if ($params['role'] == "instructor") {
            $attributes = array(
                'name' => $params['name'],
                'email' => $params['email'],
                'password' => $hash,
                'is_teacher' => false
            );
            $user = new User($attributes);
            $errors = $user->errors();
            if (count($errors) == 0) {
                $user->save_instructor();
                Redirect::to('/courses', array('message' => 'Rekisteröityminen onnistui!'));
            } else {
                View::make('user/register.html', array('errors' => $errors, 'name' => $params['name'], 'email' => $params['email']));
            }
            Kint::dump($params);
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

}
