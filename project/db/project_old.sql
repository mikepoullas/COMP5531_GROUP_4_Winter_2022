CREATE TABLE Roles
(
  role_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  role_name VARCHAR(15) NOT NULL,
  role_description VARCHAR(100) NOT NULL,
  PRIMARY KEY (role_id)
);

CREATE TABLE User
(
  user_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  role_id SMALLINT UNSIGNED NOT NULL,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(25) NOT NULL,
  first_name VARCHAR(25) NOT NULL,
  last_name VARCHAR(30) NOT NULL,
  dob DATE NOT NULL,
  email VARCHAR(50) NOT NULL,
  is_first_login TINYINT NOT NULL,
  PRIMARY KEY (user_id),
  FOREIGN KEY (role_id) REFERENCES Roles(role_id),
  UNIQUE (username)
);

CREATE TABLE Course
(
  course_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_name VARCHAR(25) NOT NULL,
  course_number MEDIUMINT UNSIGNED NOT NULL,
  max_groups SMALLINT UNSIGNED,
  max_members SMALLINT UNSIGNED,
  PRIMARY KEY (course_id)
);

CREATE TABLE Student
(
  student_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id MEDIUMINT UNSIGNED NOT NULL,
  course_id MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (student_id),
  FOREIGN KEY (user_id) REFERENCES User(user_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

CREATE TABLE Professor
(
  professor_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id MEDIUMINT UNSIGNED NOT NULL,
  course_id MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (professor_id),
  FOREIGN KEY (user_id) REFERENCES User(user_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

CREATE TABLE TA
(
  ta_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id MEDIUMINT UNSIGNED NOT NULL,
  course_id MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (ta_id),
  FOREIGN KEY (user_id) REFERENCES User(user_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

CREATE TABLE Course_Section
(
  section_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_id MEDIUMINT UNSIGNED NOT NULL,
  section_name VARCHAR(25) NOT NULL,
  PRIMARY KEY (section_id),
  FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

CREATE TABLE Student_Groups
(
  group_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  group_name VARCHAR(50) NOT NULL,
  group_leader_student_id MEDIUMINT UNSIGNED,
  project VARCHAR(256),
  assignment VARCHAR(256),
  PRIMARY KEY (group_id)
);

CREATE TABLE Is_a_member_of
(
  student_id MEDIUMINT UNSIGNED NOT NULL,
  group_id MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (student_id, group_id),
  FOREIGN KEY (student_id) REFERENCES Student(student_id),
  FOREIGN KEY (group_id) REFERENCES Student_Groups(group_id)
);

CREATE TABLE Announcement
(
  announcement_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  section_id SMALLINT UNSIGNED NOT NULL,
  posted_by_user_id MEDIUMINT UNSIGNED NOT NULL,
  posted_on DATE NOT NULL,
  content VARCHAR(1024) NOT NULL,
  PRIMARY KEY (announcement_id),
  FOREIGN KEY (section_id) REFERENCES Course_Section(section_id)
);

CREATE TABLE Discussion
(
  discussion_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  group_id MEDIUMINT UNSIGNED NOT NULL,
  title VARCHAR(100) NOT NULL,
  discussion_text VARCHAR(1024) NOT NULL,
  posted_on DATE NOT NULL,
  posted_by_user_id MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (discussion_id),
  FOREIGN KEY (group_id) REFERENCES Student_Groups(group_id)
);

CREATE TABLE Comment
(
  comment_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  discussion_id MEDIUMINT UNSIGNED NOT NULL,
  comment_text VARCHAR(1024) NOT NULL,
  posted_by_user_id MEDIUMINT UNSIGNED NOT NULL,
  posted_on DATE NOT NULL,
  PRIMARY KEY (comment_id),
  FOREIGN KEY (discussion_id) REFERENCES Discussion(discussion_id)
);

CREATE TABLE Files
(
  files_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  discussion_id MEDIUMINT UNSIGNED NOT NULL,
  uploaded_by_user_id MEDIUMINT UNSIGNED NOT NULL,
  file_location VARCHAR(256) NOT NULL,
  uploaded_on DATE NOT NULL,
  file_type INT NOT NULL,
  permission INT NOT NULL,
  PRIMARY KEY (files_id),
  FOREIGN KEY (discussion_id) REFERENCES Discussion(discussion_id)
);



