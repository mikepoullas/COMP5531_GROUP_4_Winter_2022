INSERT INTO roles VALUES(1, "Admin", "CGA Admin");
INSERT INTO roles (role_name, role_description) VALUES("Professor", "Course Instructor");
INSERT INTO roles (role_name, role_description) VALUES("TA", "Course Assistant");
INSERT INTO roles (role_name, role_description) VALUES("Student", "Course Student");

INSERT INTO users VALUES(10000, "Admin", "CGA","1980-01-01", "admin@cga.com", "admin", "admin", CURRENT_TIMESTAMP, 1, 1);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Bipin", "Desai","1900-01-01", "lord@desai.com", "desai123", "password", CURRENT_TIMESTAMP, 1, 2);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Yogesh", "Yadav","1980-01-01", "yogesh@homeboi.com", "yogesh123", "password", CURRENT_TIMESTAMP, 1, 3);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("John", "Doe","1990-01-01", "john@doe.com", "john123", "password", CURRENT_TIMESTAMP,1, 4);
INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) VALUES("Mary", "Jane","1990-04-12", "mary@jane.com", "mary123", "password", CURRENT_TIMESTAMP,1, 4);

INSERT INTO course VALUES(50000, "Files and DB", 5531);
INSERT INTO course (course_name, course_number) VALUES("Tools and Techniques", 5541);
INSERT INTO course (course_name, course_number) VALUES("Operating Systems", 5461);
INSERT INTO course (course_name, course_number) VALUES("Algos and Data Structures", 5511);

-- Student Table
INSERT INTO student (student_id, user_id) VALUES(20000, 10003);
INSERT INTO student (user_id) VALUES(10004);

-- TA Table
INSERT INTO ta (ta_id, user_id) VALUES(30000, 10002);

-- Professor Table
INSERT INTO professor (professor_id, user_id) VALUES(40000, 10001);

-- Students taking courses 
INSERT INTO user_course (user_id, course_id) VALUES(10003, 50000);
INSERT INTO user_course (user_id, course_id) VALUES(10003, 50001);
INSERT INTO user_course (user_id, course_id) VALUES(10003, 50002);
INSERT INTO user_course (user_id, course_id) VALUES(10004, 50002);
INSERT INTO user_course (user_id, course_id) VALUES(10004, 50003);

-- Course Section Table
INSERT INTO course_section (section_id, section_name, course_id) VALUES(60000, "AA", 50000);
INSERT INTO course_section (section_name, course_id) VALUES("BB", 50001);
INSERT INTO course_section (section_name, course_id) VALUES("CC", 50002);
INSERT INTO course_section (section_name, course_id) VALUES("DD", 50003);

-- TA's assigned to courses
INSERT INTO user_course (user_id, course_id) VALUES(10002, 50000);
INSERT INTO user_course (user_id, course_id) VALUES(10002, 50002);

-- Professors teaching courses
INSERT INTO user_course (user_id, course_id) VALUES(10001, 50001);
INSERT INTO user_course (user_id, course_id) VALUES(10001, 50002);

-- Student Groups Table
INSERT INTO student_groups VALUES(70000, "Group_1", 0);
INSERT INTO student_groups (group_name, group_leader_sid) VALUES("Group_2", 0);

-- Assign Student to Group  
INSERT INTO member_of_group (student_id, group_id) VALUES(20000, 70000);
INSERT INTO member_of_group (student_id, group_id) VALUES(20001, 70001);

-- Assign Groups to Section
INSERT INTO section_groups (section_id, group_id) VALUES(60000, 70000);
INSERT INTO section_groups (section_id, group_id) VALUES(60000, 70001);

-- Course Anncoucement Table
INSERT INTO announcement (announcement_id, title, content, posted_by_uid, posted_on, course_id) VALUES(1100000, "Project Due 1 !!", "Must submit project on time!", 10001, "2022-03-11", 50000);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 2 !!", "Must submit project on time!", 10001, "2022-03-11",  50001);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 3 !!", "Must submit project on time!", 10001, "2022-03-11", 50002);
INSERT INTO announcement (title, content, posted_by_uid, posted_on, course_id) VALUES("Project Due 4 !!", "Must submit project on time!", 10001, "2022-03-11", 50003);

-- Group  Discussion Table
INSERT INTO discussion (discussion_id, title, content, posted_by_uid, posted_on, group_id) VALUES(2200000, "Too much Stress 1", "When will this course end -_-", 10003, "2022-03-11", 70000);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Too much Stress 2", "When will this course end -_-", 10004, "2022-03-11", 70001);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Too much Stress 3", "When will this course end -_-", 10001, "2022-03-11", 70000);
INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id) VALUES("Too much Stress 4", "When will this course end -_-", 10002, "2022-03-11", 70001);

-- Group  Comment Table
INSERT INTO comment (comment_id, content, posted_by_uid, posted_on, discussion_id) VALUES(3300000, "No Comment 1", 10003, "2022-03-11", 2200000);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 2", 10004, "2022-03-11", 2200001);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 3", 10001, "2022-03-11", 2200002);
INSERT INTO comment (content, posted_by_uid, posted_on, discussion_id) VALUES("No Comment 4", 10002, "2022-03-11", 2200003);

-- Group  Files Table
INSERT INTO files (file_id, file_name, file_type, file_location, uploaded_by_uid, uploaded_on, discussion_id) VALUES(4400000, "random list","txt", "./CGA/files", 10002, "2022-03-11", 2200000);
INSERT INTO files (file_name, file_type, file_location, uploaded_by_uid, uploaded_on, discussion_id) VALUES("another list","txt", "./CGA/files", 10003, "2022-03-11", 2200001);