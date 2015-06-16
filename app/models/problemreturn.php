<?php

class ProblemReturn extends BaseModel {

    public $mark, $problem_id, $student_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO ProblemReturn (mark, problem_id, student_id) VALUES (:mark, :problem_id, :student_id)');
        $query->execute(array('mark' => $this->mark, 'problem_id' => $this->problem_id, 'student_id' => $this->student_id));
    }

}
