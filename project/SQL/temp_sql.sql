SELECT *
FROM users as u
    JOIN student as st ON st.user_id = u.user_id
    JOIN user_course_section as ucs ON ucs.user_id = u.user_id
    JOIN course as c ON c.course_id = ucs.course_id
    JOIN section as s ON s.section_id = ucs.section_id
ORDER BY u.user_id ASC;
SELECT d.*,
    u.username,
    g.group_name,
    c.course_name
FROM discussion as d
    JOIN users as u ON d.posted_by_uid = u.user_id
    JOIN student_group as g ON g.group_id = d.group_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
ORDER BY discussion_id ASC;
SELECT d.*,
    u.*,
    c.course_name,
    g.group_name
FROM discussion as d
    JOIN student_group as g ON g.group_id = d.group_id
    JOIN users as u ON u.user_id = d.posted_by_uid
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
    JOIN users as us ON us.user_id = ucs.user_id
WHERE us.user_id = 10001
ORDER BY d.discussion_id ASC
LIMIT 10;
SELECT *
FROM student_group as g
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id;
SELECT *
FROM student_group as g
    JOIN student as s ON g.group_leader_sid = s.student_id
    JOIN users as u ON s.user_id = u.user_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
ORDER BY g.group_id ASC;
SELECT g.*,
    u.*,
    s.section_name,
    c.course_name
FROM student_group as g
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
    LEFT JOIN section as s ON s.section_id = ucs.section_id
    JOIN users as u ON u.user_id = ucs.user_id
ORDER BY g.group_id ASC;
SELECT *
FROM users as u
    JOIN user_course_section as ucs ON ucs.user_id = u.user_id
    JOIN course as c ON c.course_id = ucs.course_id
    LEFT JOIN section as s ON s.section_id = ucs.section_id
ORDER BY u.user_id ASC;
SELECT *
FROM users as u
    JOIN student as st ON st.user_id = u.user_id
    JOIN member_of_group as mg ON mg.student_id = st.student_id
    JOIN student_group as g ON g.group_id = mg.group_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN user_course_section as ucs ON ucs.user_id = u.user_id
    JOIN course as c ON c.course_id = ucs.course_id
    JOIN section as s ON s.section_id = ucs.section_id
ORDER BY u.user_id ASC;
SELECT *
FROM announcement as a
    JOIN users as u ON a.posted_by_uid = u.user_id
    JOIN course as c ON c.course_id = a.course_id
ORDER BY a.announcement_id;
SELECT *
FROM student_group as g
    JOIN member_of_group as mg ON mg.group_id = g.group_id
    JOIN student as st ON st.student_id = mg.student_id
    JOIN users as u ON u.user_id = st.user_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN section as s ON s.course_id = c.course_id
    JOIN user_course_section as ucs ON ucs.section_id = s.section_id
    AND ucs.user_id = u.user_id
ORDER BY g.group_id ASC;
SELECT d.*,
    u.*,
    c.*,
    g.*
FROM discussion as d
    JOIN student_group as g ON g.group_id = d.group_id
    JOIN member_of_group as mg ON mg.group_id = g.group_id
    JOIN student as st ON st.student_id = mg.student_id
    JOIN users as u ON u.user_id = d.posted_by_uid
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN users as us ON us.user_id = st.user_id;
SELECT d.*,
    u.*,
    c.*,
    g.*
FROM discussion as d
    JOIN student_group as g ON g.group_id = d.group_id
    JOIN member_of_group as mg ON mg.group_id = g.group_id
    JOIN student as st ON st.student_id = mg.student_id
    JOIN users as u ON u.user_id = d.posted_by_uid
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN users as us ON us.user_id = st.user_id
WHERE us.user_id = 10007
ORDER BY d.discussion_id
LIMIT 10;
SELECT *
FROM student_group as g
    JOIN member_of_group as mg ON mg.group_id = g.group_id
    JOIN student as st ON st.student_id = mg.student_id
    JOIN users as u ON u.user_id = st.user_id;
SELECT *
FROM discussion as d
    JOIN student_group as g ON g.group_id = d.group_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN users as u ON u.user_id = d.posted_by_uid
ORDER BY d.discussion_id;
SELECT *
FROM comment as c
    JOIN discussion as d ON d.discussion_id = c.discussion_id
    JOIN users as u ON u.user_id = c.posted_by_uid
ORDER BY c.comment_id;
SELECT *
FROM course as c
    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
    JOIN users as u ON u.user_id = ucs.user_id
ORDER BY u.user_id ASC;
SELECT *
FROM course as c
    JOIN section as s ON s.course_id = c.course_id
    JOIN user_course_section as ucs ON ucs.section_id = s.section_id
    JOIN users as u ON u.user_id = ucs.user_id
ORDER BY u.user_id ASC;
SELECT *
FROM forum as f
    JOIN course as c ON c.course_id = f.course_id
    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
    JOIN users as u ON u.user_id = f.posted_by_uid
    JOIN users as us ON us.user_id = ucs.user_id
WHERE us.user_id = 10007
ORDER BY f.forum_id
LIMIT 10;

SELECT * FROM member_of_group as mg
JOIN student_group as g ON g.group_id = mg.group_id
JOIN group_of_course as gc ON gc.group_id = g.group_id
JOIN course as c ON c.course_id = gc.course_id
JOIN student as st ON st.student_id = mg.student_id
JOIN users as u ON u.user_id = st.user_id
WHERE u.user_id = 10007;

SELECT g.*, c.*, st.*, u.* FROM student_group as g
    JOIN member_of_group as mg ON mg.group_id = g.group_id
    JOIN student as st ON st.student_id = mg.student_id
    JOIN users as u ON u.user_id = st.user_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN users as us ON us.user_id = '$user_id'
    ORDER BY g.group_id ASC;
    
    
SELECT t.*, c.*, f.*, s.*, u.*, g.* FROM task as t
    JOIN course as c ON c.course_id = t.course_id
    JOIN group_of_course as gc ON gc.course_id = c.course_id
    JOIN student_group as g ON g.group_id = gc.group_id
	JOIN user_course_section as ucs ON ucs.course_id = c.course_id
	JOIN users as us ON us.user_id = ucs.user_id
    LEFT JOIN solution as s ON s.task_id = t.task_id
	LEFT JOIN files as f ON f.file_id = s.file_id
    LEFT JOIN users as u ON u.user_id = f.uploaded_by_uid
    WHERE us.user_id = 10002 AND c.course_id = 50001
    ORDER BY t.task_id ASC;