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
        View::make('user/register.html');
    }

    public static function handle_register() {
        $params = $_POST;
        if ($params['password'] === $params['validate_password']) {
            $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
            $hash = crypt($params['password'], '$2a$12$' . $salt);
            if ($params['role'] == "teacher") {
                $attributes = array(
                    'sender_name' => $params['name'],
                    'sender_email' => $params['email'],
                    'sender_password' => $hash,
                    'sender_is_teacher' => 1
                );
                $message = new Message($attributes);
                $errors = $message->errors();
                if (count($errors) == 0) {
                    $message->save();
                    Redirect::to('/register', array('message' => 'Rekisteröitymispyyntö on tallennettu!'));
                } else {
                    View::make('user/register.html', array('errors' => $errors, 'name' => $params['name'], 'email' => $params['email']));
                }
            } else if ($params['role'] == "instructor") {
                $attributes = array(
                    'sender_name' => $params['name'],
                    'sender_email' => $params['email'],
                    'sender_password' => $hash,
                    'sender_is_teacher' => 0
                );
                $message = new Message($attributes);
                $errors = $message->errors();
                if (count($errors) == 0) {
                    $message->save();
                    Redirect::to('/register', array('message' => 'Rekisteröitymispyyntö on tallennettu!'));
                } else {
                    View::make('user/register.html', array('errors' => $errors, 'name' => $params['name'], 'email' => $params['email']));
                }
            }
        } else {
            View::make('user/register.html', array('errors' => array('Salasanat eivät täsmää!'), 'name' => $params['name'], 'email' => $params['email']));
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

}
