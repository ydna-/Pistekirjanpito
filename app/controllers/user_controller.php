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

    public static function user_list() {
        self::check_is_teacher();
        $users = User::all();
        View::make('user/list.html', array('users' => $users));
    }

    public static function user_edit() {
        $user = self::get_user_logged_in();
        View::make('user/edit.html', array('user' => $user));
    }

    public static function user_update() {
        $params = $_POST;
        $user = self::get_user_logged_in();
        $params = $_POST;
        if ($params['password'] === $params['validate_password']) {
            $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
            $hash = crypt($params['password'], '$2a$12$' . $salt);
            $attributes = array(
                'id' => $id,
                'exercise_number' => $params['exercise_number'],
                'course_id' => $course_id
            );
            $user = new User($attributes);
            $errors = $exercise->errors();
            if (count($errors) == 0) {
                $exercise->update();
                Redirect::to('/edit', array('message' => 'Omat tiedot on päivitetty!'));
            } else {
                View::make('user/edit.html', array('errors' => $errors, 'attributes' => $attributes));
            }
        } else {
            View::make('user/edit.html', array('errors' => array('Salasanat eivät täsmää!'), 'name' => $params['name'], 'email' => $params['email']));
        }
    }

    public static function user_destroy($id) {
        self::check_is_teacher();
        $user = new User(array('id' => $id));
        $user->destroy();
        Redirect::to('/users', array('message' => 'Käyttäjä on poistettu järjestelmästä!'));
    }

}
