<?php

class User extends BaseModel {

    public $id, $name, $email, $password, $is_teacher;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_email');
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Teacher WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => $row['password'],
                'is_teacher' => $row['is_teacher']
            ));
            return $user;
        } else {
            return null;
        }
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Teacher (name, email, password, is_teacher) VALUES (:name, :email, :password, :is_teacher) RETURNING id');
        $query->execute(array('name' => $this->name, 'email' => $this->email, 'password' => $this->password, 'is_teacher' => $this->is_teacher));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public static function authenticate($email, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Teacher WHERE email = :email LIMIT 1');
        $query->execute(array('email' => $email));
        $row = $query->fetch();
        if ($row) {
            if (crypt($password, $row['password']) == $row['password']) {
                $user = new User(array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => $row['password'],
                    'is_teacher' => $row['is_teacher']
                ));
                return $user;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function validate_name() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Nimi ei saa olla tyhjä!';
        }
        if (strlen($this->name) < 3) {
            $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
        }
        return $errors;
    }

    public function validate_email() {
        $errors = array();
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Sähköpostiosoite ei ole validi!';
        }
        return $errors;
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Teacher');
        $query->execute();
        $rows = $query->fetchAll();
        $users = array();
        foreach ($rows as $row) {
            $users[] = new User(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email'],
                'is_teacher' => $row['is_teacher']
            ));
        }
        return $users;
    }
    
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Teacher WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

}
