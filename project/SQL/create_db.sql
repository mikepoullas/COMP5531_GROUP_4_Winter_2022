create database cga;
use cga;

/*

10000 - user
20000 - student
30000 - ta
40000 - professor
50000 - course
60000 - section
70000 - groups
80000 - task
90000 - solution

1100000 - announcement
2200000 - forum
3300000 - reply
4400000 - discussion
5500000 - comment
6600000 - files

*/

CREATE TABLE Roles
(
  role_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(30) NOT NULL UNIQUE,
  role_description VARCHAR(255) NOT NULL
);
ALTER TABLE Roles AUTO_INCREMENT = 1;


CREATE TABLE Users
(
  user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(30) NOT NULL,
  last_name VARCHAR(30) NOT NULL,
  dob DATE NOT NULL,
  email VARCHAR(30) NOT NULL UNIQUE,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(30) NOT NULL,
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  first_login TINYINT NOT NULL,
  role_id INT NOT NULL,
  FOREIGN KEY (role_id) REFERENCES Roles(role_id)
);
ALTER TABLE Users AUTO_INCREMENT = 10000;


CREATE TABLE Student
(
  student_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
ALTER TABLE Student AUTO_INCREMENT = 20000;


CREATE TABLE TA
(
  ta_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
ALTER TABLE TA AUTO_INCREMENT = 30000;


CREATE TABLE Professor
(
  professor_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
ALTER TABLE Professor AUTO_INCREMENT = 40000;


CREATE TABLE Course
(
  course_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  course_name VARCHAR(30) NOT NULL UNIQUE,
  course_number INT NOT NULL UNIQUE
);
ALTER TABLE Course AUTO_INCREMENT = 50000;


CREATE TABLE Section
(
  section_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  section_name VARCHAR(30) NOT NULL,
  course_id INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);
ALTER TABLE Section AUTO_INCREMENT = 60000;


CREATE TABLE Student_Group
(
  group_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  group_name VARCHAR(30) NOT NULL,
  group_leader_sid INT NOT NULL
);
ALTER TABLE Student_Group AUTO_INCREMENT = 70000;


CREATE TABLE Files
(
  file_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  file_name VARCHAR(30) NOT NULL,
  file_content VARCHAR(255) NOT NULL,
  file_type VARCHAR(30) NOT NULL,
  file_size INT NOT NULL,
  uploaded_by_uid INT NOT NULL,
  uploaded_on DATETIME DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE Files AUTO_INCREMENT = 6600000;

CREATE TABLE Task
(
  task_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  task_type VARCHAR(30) NOT NULL,
  task_content VARCHAR(255) NOT NULL UNIQUE,
  task_deadline DATETIME NOT NULL,
  file_id INT NOT NULL,
  course_id INT NOT NULL,
  FOREIGN KEY (file_id) REFERENCES Files(file_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);
ALTER TABLE Task AUTO_INCREMENT = 80000;


CREATE TABLE Solution
(
  solution_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  solution_type VARCHAR(30) NOT NULL,
  solution_content VARCHAR(255) NOT NULL UNIQUE,
  task_id INT NOT NULL,
  file_id INT NOT NULL,
  FOREIGN KEY (task_id) REFERENCES Task(task_id),
  FOREIGN KEY (file_id) REFERENCES Files(file_id)
);
ALTER TABLE Solution AUTO_INCREMENT = 90000;

CREATE TABLE Grades
(
  grade_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  grade INT NOT NULL,
  student_id INT NOT NULL,
  solution_id INT NOT NULL,
  FOREIGN KEY (student_id) REFERENCES Student(student_id),
  FOREIGN KEY (solution_id) REFERENCES Solution(solution_id)
);
ALTER TABLE Grades AUTO_INCREMENT = 7700000;

CREATE TABLE Announcement
(
  announcement_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  announcement_title VARCHAR(255) NOT NULL,
  announcement_content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  course_id INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);
ALTER TABLE Announcement AUTO_INCREMENT = 1100000;

CREATE TABLE Forum
(
  forum_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  forum_title VARCHAR(30) NOT NULL,
  forum_content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  course_id INT NOT NULL,
  file_id INT,
  FOREIGN KEY (course_id) REFERENCES Course(course_id),
  FOREIGN KEY (file_id) REFERENCES Files(file_id)
);
ALTER TABLE Forum AUTO_INCREMENT = 2200000;


CREATE TABLE Reply
(
  reply_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  reply_content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  forum_id INT NOT NULL,
  FOREIGN KEY (forum_id) REFERENCES Forum(forum_id) ON DELETE CASCADE 
);
ALTER TABLE Reply AUTO_INCREMENT = 3300000;


CREATE TABLE Discussion
(
  discussion_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  discussion_title VARCHAR(30) NOT NULL,
  discussion_content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  group_id INT,
  task_id INT,
  file_id INT,
  FOREIGN KEY (group_id) REFERENCES Student_Group(group_id),
  FOREIGN KEY (task_id) REFERENCES Task(task_id),
  FOREIGN KEY (file_id) REFERENCES Files(file_id)
);
ALTER TABLE Discussion AUTO_INCREMENT = 4400000;


CREATE TABLE Comment
(
  comment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  comment_content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  discussion_id INT NOT NULL,
  FOREIGN KEY (discussion_id) REFERENCES Discussion(discussion_id) ON DELETE CASCADE
);
ALTER TABLE Comment AUTO_INCREMENT = 5500000;

-- ------------------------------------------------------------------

CREATE TABLE Member_of_Group
(
  student_id INT NOT NULL,
  group_id INT NOT NULL,
  FOREIGN KEY (student_id) REFERENCES Student(student_id),
  FOREIGN KEY (group_id) REFERENCES Student_Group(group_id)
);


CREATE TABLE Group_of_Course
(
  group_id INT NOT NULL,
  course_id INT NOT NULL,
  FOREIGN KEY (group_id) REFERENCES Student_Group(group_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

CREATE TABLE User_Course_Section
(
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  section_id INT,
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id),
  FOREIGN KEY (section_id) REFERENCES Section(section_id),
  UNIQUE (user_id, section_id)
);