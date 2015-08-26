<?php

class Course extends BaseModel {

    public $id, $name, $term, $total_problems, $total_star_problems;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_term');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Course');
        $query->execute();
        $rows = $query->fetchAll();
        $courses = array();
        foreach ($rows as $row) {
            $courses[] = new Course(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'term' => $row['term'],
                'total_problems' => $row['total_problems'],
                'total_star_problems' => $row['total_star_problems']
            ));
        }
        return $courses;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Course WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $course = new Course(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'term' => $row['term'],
                'total_problems' => $row['total_problems'],
                'total_star_problems' => $row['total_star_problems']
            ));
            return $course;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Course (name, term, total_problems, total_star_problems) VALUES (:name, :term, :total_problems, :total_star_problems) RETURNING id');
        $query->execute(array('name' => $this->name, 'term' => $this->term, 'total_problems' => $this->total_problems, 'total_star_problems' => $this->total_star_problems));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Course SET name = :name, term = :term, total_problems = :total_problems, total_star_problems = :total_star_problems WHERE id = :id');
        $query->execute(array('name' => $this->name, 'term' => $this->term, 'total_problems' => $this->total_problems, 'total_star_problems' => $this->total_star_problems, 'id' => $this->id));
    }

    public function destroy() {
        Exercise::delete_all($this->id);
        Student::delete_all($this->id);
        $query = DB::connection()->prepare('DELETE FROM Course WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function validate_name() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Nimi ei saa olla tyhjä!';
        }
        if (strlen($this->name) < 3) {
            $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
        }
        return $errors;
    }

    public function validate_term() {
        $errors = array();
        if ($this->term == '' || $this->term == null) {
            $errors[] = 'Lukukausi ei saa olla tyhjä!';
        }
        if (strlen($this->name) < 3) {
            $errors[] = 'Lukukauden pituuden tulee olla vähintään kolme merkkiä!';
        }
        return $errors;
    }

}
