SELECT COUNT(activity_type) AS all FROM locations


SELECT COUNT(activity_type) AS vehicles FROM locations WHERE activity_type LIKE '%VEHICLE%' OR LIKE '%BUS%' OR LIKE '%CAR%';
SELECT COUNT(activity_type) AS unknowns FROM locations WHERE activity_type LIKE '%UNKNOWN%'; 

SELECT userid, COUNT(*) AS `entries` FROM locations GROUP BY userid;

SELECT FROM_UNIXTIME(timestamp/1000) FROM locations;

SELECT MONTH('2017/08/25') AS Month;
SELECT DAY('2017/08/25') AS DayOfMonth;
SELECT YEAR('2017/08/25') AS Year;
