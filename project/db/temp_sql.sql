-- All USER
SELECT * FROM users as u
JOIN user_course as uc
ON u.user_id = uc. user_id
JOIN course as c
ON uc.course_id = c.course_id
JOIN course_section as cs
ON c.course_id = cs.course_id
-- WHERE u.user_id = 10003
ORDER BY u.user_id ASC;

-- STUDENT
SELECT * FROM users as u
JOIN student as s
ON s.user_id = u.user_id
JOIN member_of_group as mg
ON s.student_id = mg.student_id
JOIN student_groups as g
ON g.group_id = mg.group_id
JOIN section_groups as sg
ON sg.group_id = g.group_id
JOIN course_section as cs
ON cs.section_id = sg.section_id
JOIN course as c
ON c.course_id = cs.course_id
-- WHERE u.user_id = 10003
ORDER BY u.user_id ASC;

-- PROFESSOR
SELECT * FROM users as u
JOIN professor as p
ON p.user_id = u.user_id
JOIN user_course as uc
ON u.user_id = uc. user_id
JOIN course as c
ON uc.course_id = c.course_id
JOIN course_section as cs
ON c.course_id = cs.course_id
-- WHERE u.user_id = 10001
ORDER BY u.user_id ASC;

-- TA
SELECT * FROM users as u
JOIN ta as t
ON t.user_id = u.user_id
JOIN user_course as uc
ON u.user_id = uc. user_id
JOIN course as c
ON uc.course_id = c.course_id
JOIN course_section as cs
ON c.course_id = cs.course_id
-- WHERE u.user_id = 10002
ORDER BY u.user_id ASC;

-- ANNOUNCEMENTS
SELECT *
FROM announcement as a
JOIN users as u
ON a.posted_by_uid = u.user_id
JOIN course as c
ON c.course_id = a.course_id
ORDER BY a.announcement_id DESC;

-- SELECT * from roles;
-- SELECT * from users;
-- SELECT * from course;
-- SELECT * from student;
-- SELECT * from ta;
-- SELECT * from professor;
-- SELECT * from user_course;
-- SELECT * from student_groups;
-- SELECT * from member_of_group;
-- SELECT * from course_section;
-- SELECT * from section_groups;
-- SELECT * from announcement;

-- SELECT * from user_course as uc
-- JOIN course as c ON uc.course_id = c.course_id
-- JOIN users as u ON uc.user_id = u.user_id
-- WHERE u.role_id = 4;

-- SELECT * FROM users as u
-- JOIN announcement as a ON u.user_id = a.posted_by_uid;

-- DELETE from announcement
-- WHERE announcement_id = 2200000;

-- DELETE from course_section
-- WHERE section_id = 60000;

-- SHOW COLUMNS FROM users WHERE field = 'user_id';