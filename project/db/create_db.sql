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
80000 - submission
90000 - grades

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

CREATE TABLE Student
(
  student_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE TA
(
  ta_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Professor
(
  professor_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Course
(
  course_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  course_name VARCHAR(30) NOT NULL UNIQUE,
  course_number INT NOT NULL UNIQUE
);

CREATE TABLE Section
(
  section_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  section_name VARCHAR(30) NOT NULL,
  course_id INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

CREATE TABLE Student_Group
(
  group_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  group_name VARCHAR(30) NOT NULL,
  group_leader_sid INT NOT NULL
);

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

CREATE TABLE Files
(
  file_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  file_name VARCHAR(30) NOT NULL,
  content VARCHAR(255) NOT NULL,
  type VARCHAR(30) NOT NULL,
  size INT NOT NULL,
  uploaded_by_uid INT NOT NULL,
  uploaded_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  downloads INT NOT NULL
);

CREATE TABLE Announcement
(
  announcement_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  course_id INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

CREATE TABLE Forum
(
  forum_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title INT NOT NULL,
  content VARCHAR(1024) NOT NULL,
  posted_by_uid VARCHAR(30) NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  file_id INT NOT NULL,
  course_id INT NOT NULL,
  FOREIGN KEY (file_id) REFERENCES Files(file_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

CREATE TABLE Reply
(
  reply_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  forum_id INT NOT NULL,
  FOREIGN KEY (forum_id) REFERENCES Forum(forum_id) ON DELETE CASCADE 
);

CREATE TABLE Discussion
(
  discussion_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(30) NOT NULL,
  content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  group_id INT NOT NULL,
  file_id INT,
  FOREIGN KEY (group_id) REFERENCES Student_Group(group_id),
  FOREIGN KEY (file_id) REFERENCES Files(file_id)
);

CREATE TABLE Comment
(
  comment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  content VARCHAR(1024) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  discussion_id INT NOT NULL,
  FOREIGN KEY (discussion_id) REFERENCES Discussion(discussion_id) ON DELETE CASCADE 
);

CREATE TABLE Graded_Submission
(
  submission_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  content VARCHAR(255) NOT NULL,
  type VARCHAR(30) NOT NULL,
  deadline DATETIME NOT NULL,
  file_id INT NOT NULL,
  course_id INT NOT NULL,
  discussion_id INT,
  FOREIGN KEY (file_id) REFERENCES Files(file_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id),
  FOREIGN KEY (discussion_id) REFERENCES Discussion(discussion_id)
);

CREATE TABLE Student_Grades
(
  grade_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  grade INT NOT NULL ,
  submission_id INT NOT NULL,
  student_id INT NOT NULL,
  FOREIGN KEY (submission_id) REFERENCES Graded_Submission(submission_id),
  FOREIGN KEY (student_id) REFERENCES Student(student_id)
);

-- SET AI INDEX
ALTER TABLE roles AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 10000;
ALTER TABLE student AUTO_INCREMENT = 20000;
ALTER TABLE ta AUTO_INCREMENT = 30000;
ALTER TABLE professor AUTO_INCREMENT = 40000;
ALTER TABLE course AUTO_INCREMENT = 50000;
ALTER TABLE section AUTO_INCREMENT = 60000;
ALTER TABLE Student_Group AUTO_INCREMENT = 70000;
ALTER TABLE Graded_Submission AUTO_INCREMENT = 80000;
ALTER TABLE Announcement AUTO_INCREMENT = 1100000;
ALTER TABLE Forum AUTO_INCREMENT = 2200000;
ALTER TABLE Reply AUTO_INCREMENT = 3300000;
ALTER TABLE Discussion AUTO_INCREMENT = 4400000;
ALTER TABLE Comment AUTO_INCREMENT = 5500000;
ALTER TABLE Files AUTO_INCREMENT = 6600000;