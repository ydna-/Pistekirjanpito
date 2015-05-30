<?php

class Exercise extends BaseModel {
    
    public $id, $exercise_number, $course_id;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Exercise');
        $query->execute();
        $rows = $query->fetchAll();
        $exercises = array();
        foreach($rows as $row) {
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
}
