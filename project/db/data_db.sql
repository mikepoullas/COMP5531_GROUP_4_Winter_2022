INSERT INTO roles (role_name, role_description) VALUES("Admin", "CGA Admin");
INSERT INTO roles (role_name, role_description) VALUES("Professor", "Course Instructor");
INSERT INTO roles (role_name, role_description) VALUES("TA", "Course Assistant");
INSERT INTO roles (role_name, role_description) VALUES("Student", "Course Student");

INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Admin", "CGA","1980-01-01", "admin@admin.com", "admin", "admin", CURRENT_TIMESTAMP, 1, 1);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Bipin", "Desai","1900-01-01", "bipin@desai.com", "b_desai_25", "12345", CURRENT_TIMESTAMP, 1, 2);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("David", "Probst","1900-01-01", "david@probst.com", "d_probst_69", "12345", CURRENT_TIMESTAMP, 1, 2);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Yogesh", "Yadav","1980-01-01", "yogesh@yadav.com", "y_yadav_71", "12345", CURRENT_TIMESTAMP, 1, 3);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Fan", "Zou","1980-01-01", "fan@zou.com", "f_zou_74", "12345", CURRENT_TIMESTAMP, 1, 3);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("John", "Doe","1990-01-01", "john@doe.com", "j_doe_22", "12345", CURRENT_TIMESTAMP,1, 4);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Mary", "Jane","1990-04-12", "mary@jane.com", "m_jane_81", "12345", CURRENT_TIMESTAMP,1, 4);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Tony", "Stark","1980-04-12", "tony@stark.com", "t_stark_12", "12345", CURRENT_TIMESTAMP,1, 4);

INSERT INTO course (course_name, course_number) VALUES("Files and DB", 5531);
INSERT INTO course (course_name, course_number) VALUES("Operating Systems", 5461);
INSERT INTO course (course_name, course_number) VALUES("Tools and Techniques", 5541);
INSERT INTO course (course_name, course_number) VALUES("Algos and Data Structures", 5511);

-- Course Section Table
INSERT INTO section (section_name, course_id) VALUES("AdbA", 50000);
INSERT INTO section (section_name, course_id) VALUES("BdbB", 50000);

INSERT INTO section (section_name, course_id) VALUES("AosA", 50001);
INSERT INTO section (section_name, course_id) VALUES("BosB", 50001);

INSERT INTO section (section_name, course_id) VALUES("CtoolsC", 50002);
INSERT INTO section (section_name, course_id) VALUES("DtoolsD", 50002);

INSERT INTO section (section_name, course_id) VALUES("CdsaC", 50003);
INSERT INTO section (section_name, course_id) VALUES("DdsaD", 50003);

-- Student Table
INSERT INTO student (user_id) VALUES(10005);
INSERT INTO student (user_id) VALUES(10006);
INSERT INTO student (user_id) VALUES(10007);

-- TA Table
INSERT INTO ta (user_id) VALUES(10003);
INSERT INTO ta (user_id) VALUES(10004);

-- Professor Table
INSERT INTO professor (user_id) VALUES(10001);
INSERT INTO professor (user_id) VALUES(10002);

-- prof to course section
INSERT INTO User_Course_Section (user_id, course_id) VALUES(10001, 50000);
INSERT INTO User_Course_Section (user_id, course_id) VALUES(10002, 50001);

-- TA to course section
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10003, 50000, 60000);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10003, 50000, 60001);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10004, 50001, 60002);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10004, 50001, 60003);

-- student to course section
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10005, 50000, 60000);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10005, 50001, 60002);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10005, 50002, 60004);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10006, 50001, 60003);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10006, 50002, 60005);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10006, 50003, 60007);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10007, 50000, 60001);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10007, 50001, 60003);
INSERT INTO User_Course_Section (user_id, course_id, section_id) VALUES(10007, 50003, 60007);

-- Student Groups Table
INSERT INTO student_group (group_name, group_leader_sid) VALUES("Group_1", 20000);
INSERT INTO student_group (group_name, group_leader_sid) VALUES("Group_2", 20001);
INSERT INTO student_group (group_name, group_leader_sid) VALUES("Group_3", 20002);

-- Assign Student to Group  
INSERT INTO member_of_group (student_id, group_id) VALUES(20000, 70000);
INSERT INTO member_of_group (student_id, group_id) VALUES(20001, 70001);
INSERT INTO member_of_group (student_id, group_id) VALUES(20002, 70002);

-- Assign Groups to Course
INSERT INTO group_of_course (group_id, course_id) VALUES(70000, 50000);
INSERT INTO group_of_course (group_id, course_id) VALUES(70001, 50001);
INSERT INTO group_of_course (group_id, course_id) VALUES(70002, 50002);

-- Course Anncoucement Table
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 1 !!", "Must submit project on time!", 10001, CURRENT_TIMESTAMP, 50000);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 2 !!", "Must submit project on time!", 10001, CURRENT_TIMESTAMP,  50001);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 3 !!", "Must submit project on time!", 10002, CURRENT_TIMESTAMP, 50002);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 4 !!", "Must submit project on time!", 10002, CURRENT_TIMESTAMP, 50003);

-- Group  Discussion Table
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Discussion 1", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 1", 10005, CURRENT_TIMESTAMP, 70000);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Discussion 2", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 2", 10006, CURRENT_TIMESTAMP, 70001);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Discussion 3", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 3", 10006, CURRENT_TIMESTAMP, 70000);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Discussion 4", "Lorem ipsum dolor sit amet, consectetur adipiscing elit 4", 10007, CURRENT_TIMESTAMP, 70001);

-- Group  Comment Table
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 1", 10003, CURRENT_TIMESTAMP, 4400000);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 2", 10004, CURRENT_TIMESTAMP, 4400001);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 3", 10001, CURRENT_TIMESTAMP, 4400002);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 4", 10002, CURRENT_TIMESTAMP, 4400003);

-- Files Table
-- INSERT INTO files (file_name, content, type, size, uploaded_by_uid, uploaded_on, downloads) VALUES("file_name_1.txt", "random list", "txt", 100, 10002, CURRENT_TIMESTAMP, 0);
-- INSERT INTO files (file_name, content, type, size, uploaded_by_uid, uploaded_on, downloads) VALUES("file_name_2.txt", "another list", "txt", 100, 10004, CURRENT_TIMESTAMP, 0);

-- Graded Submission Table
-- INSERT INTO graded_submission (type, content, deadline, course_id, file_id) VALUES("assignment", "Confusing assignment title", "2022-04-15 23:59:00" ,50000, 6600000);
-- INSERT INTO graded_submission (type, content, deadline, course_id, file_id) VALUES("project", " Confusing project title", "2022-04-15 23:59:00", 50001, 6600001);