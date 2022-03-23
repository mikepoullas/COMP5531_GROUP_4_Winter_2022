create database cga;
use cga;

/*

10000 - user
20000 - student
30000 - ta
40000 - prof
50000 - course
60000 - section
70000 - groups

1100000 - discussion
2200000 - announcement
3300000 - comment
4400000 - files

*/

CREATE TABLE Roles
(
  role_id INT NOT NULL AUTO_INCREMENT,
  role_name VARCHAR(30) NOT NULL,
  role_description VARCHAR(255) NOT NULL,
  PRIMARY KEY (role_id),
  UNIQUE (role_name)
);

CREATE TABLE Course
(
  course_id INT NOT NULL AUTO_INCREMENT,
  course_name VARCHAR(30) NOT NULL,
  course_number INT NOT NULL UNIQUE,
  PRIMARY KEY (course_id),
  UNIQUE (course_name),
  UNIQUE (course_number)
);

CREATE TABLE Course_Section
(
  section_id INT NOT NULL AUTO_INCREMENT,
  section_name VARCHAR(30) NOT NULL,
  course_id INT NOT NULL,
  group_id INT NOT NULL,
  PRIMARY KEY (section_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id),
  FOREIGN KEY (group_id) REFERENCES Student_groups(group_id),
  UNIQUE (section_name)
);

CREATE TABLE Announcement
(
  announcement_id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  posted_by_uid INT NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  content VARCHAR(1024) NOT NULL,
  section_id INT NOT NULL,
  PRIMARY KEY (announcement_id),
  FOREIGN KEY (section_id) REFERENCES Course_Section(section_id)
);

CREATE TABLE Student_groups
(
  group_id INT NOT NULL AUTO_INCREMENT,
  group_name VARCHAR(30) NOT NULL,
  group_leader_sid INT,
  PRIMARY KEY (group_id),
  UNIQUE (group_name)
);

CREATE TABLE member_of_group
(
  student_id INT NOT NULL,
  group_id INT NOT NULL,
  FOREIGN KEY (student_id) REFERENCES Student(student_id),
  FOREIGN KEY (group_id) REFERENCES Student_groups(group_id),
  UNIQUE (group_id)
);

CREATE TABLE section_groups
(
  section_id INT NOT NULL,
  group_id INT NOT NULL,
  FOREIGN KEY (section_id) REFERENCES Course_Section(section_id),
  FOREIGN KEY (group_id) REFERENCES Student_groups(group_id),
  UNIQUE (group_id)
);

CREATE TABLE User_Course
(
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id),
  UNIQUE (user_id, course_id)
);

CREATE TABLE Group_assignment
(
  group_asn_id INT NOT NULL AUTO_INCREMENT,
  details VARCHAR(255) NOT NULL,
  group_id INT NOT NULL,
  PRIMARY KEY (group_asn_id),
  FOREIGN KEY (group_id) REFERENCES Student_groups(group_id)
);

CREATE TABLE Group_project
(
  group_proj_id INT NOT NULL AUTO_INCREMENT,
  details VARCHAR(255) NOT NULL,
  group_id INT NOT NULL,
  PRIMARY KEY (group_proj_id),
  FOREIGN KEY (group_id) REFERENCES Student_groups(group_id)
);

CREATE TABLE Discussion
(
  discussion_id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(30) NOT NULL,
  content VARCHAR(1024) NOT NULL,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  posted_by_uid INT NOT NULL,
  group_id INT NOT NULL,
  PRIMARY KEY (discussion_id),
  FOREIGN KEY (group_id) REFERENCES Student_groups(group_id)
);

CREATE TABLE Files
(
  file_id INT NOT NULL AUTO_INCREMENT,
  permission VARCHAR(30) NOT NULL,
  uploaded_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  file_type VARCHAR(30) NOT NULL,
  uploaded_by_uid INT NOT NULL,
  file_location VARCHAR(255) NOT NULL,
  discussion_id INT NOT NULL,
  PRIMARY KEY (file_id),
  FOREIGN KEY (discussion_id) REFERENCES Discussion(discussion_id)
);

CREATE TABLE Comment
(
  comment_id INT NOT NULL AUTO_INCREMENT,
  posted_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  posted_by_uid INT NOT NULL,
  content VARCHAR(1024) NOT NULL,
  discussion_id INT NOT NULL,
  PRIMARY KEY (comment_id),
  FOREIGN KEY (discussion_id) REFERENCES Discussion(discussion_id)
);

CREATE TABLE Users
(
  user_id INT NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(30) NOT NULL,
  last_name VARCHAR(30) NOT NULL,
  dob DATE NOT NULL,
  email VARCHAR(30) NOT NULL,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(30) NOT NULL,
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  first_login TINYINT NOT NULL,
  role_id INT NOT NULL,
  PRIMARY KEY (user_id),
  FOREIGN KEY (role_id) REFERENCES Roles(role_id),
  UNIQUE (email),
  UNIQUE (username)
);

CREATE TABLE Student
(
  student_id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  PRIMARY KEY (student_id),
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Professor
(
  professor_id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  PRIMARY KEY (professor_id),
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE TA
(
  ta_id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  PRIMARY KEY (ta_id),
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);