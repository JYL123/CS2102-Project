-- Simple sql query
--select
SELECT icnum FROM administrators where username = '$_POST[username]' and userpassword = '$_POST[userpassword]';
SELECT username FROM users WHERE icnum = '$_POST[icnum]';
SELECT adid FROM advertisements ORDER BY adid DESC LIMIT 1;
SELECT * FROM advertisements WHERE EXISTS (SELECT 1 FROM advertise WHERE advertisements.adid = advertise.adid);
SELECT * FROM bid WHERE adid = $_POST[adid] AND icnum = '$_POST[icnum]';
SELECT icnum FROM users where username = '$_POST[username]' and userpassword = '$_POST[userpassword]';
SELECT b.adid, a.origin, a.destination, a.doa, at.icnum, bidpoints, status
				FROM bid b, advertisements a, advertise at
				WHERE status = 'Not Selected' AND b.adid = a.adid AND b.adid = at.adid AND at.icnum = '$_SESSION[icnum]'
				ORDER BY b.adid;

SELECT * , --Find ads eligible to bid
(SELECT max(bidpoints) AS maxBid FROM bid GROUP BY adid HAVING adid = a.adid),
(SELECT bidpoints AS yourBid FROM bid WHERE icnum='A1111111A' AND adid = a.adid)
FROM advertisements a
WHERE NOT EXISTS (
	SELECT 1 FROM bid b
	WHERE b.adid = a.adid
	AND b.status = 'Selected'
);

BEGIN;
INSERT INTO advertisements (origin, destination, doa) VALUES ('$_POST[origin]', '$_POST[destination]', '$_POST[doa]');
INSERT INTO advertise (icnum, adid) SELECT '$_SESSION[icnum]', adid FROM advertisements ORDER BY adid DESC LIMIT 1;
COMMIT;

-- insert
INSERT INTO administrators (username, userpassword, icnum, firstname, lastname, email, phonenum) VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]', '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]');
INSERT INTO cars (platenum, models, numseats) VALUES ('$_POST[platenum]', '$_POST[models]', '$_POST[numseats]');
INSERT INTO advertisements (origin, destination, doa) VALUES ('$_POST[origin]', '$_POST[destination]', '$_POST[doa]');
INSERT INTO advertise (icnum, adid) VALUES ('$_POST[icnum]','$row[adid]');
INSERT INTO bid (adid, icnum, bidpoints) VALUES ($_POST[adid],'$_POST[icnum]', 1);
INSERT INTO users (username, userpassword, icnum, firstname, lastname, email, phonenum) VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]', '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]');
INSERT INTO drive(plateNum, ICNum) VALUES ('$_POST[platenum]', '$_POST[icnum]');

-- delete
DELETE FROM users WHERE icnum = '$_POST[icnum]';
DELETE FROM drive WHERE plateNum = '$_POST[platenum]' and icnum='$_POST[icnum]';
DELETE FROM advertise WHERE adid='$_POST[adid]' and icnum='$_POST[icnum]';
DELETE FROM bid WHERE adid='_POST[adid]' and icnum='$_POST[icnum]';
DELETE FROM cars WHERE plateNum='$_POST[platenum]';
DELETE FROM advertisements WHERE adid='$_POST[adid]';



-- update
UPDATE bid SET status = 'Selected' WHERE icnum = '$_SESSION[icnum]' and adid = $row[adid]


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

-- Functions

	--update bid points
CREATE OR REPLACE FUNCTION incBid(IC VARCHAR, ad INTEGER, val NUMERIC)
RETURNS BOOLEAN AS
'BEGIN
UPDATE bid
SET bidPoints=bidPoints+val
WHERE ICNUM=IC and adID=ad;
RETURN TRUE;
END;'
LANGUAGE PLPGSQL;

	--update bid status
CREATE OR REPLACE FUNCTION selBidder(IC VARCHAR)
RETURNS BOOLEAN AS $$
BEGIN
UPDATE bid
SET status='Selected'
WHERE icnum=IC;
RETURN TRUE;
END; $$
LANGUAGE PLPGSQL;

-- Triggers
	--delete trigger
CREATE OR REPLACE FUNCTION delete()
RETURNS TRIGGER AS $$
BEGIN
RAISE NOTICE 'An user is deleted';
RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

CREATE TRIGGER deleteTrigger
BEFORE DELETE
ON users
FOR EACH STATEMENT
EXECUTE PROCEDURE delete();

	--update trigger
CREATE OR REPLACE FUNCTION update()
RETURNS TRIGGER AS $$
BEGIN
RAISE NOTICE 'Bid status is updated';
RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

CREATE TRIGGER updateTrigger
BEFORE UPDATE
ON bid
FOR EACH STATEMENT
EXECUTE PROCEDURE update();

	--insert car trigger
CREATE OR REPLACE FUNCTION insertCars()
RETURNS TRIGGER AS $$
BEGIN
RAISE NOTICE 'A car is inserted into database';
RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

CREATE TRIGGER insertCarsTrigger
BEFORE UPDATE
ON cars
FOR EACH STATEMENT
EXECUTE PROCEDURE insertCars();

	-- insert drive trigger
CREATE OR REPLACE FUNCTION insertDrive()
RETURNS TRIGGER AS $$
BEGIN
RAISE NOTICE 'A driver is added';
RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

CREATE TRIGGER insertDriveTrigger
BEFORE UPDATE
ON drive
FOR EACH STATEMENT
EXECUTE PROCEDURE insertDrive();
