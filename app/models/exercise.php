<?php

class Exercise extends BaseModel {

    public $id, $exercise_number, $number_of_problems, $number_of_star_problems, $course_id;

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
                'number_of_problems' => $row['number_of_problems'],
                'number_of_star_problems' => $row['number_of_star_problems'],
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
                'number_of_problems' => $row['number_of_problems'],
                'number_of_star_problems' => $row['number_of_star_problems'],
                'course_id' => $row['course_id']
            ));
            return $exercise;
        }
        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Exercise (exercise_number, number_of_problems, number_of_star_problems, course_id) VALUES (:exercise_number, :number_of_problems, :number_of_star_problems, :course_id) RETURNING id');
        $query->execute(array('exercise_number' => $this->exercise_number, 'number_of_problems' => $this->number_of_problems, 'number_of_star_problems' => $this->number_of_star_problems, 'course_id' => $this->course_id));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Exercise SET exercise_number = :exercise_number, course_id = :course_id WHERE id = :id');
        $query->execute(array('exercise_number' => $this->exercise_number, 'course_id' => $this->course_id, 'id' => $this->id));
    }

    public function destroy() {
        Problem::delete_all($this->id);
        $query = DB::connection()->prepare('DELETE FROM Exercise WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }
    
    public static function delete_all($course_id) {
        $exercises = Exercise::all($course_id);
        foreach ($exercises as $exercise) {
            Problem::delete_all($exercise->id);
        }
        $query = DB::connection()->prepare('DELETE FROM Exercise WHERE course_id = :course_id');
        $query->execute(array('course_id' => $course_id));
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
    
    public function get_number_of_returned() {
        $students = Student::all($this->course_id);
        $number = 0;
        foreach ($students as $student) {
            $returns = ProblemReturn::all_by_exercise_and_student($this->id, $student->id);
            if (count($returns) != 0) {
                $number++;
            }
        }
        return $number;
    }

}
