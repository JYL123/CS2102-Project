-- Simple sql query

------------- SELECT -------------

-- User login
SELECT icnum, firstName, lastName FROM users where username = '$_POST[username]' and userpassword = '$_POST[userpassword]';
-- Admin login
SELECT icnum, firstName, lastName FROM administrators where username = '$_POST[username]' and userpassword = '$_POST[userpassword]';
-- Retrive admin information
SELECT * FROM administrators WHERE icnum = '$_SESSION[icnum]';
-- Find total number of users in the system
SELECT count(icnum) FROM users;
-- Find total number of drivers in the system
SELECT count(DISTINCT icnum)
FROM drive NATURAL JOIN users;
-- Find total number of advertisements in the system
SELECT count(*) FROM advertisements;
-- Retrieve bidding status of advertisements the specific user has posted
-- (meaning all those he/she can select), thus the bid should also not yet be selected by other drivers
SELECT b.adid, b.icnum as BidderIC, a.origin, a.destination, a.doa, at.icnum, bidpoints, status
				FROM bid b, advertisements a, advertise at
				WHERE status = 'Not Selected' AND b.adid = a.adid AND b.adid = at.adid AND at.icnum = '$_SESSION[icnum]'
				ORDER BY b.adid;

-- Find ads eligible to bid, also show the user's bidpoint and the current max bidpoint
SELECT * ,
	(SELECT max(bidpoints) AS maxBid FROM bid GROUP BY adid HAVING adid = a.adid),
	(SELECT bidpoints AS yourBid FROM bid WHERE icnum='$_SESSION[icnum]' AND adid = a.adid)
FROM advertisements a
WHERE NOT EXISTS (
	SELECT 1 FROM bid b
	WHERE b.adid = a.adid
	AND icnum='$_SESSION[icnum]'
	AND b.status = 'Selected');

-- Get all cars of the current user
SELECT *
FROM cars
WHERE plateNum IN (
	SELECT plateNum FROM drive WHERE icnum = '$_SESSION[icnum]'
);

-- Get all advertisements posted by the current user;
SELECT DISTINCT uaa.adid, uaa.origin, uaa.destination, uaa.doa
FROM (
	(users u natural left join advertise a ) natural join advertisements
) as uaa
WHERE uaa.icnum = '$_SESSION[icnum]';

-- Get all ads that the user have bidded, show also the status whether he/she is selected
SELECT origin, destination, doa, bidpoints, status, u.lastname, u.firstname
FROM bid, advertisements a, users u
WHERE bid.adid = a.adid
AND driveric = u.icnum
AND bid.icnum = '$_SESSION[icnum]'


------------- INSERT -------------

-- User sign up
INSERT INTO users (username, userpassword, icnum, firstname, lastname, email, phonenum)
	VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]', '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]');
-- Admin sign up
INSERT INTO administrators (username, userpassword, icnum, firstname, lastname, email, phonenum)
	VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]', '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]');

-- Post an advertisement (Insert into advertisement and also advertise in one transection)
BEGIN;
INSERT INTO advertisements (origin, destination, doa) VALUES ('$_POST[origin]', '$_POST[destination]', '$_POST[doa]');
INSERT INTO advertise (icnum, adid)(
	SELECT '$_SESSION[icnum]', adid
	FROM advertisements
	ORDER BY adid
	DESC LIMIT 1
);
END;

-- Bidding an advertisement
-- Step 1: Check whether the user has already bidded for that specific advertisements
SELECT * FROM bid WHERE adid = $_POST[adid] AND icnum = '$_SESSION[icnum]';
-- Step 2:
-- IF not exists a record
INSERT INTO bid VALUES ('$_SESSION[icnum]', $_POST[adid], '$_POST[bidpoints]';
-- ELSE update points the existing one
UPDATE bid
SET bidpoints = '$_POST[bidpoints]'
WHERE icnum = '$_SESSION[icnum]'
AND adid = '$_POST[adid]';

-- Apply to be a driver;
BEGIN;
INSERT INTO cars (platenum, models, numseats) VALUES ('$_POST[platenum]', '$_POST[models]', '$_POST[numseats]');
INSERT INTO drive(platenum, icnum) VALUES ('$_POST[platenum]', '$_SESSION[icnum]');
END;


------------- DELETE -------------

-- Delete a user according to the icnum
DELETE FROM users WHERE icnum = '$_POST[icnum]';


DELETE FROM drive WHERE plateNum = '$_POST[platenum]' and icnum='$_POST[icnum]';
DELETE FROM cars WHERE plateNum='$_POST[platenum]';


------------- UPDATE -------------

-- When a driver selects a bidder, set the bid status to 'SELECTED'.
UPDATE bid
	SET status = 'Selected'
	WHERE icnum = '$_POST[icnum]'
	AND adid = '$_POST[adid]';

------------- COMPLEX QUERIES -------------

-- For displaying maximum bidpoint of each advertisement. This is to be viewed by administrator.
SELECT DISTINCT *
FROM (
		SELECT adid, max(bidpoints) as points
		FROM bid
		GROUP BY adid
) AS combined natural join advertisements
ORDER BY points DESC;

-- Show all expired ads (14 days), with its max bidpoint, in descending time order
SELECT DISTINCT *
FROM (
		SELECT adid, max(bidpoints) as points
		FROM bid
		GROUP BY adid
) AS combined natural join advertisements
WHERE CURRENT_TIMESTAMP - doa > '14 day'::interval
ORDER by doa DESC;

-- Top 10 popular ad of the week
SELECT DISTINCT *
FROM (
		SELECT adid, max(bidpoints) as points
		FROM bid
		GROUP BY adid
) AS combined natural join advertisements
WHERE CURRENT_TIMESTAMP - doa <= '7 day'::interval
ORDER BY points DESC
LIMIT 10;


------------- FUNCTIONS -------------

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
CREATE OR REPLACE FUNCTION selBidder(IC VARCHAR, ad INTEGER)
RETURNS BOOLEAN AS $$
BEGIN
UPDATE bid
SET status='Selected', driverIC=IC
WHERE adid=ad;
IF ((SELECT driveric FROM bid WHERE adid = ad) = IC) --Check whether the update is successful
THEN RETURN TRUE;
ELSE
RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

------------- TRIGGER -------------

-- Function and trigger to keep integrity constraint (bidpoint > 0)
CREATE OR REPLACE FUNCTION exception()
RETURNS TRIGGER AS $$
BEGIN
RAISE NOTICE 'invalid bidpoint';
RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

CREATE TRIGGER exception
BEFORE INSERT OR UPDATE
ON bid
FOR EACH STATEMENT
WHEN NEW.bidpoint < 0
EXECUTE PROCEDURE exception();

-- Prohibit driver from selecting multiple bids if the driver would not be able to
-- pick up the next driver on time. (Timeframe set to be 15 mins)
CREATE OR REPLACE FUNCTION checkDriverTime()
RETURNS TRIGGER AS $$
DECLARE t TIMESTAMP;
DECLARE ori VARCHAR(64);
DECLARE dest VARCHAR(64);
BEGIN
t := (SELECT doa FROM Advertisements WHERE adid = NEW.adid);
ori := (SELECT origin FROM Advertisements WHERE adid = NEW.adid);
dest := (SELECT destination FROM Advertisements WHERE adid = NEW.adid);
IF (SELECT count(*) FROM Bid NATURAL JOIN Advertisements
		WHERE driverIC = NEW.driveric
		AND origin <> ori
		AND destination <> dest
		AND ((t - doa < '15 minute'::interval AND t - doa >'-15 minute'::interval)
			OR
			(doa - t < '15 minute'::interval AND doa - t >'-15 minute'::interval))
		)
THEN
RAISE NOTICE 'Select bidder failed on checkDriverTime()!';
RETURN NULL;
ELSE
RAISE NOTICE 'Select bidder success!';
RETURN NEW;
END IF;
END; $$
LANGUAGE PLPGSQL;


CREATE TRIGGER driverTime
BEFORE UPDATE
ON bid
FOR EACH ROW
WHEN (NEW.status = 'Selected')
EXECUTE PROCEDURE checkDriverTime();


-- Prohibit user from bidding multiple bids of different routes in the same
-- 15 minutes timeframe (Rationale being they cannot change ride in such a short timeframe)
CREATE OR REPLACE FUNCTION checkBidderTime()
RETURNS TRIGGER AS $$
DECLARE t TIMESTAMP;
DECLARE ori VARCHAR(64);
DECLARE dest VARCHAR(64);
BEGIN
t := (SELECT doa FROM Advertisements WHERE adid = NEW.adid);
ori := (SELECT origin FROM Advertisements WHERE adid = NEW.adid);
dest := (SELECT destination FROM Advertisements WHERE adid = NEW.adid);

IF EXISTS(SELECT 1 FROM Bid NATURAL JOIN Advertisements
		WHERE icnum = NEW.icnum
		AND origin <> ori
		AND destination <> dest
		AND ((t - doa < '15 minute'::interval AND t - doa >'-15 minute'::interval)
				OR
				(doa - t < '15 minute'::interval AND doa - t >'-15 minute'::interval))
		)
THEN
RAISE NOTICE 'Create bid failed on checkBidderTime()!';
RETURN NULL;
ELSE
RAISE NOTICE 'Create bid success!';
RETURN NEW;
END IF;
END; $$
LANGUAGE PLPGSQL;


CREATE TRIGGER bidderTime
BEFORE INSERT
ON bid
FOR EACH ROW
EXECUTE PROCEDURE checkBidderTime();


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


------------- LOG -------------
CREATE TABLE blog (
	ICNum VARCHAR(16),
	adID INTEGER,
	pointBefore INTEGER,
	pointAfter INTEGER NOT NULL,
	upadteTime TIMESTAMP NOT NULL
);

CREATE OR REPLACE FUNCTION bidlog()
RETURNS TRIGGER AS $$
DECLARE pb INTEGER;
DECLARE now TIMESTAMP;
BEGIN
now := CURRENT_TIMESTAMP;
IF TG_OP ='INSERT'
THEN pb:=null;
ELSEIF TG_OP ='UPDATE'
THEN pb:=OLD.bidpoints;
END IF;
INSERT INTO blog
VALUES (NEW.icnum, NEW.adID, pb, NEW.bidpoints, now);
RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

CREATE TRIGGER logBP
AFTER INSERT OR UPDATE
ON bid
FOR EACH ROW
EXECUTE PROCEDURE bidlog();
