INSERT INTO roles VALUES(1, "Admin", "CGA Admin");
INSERT INTO roles (role_name, role_description) VALUES("Student", "Course Student");
INSERT INTO roles (role_name, role_description) VALUES("TA", "Course Assistant");
INSERT INTO roles (role_name, role_description) VALUES("Professor", "Course Instructor");

INSERT INTO user VALUES(10000, "Admin", "CGA","1980-01-01", "admin", "admin","admin@cga.com",1, 1);
INSERT INTO user (first_name, last_name, dob, username, password, email, is_first_login, role_id) VALUES("John", "Doe","1990-01-01", "john123", "password","john@doe.com",1, 2);
INSERT INTO user (first_name, last_name, dob, username, password, email, is_first_login, role_id) VALUES("Yogesh", "Homeboi","1980-01-01", "yogesh123", "password","yogesh@homeboi.com",1, 3);
INSERT INTO user (first_name, last_name, dob, username, password, email, is_first_login, role_id) VALUES("Lord", "Desai","1900-01-01", "desai123", "password","lord@desai.com",1, 4);

INSERT INTO course VALUES(50000, "Files and DB", 5531, 0, 0);
INSERT INTO course (course_name, course_number, max_groups, max_members) VALUES("Tools and Techniques", 5541, 0, 0);
INSERT INTO course (course_name, course_number, max_groups, max_members) VALUES("Operating Systems", 5461, 0, 0);

INSERT INTO student VALUES(20000, 10001);
INSERT INTO ta VALUES(30000, 10002);
INSERT INTO professor VALUES(40000, 10003);

INSERT INTO student_groups VALUES(70000, "Group_1", 0);
INSERT INTO student_groups (group_name, group_leader_sid) VALUES("Group_2", 0);

INSERT INTO course_section VALUES(60000, "AA", 50000);
INSERT INTO announcement VALUES(2200000, "Project Due!!", 10003, "2022-03-11", "Must submit project soon or be fail", 60000);

-- student
INSERT INTO user_course VALUES(10001, 50000);
INSERT INTO user_course VALUES(10001, 50001);
INSERT INTO user_course VALUES(10001, 50002);
-- ta
INSERT INTO user_course VALUES(10002, 50000);
INSERT INTO user_course VALUES(10002, 50002);
-- prof
INSERT INTO user_course VALUES(10003, 50001);
INSERT INTO user_course VALUES(10003, 50002);

INSERT INTO member_of_group VALUES(20000, 1);

SELECT * from roles;
SELECT * from user;
SELECT * from course;
SELECT * from student;
SELECT * from ta;
SELECT * from professor;
SELECT * from user_course;
SELECT * from student_groups;
SELECT * from member_of_group;
SELECT * from course_section;
SELECT * from announcement;

SELECT * from user_course as uc
JOIN course as c ON uc.course_id = c.course_id
JOIN user as u ON uc.user_id = u.user_id
JOIN student as s ON s.user_id = u.user_id
WHERE u.role_id = 2;

SELECT * FROM user as u
JOIN announcement as a ON u.user_id = a.posted_by_uid; 

DELETE from announcement
WHERE announcement_id = 2200000;

DELETE from course_section
WHERE section_id = 60000;