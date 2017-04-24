<?php

class Question extends BaseModel {

    public $id, $question_number, $exercise_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all($exercise_id) {
        $query = DB::connection()->prepare("SELECT * FROM Question WHERE exercise_id = :exercise_id");
        $query->execute(array('exercise_id' => $exercise_id));
        $rows = $query->fetchAll();
        $questions = array();
        foreach ($rows as $row) {
            $questions[] = new Question(array(
                'id' => $row['id'],
                'question_number' => $row['question_number'],
                'exercise_id' => $row['exercise_id']
            ));
        }
        return $questions;
    }

    public static function question_numbers($exercise_id) {
        $query = DB::connection()->prepare("SELECT question_number FROM Question WHERE exercise_id = :exercise_id");
        $query->execute(array('exercise_id' => $exercise_id));
        $rows = $query->fetchAll();
        $numbers = array();
        foreach ($rows as $row) {
            $numbers[] = $row['question_number'];
        }
        return $numbers;
    }
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Question WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $question = new Question(array(
                'id' => $row['id'],
                'question_number' => $row['question_number'],
                'exercise_id' => $row['exercise_id']
            ));
            return $question;
        }
        return null;
    }

    public static function find_by_number($number, $exercise_id) {
        $query = DB::connection()->prepare('SELECT * FROM Question WHERE question_number = :question_number AND exercise_id = :exercise_id LIMIT 1');
        $query->execute(array('question_number' => $number, 'exercise_id' => $exercise_id));
        $row = $query->fetch();
        if ($row) {
            $question = new Question(array(
                'id' => $row['id'],
                'question_number' => $row['question_number'],
                'exercise_id' => $row['exercise_id']
            ));
            return $question;
        }
        return null;
    }

    public static function delete_all($exercise_id) {
        $questions = Question::all($exercise_id);
        foreach ($questions as $question) {
            Answer::delete_all($question->id);
        }
        $query = DB::connection()->prepare('DELETE FROM Question WHERE exercise_id = :exercise_id');
        $query->execute(array('exercise_id' => $exercise_id));
    }

    public static function delete_all_by_student($student_id) {
        $query = DB::connection()->prepare('DELETE FROM Question WHERE student_id = :student_id');
        $query->execute(array('student_id' => $student_id));
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Question (question_number, exercise_id) VALUES (:question_number, :exercise_id) RETURNING id');
        $query->execute(array('question_number' => $this->question_number, 'exercise_id' => $this->exercise_id));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

}
