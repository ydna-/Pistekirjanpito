<?php

class Answer extends BaseModel {

    public $mark, $question_id, $student_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all_by_exercise($exercise_id) {
        $query = DB::connection()->prepare('SELECT mark, question_id, student_id FROM Answer INNER JOIN Question ON Answer.question_id = question.id WHERE exercise_id=:exercise_id');
        $query->execute(array('exercise_id' => $exercise_id));
        $rows = $query->fetchAll();
        $answers = array();
        foreach ($rows as $row) {
            $answers[] = new Answer(array(
                'mark' => $row['mark'],
                'question_id' => $row['question_id'],
                'student_id' => $row['student_id']
            ));
        }
        return $answers;
    }
    
    public static function all_by_student($student_id) {
        $query = DB::connection()->prepare('SELECT * FROM Answer WHERE student_id = :student_id');
        $query->execute(array('student_id' => $student_id));
        $rows = $query->fetchAll();
        $answers = array();
        foreach ($rows as $row) {
            $answers[] = new Answer(array(
                'mark' => $row['mark'],
                'question_id' => $row['question_id'],
                'student_id' => $row['student_id']
            ));
        }
        return $answers;
    }
    
    public static function all_by_exercise_and_student($exercise_id, $student_id) {
        $query = DB::connection()->prepare('SELECT mark, question_id, student_id FROM Answer INNER JOIN Question ON Answer.question_id = question.id WHERE exercise_id=:exercise_id AND student_id = :student_id');
        $query->execute(array('exercise_id' => $exercise_id, 'student_id' => $student_id));
        $rows = $query->fetchAll();
        $answers = array();
        foreach ($rows as $row) {
            $answers[] = new ProblemReturn(array(
                'mark' => $row['mark'],
                'question_id' => $row['question_id'],
                'student_id' => $row['student_id']
            ));
        }
        return $answers;
    }
    
    public static function find($question_id, $student_id) {
        $query = DB::connection()->prepare('SELECT * FROM Answer WHERE question_id = :question_id AND student_id = :student_id LIMIT 1');
        $query->execute(array('question_id' => $question_id, 'student_id' => $student_id));
        $row = $query->fetch();
        if ($row) {
            $answer = new Answer(array(
                'mark' => $row['mark'],
                'question_id' => $row['question_id'],
                'student_id' => $row['student_id']
            ));
            return $answer;
        }
        return null;
    }
    
    public static function find_by_number($number, $exercise_id, $student_id) {
        $question = Question::find_by_number($number, $exercise_id);
        $query = DB::connection()->prepare('SELECT * FROM Answer WHERE question_id = :question_id AND student_id = :student_id LIMIT 1');
        $query->execute(array('question_id' => $question->id, 'student_id' => $student_id));
        $row = $query->fetch();
        if ($row) {
            $answer = new Answer(array(
                'mark' => $row['mark'],
                'question_id' => $row['question_id'],
                'student_id' => $row['student_id']
            ));
            return $answer;
        }
        return null;
    }
    
    public static function delete_all($question_id) {
        $query = DB::connection()->prepare('DELETE FROM Abswer WHERE question_id = :question_id');
        $query->execute(array('question_id' => $question_id));
    }
    
    public static function delete_all_by_student($student_id) {
        $query = DB::connection()->prepare('DELETE FROM Answer WHERE student_id = :student_id');
        $query->execute(array('student_id' => $student_id));
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Answer (mark, question_id, student_id) VALUES (:mark, :question_id, :student_id)');
        $query->execute(array('mark' => $this->mark, 'question_id' => $this->question_id, 'student_id' => $this->student_id));
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE Answer SET mark = :mark WHERE question_id = :question_id AND student_id = :student_id');
        $query->execute(array('mark' => $this->mark, 'question_id' => $this->question_id, 'student_id' => $this->student_id));
    }

}
