<?php

class Message extends BaseModel {

    public $id, $sender_name, $sender_email, $sender_password, $sender_is_teacher;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_email');
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Message WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $message = new Message(array(
                'id' => $row['id'],
                'sender_name' => $row['sender_name'],
                'sender_email' => $row['sender_email'],
                'sender_password' => $row['sender_password'],
                'sender_is_teacher' => $row['sender_is_teacher']
            ));
            return $message;
        } else {
            return null;
        }
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Message');
        $query->execute();
        $rows = $query->fetchAll();
        $messages = array();
        foreach ($rows as $row) {
            $messages[] = new Message(array(
                'id' => $row['id'],
                'sender_name' => $row['sender_name'],
                'sender_email' => $row['sender_email'],
                'sender_password' => $row['sender_password'],
                'sender_is_teacher' => $row['sender_is_teacher']
            ));
        }
        return $messages;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Message (sender_name, sender_email, sender_password, sender_is_teacher) VALUES (:sender_name, :sender_email, :sender_password, :sender_is_teacher) RETURNING id');
        $query->execute(array('sender_name' => $this->sender_name, 'sender_email' => $this->sender_email, 'sender_password' => $this->sender_password, 'sender_is_teacher' => $this->sender_is_teacher));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Message WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function validate_name() {
        $errors = array();
        if ($this->sender_name == '' || $this->sender_name == null) {
            $errors[] = 'Nimi ei saa olla tyhjä!';
        }
        if (strlen($this->sender_name) < 3) {
            $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
        }
        return $errors;
    }

    public function validate_email() {
        $errors = array();
        if (!filter_var($this->sender_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Sähköpostiosoite ei ole validi!';
        }
        return $errors;
    }

}
