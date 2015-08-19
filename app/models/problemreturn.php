<?php

class ProblemReturn extends BaseModel {

    public $mark, $problem_id, $student_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all_by_exercise($exercise_id) {
        $query = DB::connection()->prepare("SELECT mark, problem_id, student_id FROM ProblemReturn INNER JOIN Problem ON ProblemReturn.problem_id = problem.id WHERE exercise_id=:exercise_id AND problem_number NOT LIKE '%k%'");
        $query->execute(array('exercise_id' => $exercise_id));
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
    
    public static function all_first_by_exercise($exercise_id) {
        $query = DB::connection()->prepare("SELECT mark, problem_id, student_id FROM ProblemReturn INNER JOIN Problem ON ProblemReturn.problem_id = problem.id WHERE exercise_id=:exercise_id AND problem_number LIKE '%k1'");
        $query->execute(array('exercise_id' => $exercise_id));
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
    
    public static function all_second_by_exercise($exercise_id) {
        $query = DB::connection()->prepare("SELECT mark, problem_id, student_id FROM ProblemReturn INNER JOIN Problem ON ProblemReturn.problem_id = problem.id WHERE exercise_id=:exercise_id AND problem_number LIKE '%k2'");
        $query->execute(array('exercise_id' => $exercise_id));
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
    
    public static function all_by_student($student_id) {
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
    
    public static function all_by_exercise_and_student($exercise_id, $student_id) {
        $query = DB::connection()->prepare('SELECT mark, problem_id, student_id FROM ProblemReturn INNER JOIN Problem ON ProblemReturn.problem_id = problem.id WHERE exercise_id=:exercise_id AND student_id = :student_id');
        $query->execute(array('exercise_id' => $exercise_id, 'student_id' => $student_id));
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
    
    public static function find_by_number($problem_number, $exercise_id, $student_id) {
        $problem = Problem::find_by_number($problem_number, $exercise_id);
        $query = DB::connection()->prepare('SELECT * FROM ProblemReturn WHERE problem_id = :problem_id AND student_id = :student_id LIMIT 1');
        $query->execute(array('problem_id' => $problem->id, 'student_id' => $student_id));
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
    
    public static function delete_all($problem_id) {
        $query = DB::connection()->prepare('DELETE FROM ProblemReturn WHERE problem_id = :problem_id');
        $query->execute(array('problem_id' => $problem_id));
    }
    
    public static function delete_all_by_student($student_id) {
        $query = DB::connection()->prepare('DELETE FROM ProblemReturn WHERE student_id = :student_id');
        $query->execute(array('student_id' => $student_id));
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
