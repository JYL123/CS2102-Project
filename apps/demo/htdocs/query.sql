-- Simple sql query
--select
SELECT icnum FROM administrators where username = '$_POST[username]' and userpassword = '$_POST[userpassword]';
SELECT username FROM users WHERE icnum = '$_POST[icnum]';
SELECT adid FROM advertisements ORDER BY adid DESC LIMIT 1;
SELECT * FROM advertisements WHERE EXISTS (SELECT 1 FROM advertise WHERE advertisements.adid = advertise.adid);
SELECT * FROM bid WHERE adid = $_POST[adid] AND icnum = '$_POST[icnum]';
SELECT icnum FROM users where username = '$_POST[username]' and userpassword = '$_POST[userpassword]';

-- insert
INSERT INTO users VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]',  '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]';
INSERT INTO administrators (username, userpassword, icnum, firstname, lastname, email, phonenum) VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]', '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]');
INSERT INTO cars (platenum, models, numseats) VALUES ('$_POST[platenum]', '$_POST[models]', '$_POST[numseats]');
INSERT INTO advertisements (origin, destination, doa) VALUES ('$_POST[origin]', '$_POST[destination]', '$_POST[doa]');
INSERT INTO advertise (icnum, adid) VALUES ('$_POST[icnum]','$row[adid]');
INSERT INTO bid (adid, icnum, bidpoints) VALUES ($_POST[adid],'$_POST[icnum]', 1);
INSERT INTO users (username, userpassword, icnum, firstname, lastname, email, phonenum) VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]', '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]');

-- delete
DELETE FROM users WHERE icnum = '$_POST[icnum]';


-- For displaying bid points of each advertisement. This is to be viewed by administrator.
SELECT DISTINCT * 
FROM (
	SELECT adid, count(bidpoints) as points
	FROM bid
    GROUP BY adid
) AS combined natural join advertisements
ORDER BY points DESC;

-- For displaying ads that are expired. The ads which were posted 14 dayds ago is expired. 
SELECT DISTINCT * 
FROM (
	SELECT adid, count(bidpoints) as points
	FROM bid
    GROUP BY adid
) AS combined natural join advertisements
WHERE CURRENT_TIMESTAMP - doa > '14' ;

-- Most popular ad of the week
SELECT DISTINCT * 
FROM (
	SELECT adid, count(bidpoints) as points
	FROM bid
    GROUP BY adid
) AS combined natural join advertisements
WHERE CURRENT_TIMESTAMP - doa <= '7' 
ORDER BY points DESC
LIMIT 1;