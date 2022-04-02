/*

10000 - user, 
20000 - student
30000 - ta
40000 - professor
50000 - course
60000 - section
70000 - groups
80000 - submission
90000 - grades

1100000 - announcement
2200000 - forum
3300000 - reply
4400000 - discussion
5500000 - comment
6600000 - files

*/

INSERT INTO roles (role_name, role_description) VALUES
("Admin", "CGA Admin"),
("Professor", "Course Instructor"),
("TA", "Course Assistant"),
("Student", "Course Student");

INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES 
("Admin", "CGA","1980-01-01", "admin@admin.com", "admin", "admin", CURRENT_TIMESTAMP, 1, 1),
("Bipin", "Desai","1900-01-01", "bipin@desai.com", "b_desai_25", "12345", CURRENT_TIMESTAMP, 1, 2),
("David", "Probst","1900-01-01", "david@probst.com", "d_probst_69", "12345", CURRENT_TIMESTAMP, 1, 2),
("Stuart", "Thiel","1900-01-01", "stuart@thiel.com", "s_thiel_89", "12345", CURRENT_TIMESTAMP, 1, 2),
("Yogesh", "Yadav","1980-01-01", "yogesh@yadav.com", "y_yadav_71", "12345", CURRENT_TIMESTAMP, 1, 3),
("Fan", "Zou","1980-01-01", "fan@zou.com", "f_zou_74", "12345", CURRENT_TIMESTAMP, 1, 3),
("Charmi", "Shah","1980-01-01", "charmi@shah.com", "s_shah_98", "12345", CURRENT_TIMESTAMP, 1, 3),
("John", "Doe","1990-01-01", "john@doe.com", "j_doe_22", "12345", CURRENT_TIMESTAMP,1, 4),
("Mary", "Jane","1990-04-12", "mary@jane.com", "m_jane_81", "12345", CURRENT_TIMESTAMP,1, 4),
("Tony", "Stark","1980-04-12", "tony@stark.com", "t_stark_12", "12345", CURRENT_TIMESTAMP,1, 4);

-- Professor Table
INSERT INTO professor (user_id) VALUES
(10001),
(10002),
(10003);

-- TA Table
INSERT INTO ta (user_id) VALUES
(10004),
(10005),
(10006);

-- Student Table
INSERT INTO student (user_id) VALUES
(10007),
(10008),
(10009);

INSERT INTO course (course_name, course_number) VALUES
("Files and DB", 5531),
("Operating Systems", 5461),
("Tools and Techniques", 5541);

-- Course Section Table
INSERT INTO section (section_name, course_id) VALUES
("AdbA", 50000),
("BdbB", 50000),
("AosA", 50001),
("BosB", 50001),
("CtoolsC", 50002),
("DtoolsD", 50002);

INSERT INTO User_Course_Section (user_id, course_id) VALUES
-- prof to course section
(10001, 50000),
(10002, 50001),
(10003, 50002);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES
-- TA to course section
(10004, 50000, 60000),
(10004, 50000, 60001),
(10005, 50001, 60002),
(10005, 50001, 60003),
(10006, 50002, 60004),
(10006, 50002, 60005),
-- student to course section
(10007, 50000, 60000),
(10007, 50001, 60002),

(10008, 50001, 60003),
(10008, 50002, 60005),

(10009, 50000, 60001),
(10009, 50002, 60004);

-- Student Groups Table
INSERT INTO student_group (group_name, group_leader_sid) VALUES
("Group_1", 20000),
("Group_2", 20001),
("Group_3", 20002);

-- Assign Student to Group  
INSERT INTO member_of_group (student_id, group_id) VALUES
(20000, 70000),
(20002, 70000),

(20000, 70001),
(20001, 70001),

(20001, 70002),
(20002, 70002);

-- Assign Groups to Course
INSERT INTO group_of_course (group_id, course_id) VALUES
(70000, 50000),
(70001, 50001),
(70002, 50002);

-- Course Anncoucement Table
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES
("Project Due 1 !!", "Must submit project on time!", 10001, CURRENT_TIMESTAMP, 50000),
("Project Due 2 !!", "Must submit project on time!", 10002, CURRENT_TIMESTAMP,  50001),
("Project Due 3 !!", "Must submit project on time!", 10003, CURRENT_TIMESTAMP, 50002);

-- Group  Discussion Table
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES
("Discussion 1", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 1", 10007, CURRENT_TIMESTAMP, 70000),
("Discussion 2", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 2", 10008, CURRENT_TIMESTAMP, 70001),
("Discussion 3", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 3", 10009, CURRENT_TIMESTAMP, 70002);

-- Group  Comment Table
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES
("No Comment 1", 10007, CURRENT_TIMESTAMP, 4400000),
("No Comment 2", 10008, CURRENT_TIMESTAMP, 4400001),
("No Comment 3", 10009, CURRENT_TIMESTAMP, 4400002);

-- Course  Forum Table
INSERT INTO forum (title, content, posted_by_uid, posted_on, course_id) VALUES
("Forum 1", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 1", 10007, CURRENT_TIMESTAMP, 50000),
("Forum 2", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 2", 10008, CURRENT_TIMESTAMP, 50001),
("Forum 3", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 3", 10009, CURRENT_TIMESTAMP, 50002);

-- Course  Reply Table
INSERT INTO reply (content, posted_by_uid, posted_on, forum_id) VALUES
("No Comment 1", 10007, CURRENT_TIMESTAMP, 2200000),
("No Comment 2", 10008, CURRENT_TIMESTAMP, 2200001),
("No Comment 3", 10009, CURRENT_TIMESTAMP, 2200002);

-- Files Table
-- INSERT INTO files (file_name, content, type, size, uploaded_by_uid, uploaded_on, downloads) VALUES("file_name_1.txt", "random list", "txt", 100, 10002, CURRENT_TIMESTAMP, 0);
-- INSERT INTO files (file_name, content, type, size, uploaded_by_uid, uploaded_on, downloads) VALUES("file_name_2.txt", "another list", "txt", 100, 10004, CURRENT_TIMESTAMP, 0);

-- Graded Submission Table
-- INSERT INTO graded_submission (type, content, deadline, course_id, file_id) VALUES("assignment", "Confusing assignment title", "2022-04-15 23:59:00" ,50000, 6600000);
-- INSERT INTO graded_submission (type, content, deadline, course_id, file_id) VALUES("project", " Confusing project title", "2022-04-15 23:59:00", 50001, 6600001);