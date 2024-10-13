INSERT INTO dentists (dentist_name, dentist_email, dentist_password) 
VALUES 
('Dr Eunice Seng', 'euniceseng@example.com', 'hashed_password1'), 
('Dr Thong Peiyu', 'thongpeiyu@example.com', 'hashed_password2'), 
('Dr Ali Abu bin Akau', 'aliabu@example.com', 'hashed_password3');

INSERT INTO services (service_type) 
VALUES 
('General Dentistry'),
('Dental Braces',),
('Scaling and Polishing');

INSERT INTO schedule (dentist_id, available_date, available_time, availability_status)
SELECT dentist_id, CURDATE() + INTERVAL seq DAY AS available_date, times.available_time, TRUE
FROM dentists
CROSS JOIN (
    SELECT @row := @row + 1 AS seq 
    FROM (
        SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
        SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL 
        SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL 
        SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14
    ) AS numbers,
    (SELECT @row := -1) AS init
) AS seq
CROSS JOIN (
    SELECT '09:00:00' AS available_time UNION ALL
    SELECT '10:00:00' UNION ALL
    SELECT '11:00:00' UNION ALL
    SELECT '12:00:00' UNION ALL
    SELECT '13:00:00' UNION ALL
    SELECT '14:00:00' UNION ALL
    SELECT '15:00:00' UNION ALL
    SELECT '16:00:00' UNION ALL
    SELECT '17:00:00'  -- This includes 17:00 (5:00 PM)
) AS times
WHERE DAYOFWEEK(CURDATE() + INTERVAL seq DAY) NOT IN (1, 7)  -- Exclude weekends
AND CURDATE() + INTERVAL seq DAY <= CURDATE() + INTERVAL 14 DAY; -- Limit to two weeks


CREATE EVENT insert_schedule_daily
ON SCHEDULE EVERY 1 DAY
DO
    INSERT INTO schedule (dentist_id, available_date, available_time, availability_status)
    SELECT dentist_id, CURDATE() + INTERVAL 14 DAY, available_time, TRUE
    FROM dentists
    CROSS JOIN (
        SELECT '09:00:00' AS available_time UNION ALL
        SELECT '10:00:00' UNION ALL
        SELECT '11:00:00' UNION ALL
        SELECT '12:00:00' UNION ALL
        SELECT '13:00:00' UNION ALL
        SELECT '14:00:00' UNION ALL
        SELECT '15:00:00' UNION ALL
        SELECT '16:00:00' UNION ALL
        SELECT '17:00:00'
    ) AS times
    WHERE DAYOFWEEK(CURDATE() + INTERVAL 14 DAY) NOT IN (1, 7);

