import random
import string

f = open("add_test_data.sql","w")
f.write("INSERT INTO Teacher(name, email, password, is_teacher) VALUES ('Andreas','andreasn@cs.helsinki.fi','$2a$12$jNvUghmzXObxcfycqofQveqrk3P59kN.UD7REwxK34dbEv.R5PV/W',true);\n");
names = ["Johdatus yliopistomatematiikkaan","Lineaarialgebra ja matriisilaskenta I","Lineaarialgebra ja matriisilaskenta II"]
terms = ["Syksy 2015","Syksy 2016"]
courses = {}
course_index = 0

for name in names:
    for term in terms:
        course_index = course_index + 1
        courses[course_index] = (name, term)
        f.write("INSERT INTO Course(name, term, total_problems) VALUES ('" + name + "','" + term + "',400);\n")

students = {}
student_index = 0

for key, value in courses.iteritems():
    for j in range(1,401):
        student_index = student_index + 1
        student_no = ''.join(random.choice(string.digits) for _ in range(10))
        course_no = ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(10))
        students[student_index] = (student_no, course_no, key)
        f.write("INSERT INTO Student(student_number, course_number, course_id) VALUES ('" + student_no + "','" + course_no + "'," + str(key) + ");\n")

exercises = {}
exercise_index = 0

for key, value in courses.iteritems():
    for j in range(1,21):
        exercise_index = exercise_index + 1
        problems = random.randint(15,25)
        stars = random.randint(2,4)
        max_problems = random.randint(15-stars,problems-stars)
        max_stars = random.randint(2,stars)
        exercises[exercise_index] = (j, problems, stars, max_problems, max_stars, key)
        f.write("INSERT INTO Exercise(exercise_number, number_of_problems, number_of_star_problems, max_non_star_score, max_star_score, course_id) VALUES (" + str(j) + "," + str(problems) + "," + str(stars) + "," + str(max_problems) + "," + str(max_stars) + "," + str(key) + ");\n")

problems = {}
problem_index = 0

questions = {}
question_index = 0

for key, value in exercises.iteritems():
    stars = range(1,value[1]+1)
    for i in range(value[1]-value[2]):
        temp = random.choice(stars)
        stars.remove(temp)
    for j in range(1,value[1]+1):
        problem_index = problem_index + 1
        if j in stars:
            problems[problem_index] = (j, 1, key)
            f.write("INSERT INTO Problem(problem_number, star_problem, exercise_id) VALUES ('" + str(j) + "',true," + str(key) + ");\n")
            problem_index = problem_index + 1
            problems[problem_index] = (j, 1, key)
            f.write("INSERT INTO Problem(problem_number, star_problem, exercise_id) VALUES ('" + str(j) + "k1" + "',true," + str(key) + ");\n")
            problem_index = problem_index + 1
            problems[problem_index] = (j, 1, key)
            f.write("INSERT INTO Problem(problem_number, star_problem, exercise_id) VALUES ('" + str(j) + "k2" + "',true," + str(key) + ");\n")
        else:
            problems[problem_index] = (j, 0, key)
            f.write("INSERT INTO Problem(problem_number, star_problem, exercise_id) VALUES ('" + str(j) + "',false," + str(key) + ");\n")
    for j in range(1,6):
        question_index = question_index + 1
        questions[question_index] = (j, key)
        f.write("INSERT INTO Question(question_number, exercise_id) VALUES ('" + str(j) + "'," + str(key) + ");\n")

#for key2, value2 in students.iteritems():
#    if random.random() < 0.8:
#        for key1, value1 in problems.iteritems():    
#            if value2[2] == exercises[value1[2]][5]:
#                if random.random() < 0.8:
#                    if value1[1] == 0:
#                        mark = random.choice(["O"," "])
#                    else:
#                        mark = random.choice(["O","V"," "])
#                    f.write("INSERT INTO ProblemReturn(mark, problem_id, student_id) VALUES ('" + mark + "'," + str(key1) + "," + str(key2) + ");\n")
#        for key1, value1 in questions.iteritems():
#            if value2[2] == exercises[value1[1]][5]:
#                if random.random() < 0.8:
#                    mark = random.choice(["K","E"])
#                    f.write("INSERT INTO Answer(mark, question_id, student_id) VALUES ('" + mark + "'," + str(key1) + "," + str(key2) + ");\n")