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
80000 - assignment
90000 - project

1100000 - announcement
2200000 - discussion
3300000 - comment
4400000 - files

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

CREATE TABLE Course_Assignment
(
  assignment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  content VARCHAR(255) NOT NULL,
  course_id INT NOT NULL,
  file_id INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES Course(course_id),
  FOREIGN KEY (file_id) REFERENCES Files(file_id)
);

CREATE TABLE Course_Project
(
  project_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  content VARCHAR(255) NOT NULL,
  course_id INT NOT NULL,
  file_id INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES Course(course_id),
  FOREIGN KEY (file_id) REFERENCES Files(file_id)
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
