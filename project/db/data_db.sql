INSERT INTO roles VALUES(1, "Admin", "CGA Admin");
INSERT INTO roles (role_name, role_description) VALUES("Professor", "Course Instructor");
INSERT INTO roles (role_name, role_description) VALUES("TA", "Course Assistant");
INSERT INTO roles (role_name, role_description) VALUES("Student", "Course Student");

INSERT INTO users VALUES(10000, "Admin", "CGA","1980-01-01", "admin@admin.com", "admin", "admin", CURRENT_TIMESTAMP, 1, 1);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Bipin", "Desai","1900-01-01", "bipin@desai.com", "b_desai_25", "12345", CURRENT_TIMESTAMP, 1, 2);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("David", "Probst","1900-01-01", "david@probst.com", "d_probst_69", "12345", CURRENT_TIMESTAMP, 1, 2);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Yogesh", "Yadav","1980-01-01", "yogesh@yadav.com", "y_yadav_71", "12345", CURRENT_TIMESTAMP, 1, 3);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Fan", "Zou","1980-01-01", "fan@zou.com", "f_zou_74", "12345", CURRENT_TIMESTAMP, 1, 3);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("John", "Doe","1990-01-01", "john@doe.com", "j_doe_22", "12345", CURRENT_TIMESTAMP,1, 4);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Mary", "Jane","1990-04-12", "mary@jane.com", "m_jane_81", "12345", CURRENT_TIMESTAMP,1, 4);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Tony", "Stark","1980-04-12", "tony@stark.com", "t_stark_12", "12345", CURRENT_TIMESTAMP,1, 4);

INSERT INTO course VALUES(50000, "Files and DB", 5531);
INSERT INTO course (course_name, course_number) VALUES("Operating Systems", 5461);
INSERT INTO course (course_name, course_number) VALUES("Tools and Techniques", 5541);
INSERT INTO course (course_name, course_number) VALUES("Algos and Data Structures", 5511);

-- Course Section Table
INSERT INTO section (section_id, section_name, course_id) VALUES(60000, "AdbA", 50000);
INSERT INTO section (section_name, course_id) VALUES("BdbB", 50000);

INSERT INTO section (section_name, course_id) VALUES("AosA", 50001);
INSERT INTO section (section_name, course_id) VALUES("BosB", 50001);

INSERT INTO section (section_name, course_id) VALUES("CtoolsC", 50002);
INSERT INTO section (section_name, course_id) VALUES("DtoolsD", 50002);

INSERT INTO section (section_name, course_id) VALUES("CdsaC", 50003);
INSERT INTO section (section_name, course_id) VALUES("DdsaD", 50003);

-- Student Table
INSERT INTO student (student_id, user_id) VALUES(20000, 10005);
INSERT INTO student (user_id) VALUES(10006);
INSERT INTO student (user_id) VALUES(10007);

-- TA Table
INSERT INTO ta (ta_id, user_id) VALUES(30000, 10003);
INSERT INTO ta (user_id) VALUES(10004);

-- Professor Table
INSERT INTO professor (professor_id, user_id) VALUES(40000, 10001);
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
INSERT INTO student_group VALUES(70000, "Group_1", 20000);
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
INSERT INTO announcement (announcement_id, title, content, posted_by_uid, posted_on, course_id) VALUES(1100000, "Project Due 1 !!", "Must submit project on time!", 10001, CURRENT_TIMESTAMP, 50000);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 2 !!", "Must submit project on time!", 10001, CURRENT_TIMESTAMP,  50001);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 3 !!", "Must submit project on time!", 10002, CURRENT_TIMESTAMP, 50002);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 4 !!", "Must submit project on time!", 10002, CURRENT_TIMESTAMP, 50003);

-- Group  Discussion Table
INSERT INTO discussion (discussion_id, title, content, posted_by_uid, posted_on, group_id) VALUES(2200000, "Discussion 1", "1. Lorem ipsum dolor sit amet, consectetur adipiscing elit", 10005, CURRENT_TIMESTAMP, 70000);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Discussion 2", "2. Lorem ipsum dolor sit amet, consectetur adipiscing elit", 10006, CURRENT_TIMESTAMP, 70001);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Discussion 3", "3. Lorem ipsum dolor sit amet, consectetur adipiscing elit", 10006, CURRENT_TIMESTAMP, 70000);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Discussion 4", "4. Lorem ipsum dolor sit amet, consectetur adipiscing elit", 10007, CURRENT_TIMESTAMP, 70001);

-- Group  Comment Table
INSERT INTO comment (comment_id, content, posted_by_uid, posted_on, discussion_id) VALUES(3300000, "No Comment 1", 10003, CURRENT_TIMESTAMP, 2200000);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 2", 10004, CURRENT_TIMESTAMP, 2200001);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 3", 10001, CURRENT_TIMESTAMP, 2200002);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 4", 10002, CURRENT_TIMESTAMP, 2200003);

-- Files Table
INSERT INTO files (file_id, file_name, file_type, file_location, uploaded_by_uid, uploaded_on) VALUES(4400000, "random list","txt", "./CGA/files", 10002, CURRENT_TIMESTAMP);
INSERT INTO files (file_name, file_type, file_location, uploaded_by_uid, uploaded_on) VALUES("another list","txt", "./CGA/files", 10004, CURRENT_TIMESTAMP);

-- Course Assignment Table
INSERT INTO course_assignment (assignment_id, content, course_id, file_id) VALUES(80000, "assignment 1", 50000, 4400000);
INSERT INTO course_assignment (content, course_id, file_id) VALUES("assignment 2", 50001, 4400001);

-- Course Project Table
INSERT INTO course_project (project_id, content, course_id, file_id) VALUES(90000, "assignment 1", 50000, 4400000);
INSERT INTO course_assignment (content, course_id, file_id) VALUES("assignment 2", 50001, 4400001);