<?php

class ProblemReturn extends BaseModel {

    public $mark, $problem_id, $student_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all($student_id) {
        $query = DB::connection()->prepare('SELECT * FROM ProblemReturn WHERE student_id = :student_id');
        $query->execute(array('student_id' => $student_id));
        $rows = $query->fetchAll();
        $returns = array();
        foreach ($rows as $row) {
            $returns[] = new ProblemReturn(array(
                'mark' => $row['mark'],
                'problem_id' => $row['problem_id'],
                'student_id' => $row['student_id']
            ));
        }
        return $returns;
    }
    
    public static function find($problem_id, $student_id) {
        $query = DB::connection()->prepare('SELECT * FROM ProblemReturn WHERE problem_id = :problem_id AND student_id = :student_id LIMIT 1');
        $query->execute(array('problem_id' => $problem_id, 'student_id' => $student_id));
        $row = $query->fetch();
        if ($row) {
            $return = new ProblemReturn(array(
                'mark' => $row['mark'],
                'problem_id' => $row['problem_id'],
                'student_id' => $row['student_id']
            ));
            return $return;
        }
        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO ProblemReturn (mark, problem_id, student_id) VALUES (:mark, :problem_id, :student_id)');
        $query->execute(array('mark' => $this->mark, 'problem_id' => $this->problem_id, 'student_id' => $this->student_id));
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE ProblemReturn SET mark = :mark WHERE problem_id = :problem_id AND student_id = :student_id');
        $query->execute(array('mark' => $this->mark, 'problem_id' => $this->problem_id, 'student_id' => $this->student_id));
    }

}
