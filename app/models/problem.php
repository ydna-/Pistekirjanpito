<?php

class Problem extends BaseModel {

    public $id, $problem_number, $star_problem, $exercise_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all_plus_star($exercise_id) {
        $query = DB::connection()->prepare("SELECT * FROM Problem WHERE exercise_id = :exercise_id");
        $query->execute(array('exercise_id' => $exercise_id));
        $rows = $query->fetchAll();
        $problems = array();
        foreach ($rows as $row) {
            $problems[] = new Problem(array(
                'id' => $row['id'],
                'problem_number' => $row['problem_number'],
                'star_problem' => $row['star_problem'],
                'exercise_id' => $row['exercise_id']
            ));
        }
        return $problems;
    }

    public static function all($exercise_id) {
        $query = DB::connection()->prepare("SELECT * FROM Problem WHERE exercise_id = :exercise_id AND problem_number NOT LIKE '%k%'");
        $query->execute(array('exercise_id' => $exercise_id));
        $rows = $query->fetchAll();
        $problems = array();
        foreach ($rows as $row) {
            $problems[] = new Problem(array(
                'id' => $row['id'],
                'problem_number' => $row['problem_number'],
                'star_problem' => $row['star_problem'],
                'exercise_id' => $row['exercise_id']
            ));
        }
        return $problems;
    }

    public static function all_first($exercise_id) {
        $query = DB::connection()->prepare("SELECT * FROM Problem WHERE exercise_id = :exercise_id AND problem_number LIKE '%k1'");
        $query->execute(array('exercise_id' => $exercise_id));
        $rows = $query->fetchAll();
        $problems = array();
        foreach ($rows as $row) {
            $problems[] = new Problem(array(
                'id' => $row['id'],
                'problem_number' => $row['problem_number'],
                'star_problem' => $row['star_problem'],
                'exercise_id' => $row['exercise_id']
            ));
        }
        return $problems;
    }

    public static function all_second($exercise_id) {
        $query = DB::connection()->prepare("SELECT * FROM Problem WHERE exercise_id = :exercise_id AND problem_number LIKE '%k2'");
        $query->execute(array('exercise_id' => $exercise_id));
        $rows = $query->fetchAll();
        $problems = array();
        foreach ($rows as $row) {
            $problems[] = new Problem(array(
                'id' => $row['id'],
                'problem_number' => $row['problem_number'],
                'star_problem' => $row['star_problem'],
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
            $problem = new Problem(array(
                'id' => $row['id'],
                'problem_number' => $row['problem_number'],
                'star_problem' => $row['star_problem'],
                'exercise_id' => $row['exercise_id']
            ));
            return $problem;
        }
        return null;
    }

    public static function find_by_number($number, $exercise_id) {
        $query = DB::connection()->prepare('SELECT * FROM Problem WHERE problem_number = :problem_number AND exercise_id = :exercise_id LIMIT 1');
        $query->execute(array('problem_number' => $number, 'exercise_id' => $exercise_id));
        $row = $query->fetch();
        if ($row) {
            $problem = new Problem(array(
                'id' => $row['id'],
                'problem_number' => $row['problem_number'],
                'star_problem' => $row['star_problem'],
                'exercise_id' => $row['exercise_id']
            ));
            return $problem;
        }
        return null;
    }

    public static function delete_all($exercise_id) {
        $problems = Problem::all_plus_star($exercise_id);
        foreach ($problems as $problem) {
            ProblemReturn::delete_all($problem->id);
        }
        $query = DB::connection()->prepare('DELETE FROM Problem WHERE exercise_id = :exercise_id');
        $query->execute(array('exercise_id' => $exercise_id));
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Problem (problem_number, star_problem, exercise_id) VALUES (:problem_number, :star_problem, :exercise_id) RETURNING id');
        $query->execute(array('problem_number' => $this->problem_number, 'star_problem' => $this->star_problem, 'exercise_id' => $this->exercise_id));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

}
