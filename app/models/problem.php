<?php

class Problem extends BaseModel {

    public $id, $problem_number, $exercise_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all($exercise_id) {
        $query = DB::connection()->prepare('SELECT * FROM Problem WHERE exercise_id = :exercise_id');
        $query->execute(array('exercise_id' => $exercise_id));
        $rows = $query->fetchAll();
        $problems = array();
        foreach ($rows as $row) {
            $problems[] = new Problem(array(
                'id' => $row['id'],
                'problem_number' => $row['problem_number'],
                'exercise_id' => $row['exercise_id']
            ));
        }
        return $problems;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Problem WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $problem = new Exercise(array(
                'id' => $row['id'],
                'problem_number' => $row['exercise_number'],
                'exercise_id' => $row['course_id']
            ));
            return $problem;
        }
        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Problem (problem_number, exercise_id) VALUES (:problem_number, :exercise_id) RETURNING id');
        $query->execute(array('problem_number' => $this->problem_number, 'exercise_id' => $this->exercise_id));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

}
