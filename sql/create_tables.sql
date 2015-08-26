CREATE TABLE Teacher(
    id SERIAL PRIMARY KEY,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    is_teacher BOOLEAN NOT NULL
);

CREATE TABLE Course(
    id SERIAL PRIMARY KEY,
    name varchar(255) NOT NULL,
    term varchar(255) NOT NULL,
    total_problems INTEGER NOT NULL,
    total_star_problems INTEGER NOT NULL
);

CREATE TABLE Student(
    id SERIAL PRIMARY KEY,
    student_number varchar(255) NOT NULL,
    course_number varchar(255) NOT NULL,
    course_id INTEGER REFERENCES Course(id)
);

CREATE TABLE Exercise(
    id SERIAL PRIMARY KEY,
    exercise_number INTEGER NOT NULL,
    number_of_problems INTEGER NOT NULL,
    number_of_star_problems INTEGER NOT NULL,
    max_non_star_score INTEGER NOT NULL,
    max_star_score INTEGER NOT NULL,
    course_id INTEGER REFERENCES Course(id)
);

CREATE TABLE Problem(
    id SERIAL PRIMARY KEY,
    problem_number varchar(255) NOT NULL,
    star_problem BOOLEAN NOT NULL,
    exercise_id INTEGER REFERENCES Exercise(id)
);

CREATE TABLE ProblemReturn(
    mark char(1),
    problem_id INTEGER REFERENCES Problem(id),
    student_id INTEGER REFERENCES Student(id),
    PRIMARY KEY (problem_id, student_id)
);

CREATE TABLE Question(
    id SERIAL PRIMARY KEY,
    question_number varchar(255) NOT NULL,
    exercise_id INTEGER REFERENCES Exercise(id)
);

CREATE TABLE Answer(
    mark char(1),
    question_id INTEGER REFERENCES Question(id),
    student_id INTEGER REFERENCES Student(id),
    PRIMARY KEY (question_id, student_id)
);

CREATE TABLE Message(
    id SERIAL PRIMARY KEY,
    sender_name varchar(255) NOT NULL,
    sender_email varchar(255) NOT NULL,
    sender_password varchar(255) NOT NULL,
    sender_is_teacher BOOLEAN NOT NULL
);