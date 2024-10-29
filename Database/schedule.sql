INSERT INTO schedule (dentist_id, available_date, available_time, availability_status)
SELECT dentist_id, available_date, available_time, availability_status
FROM (
WITH RECURSIVE seq AS (
    SELECT 0 AS seq
    UNION ALL
    SELECT seq + 1
    FROM seq
    WHERE seq + 1 < 90  -- Generate numbers from 0 to 89 (90 days)
),
times AS (
    SELECT '09:00:00' AS available_time
    UNION ALL
    SELECT DATE_FORMAT(ADDTIME(available_time, '00:30:00'), '%H:%i:%s')
    FROM times
    WHERE available_time < '17:30:00'  -- Generate times from 09:00 to 17:30
)
SELECT dentist_id, CURDATE() + INTERVAL seq DAY AS available_date, times.available_time, TRUE AS availability_status
FROM dentists
CROSS JOIN seq
CROSS JOIN times
WHERE DAYOFWEEK(CURDATE() + INTERVAL seq DAY) NOT IN (1, 7)  -- Exclude weekends
AND CURDATE() + INTERVAL seq DAY <= CURDATE() + INTERVAL 90 DAY  -- Limit to 90 days
AND NOT EXISTS (
    SELECT 1 
    FROM schedule 
    WHERE schedule.dentist_id = dentists.dentist_id 
    AND schedule.available_date = CURDATE() + INTERVAL seq DAY 
    AND schedule.available_time = times.available_time
)
ORDER BY available_date, dentist_id, available_time
) AS generated_schedule;
