<?php

class Exercise extends BaseModel {

    public $id, $exercise_number, $course_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_number');
    }

    public static function all($course_id) {
        $query = DB::connection()->prepare('SELECT * FROM Exercise WHERE course_id = :course_id');
        $query->execute(array('course_id' => $course_id));
        $rows = $query->fetchAll();
        $exercises = array();
        foreach ($rows as $row) {
            $exercises[] = new Exercise(array(
                'id' => $row['id'],
                'exercise_number' => $row['exercise_number'],
                'course_id' => $row['course_id']
            ));
        }
        return $exercises;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Exercise WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $exercise = new Exercise(array(
                'id' => $row['id'],
                'exercise_number' => $row['exercise_number'],
                'course_id' => $row['course_id']
            ));
            return $exercise;
        }
        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Exercise (exercise_number, course_id) VALUES (:exercise_number, :course_id) RETURNING id');
        $query->execute(array('exercise_number' => $this->exercise_number, 'course_id' => $this->course_id));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Exercise SET exercise_number = :exercise_number, course_id = :course_id WHERE id = :id');
        $query->execute(array('exercise_number' => $this->exercise_number, 'course_id' => $this->course_id, 'id' => $this->id));
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Exercise WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function validate_number() {
        $errors = array();
        if ($this->exercise_number == null) {
            $errors[] = 'Harjoituksen numero ei saa olla tyhjä!';
        }
        if ($this->exercise_number < 1) {
            $errors[] = 'Harjoituksen numeron tulee olla positiivinen!';
        }
        return $errors;
    }

}
