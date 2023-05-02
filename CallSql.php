<?php
require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';

Database::use("tamagotchi_bcl");
Database::rawQuery("DROP PROCEDURE IF EXISTS `CREATE_ACCOUNT`");
Database::rawQuery("DROP PROCEDURE IF EXISTS `CREATE_TAMAGOTCHI`");
Database::rawQuery("DROP PROCEDURE IF EXISTS `EAT`");
Database::rawQuery("DROP PROCEDURE IF EXISTS `DRINK`");
Database::rawQuery("DROP PROCEDURE IF EXISTS `BEDTIME`");
Database::rawQuery("DROP PROCEDURE IF EXISTS `ENJOY`");
Database::rawQuery("DROP FUNCTION IF EXISTS `LEVEL_CHECK`");
Database::rawQuery("DROP FUNCTION IF EXISTS `IS_ALIVE`");
Database::rawQuery("DROP TRIGGER IF EXISTS `actions_trigger`");
Database::rawQuery("DROP TRIGGER IF EXISTS `tamagotchi_deaths_trigger`");
Database::rawQuery("DROP VIEW IF EXISTS `tamagotchi_life`");

//PROCEDURE
 Database::rawQuery("CREATE PROCEDURE CREATE_ACCOUNT(IN name VARCHAR(255))
BEGIN
    INSERT INTO accounts (name) VALUES (name);
END");
 Database::rawQuery("CREATE PROCEDURE CREATE_TAMAGOTCHI(IN name VARCHAR(255), IN account_id INT)
BEGIN
    INSERT INTO tamagotchis (name, account_id) VALUES (name, account_id);
END");
 Database::rawQuery("CREATE PROCEDURE EAT(IN tamagotchi_id INT)
BEGIN
    IF (SELECT hunger FROM tamagotchis WHERE tamagotchis.id = tamagotchi_id) <= 80 THEN
        INSERT INTO actions (name, tamagotchi_id) VALUES ('eat', tamagotchi_id);
    END IF;
END");
    Database::rawQuery("CREATE PROCEDURE DRINK(IN tamagotchi_id INT)
BEGIN
    IF (SELECT thirst FROM tamagotchis WHERE tamagotchis.id = tamagotchi_id) <= 80 THEN
        INSERT INTO actions (name, tamagotchi_id) VALUES ('drink', tamagotchi_id);
    END IF;
END");
        Database::rawQuery("CREATE PROCEDURE BEDTIME(IN tamagotchi_id INT)
BEGIN
    IF (SELECT sleep FROM tamagotchis WHERE tamagotchis.id = tamagotchi_id) <= 80 THEN
        INSERT INTO actions (name, tamagotchi_id) VALUES ('bedtime', tamagotchi_id);
    END IF;
END");
            Database::rawQuery("CREATE PROCEDURE ENJOY(IN tamagotchi_id INT)
BEGIN
    IF (SELECT boredom FROM tamagotchis WHERE tamagotchis.id = tamagotchi_id) <= 80 THEN
        INSERT INTO actions (name, tamagotchi_id) VALUES ('enjoy', tamagotchi_id);
    END IF;
END");



//FUNCTION
Database::rawQuery(
    "CREATE FUNCTION LEVEL_CHECK( tamagotchi_id INT)
    RETURNS INT
    BEGIN
        DECLARE level INT;
        SELECT FLOOR(COUNT(actions.id)/10)+1 INTO level FROM actions WHERE actions.tamagotchi_id = tamagotchi_id;
        RETURN level;
    END");



Database::rawQuery("CREATE FUNCTION IS_ALIVE( tamagotchi_id INT)
RETURNS INT
BEGIN
    DECLARE alive INT;
        SELECT COUNT(deaths.id) INTO alive  FROM deaths WHERE deaths.tamagotchi_id = tamagotchi_id ;
        RETURN alive;
END");

Database::rawQuery("CREATE TRIGGER actions_trigger AFTER INSERT ON actions
FOR EACH ROW
BEGIN
    DECLARE liveforce INT;
    SET liveforce = LEVEL_CHECK(NEW.tamagotchi_id)-1;
    UPDATE tamagotchis 
    SET 
        hunger = CASE NEW.name 
                    WHEN 'eat' THEN IF((hunger + 30 + liveforce) > 100, 100, (hunger + 30 + liveforce))
                    WHEN 'drink' THEN IF( (hunger < (10 + liveforce)), 0, (hunger - 10 - liveforce))
                    WHEN 'bedtime' THEN IF((hunger < (10  + liveforce)), 0, (hunger - 10 - liveforce))
                    WHEN 'enjoy' THEN IF((hunger < (5 + liveforce)), 0, (hunger - 5 - liveforce))
                    ELSE hunger
                END,
        thirst = CASE NEW.name 
                    WHEN 'eat' THEN IF((thirst < (10 + liveforce)), 0, (thirst - 10 - liveforce))
                    WHEN 'drink' THEN IF((thirst + 30 + liveforce) > 100, 100, (thirst + 30 + liveforce))
                    WHEN 'bedtime' THEN IF((thirst < (15 + liveforce)) , 0, (thirst - 15 - liveforce))
                    WHEN 'enjoy' THEN IF((thirst < (5 + liveforce)), 0, (thirst - 5 - liveforce))
                    ELSE thirst
                END,
        sleep = CASE NEW.name 
                    WHEN 'eat' THEN IF((sleep < (5 + liveforce)), 0, (sleep - 5 - liveforce))
                    WHEN 'drink' THEN IF((sleep < (5 + liveforce)), 0, (sleep - 5 - liveforce))
                    WHEN 'enjoy' THEN IF((sleep < (5 + liveforce)), 0, (sleep - 5 - liveforce))
                    WHEN 'bedtime' THEN IF((sleep + 30 + liveforce) > 100, 100, (sleep + 30 + liveforce))
                    ELSE sleep
                END,
        boredom = CASE NEW.name 
                    WHEN 'enjoy' THEN IF((boredom + 15 + liveforce) < 0, 0, (boredom + 15 + liveforce))
                    WHEN 'eat' THEN IF((boredom < (5 + liveforce)), 0, (boredom - 5 - liveforce))
                    WHEN 'drink' THEN IF((boredom < (5 + liveforce)), 0, (boredom - 5 - liveforce))
                    WHEN 'bedtime' THEN IF((boredom < (15 + liveforce)), 0, (boredom - 15 - liveforce))
                    ELSE boredom
                END
    WHERE id = NEW.tamagotchi_id;
END;");


Database::rawQuery("CREATE TRIGGER tamagotchi_deaths_trigger
AFTER UPDATE ON tamagotchis
FOR EACH ROW
BEGIN
    DECLARE death_inserted BOOLEAN DEFAULT FALSE;
    IF (NEW.hunger = 0 OR NEW.thirst = 0 OR NEW.sleep = 0 OR NEW.boredom = 0) THEN
    INSERT INTO deaths (tamagotchi_id) 
    SELECT NEW.id
    WHERE NOT EXISTS (SELECT 1 FROM deaths WHERE tamagotchi_id = NEW.id);
    END IF;
END;");


Database::rawQuery("CREATE VIEW tamagotchi_life AS
SELECT tamagotchis.id, deaths.deathdate, tamagotchis.birthdate,
       count(CASE WHEN actions.name = 'eat' THEN 1 END) as eat_count,
       count(CASE WHEN actions.name = 'drink' THEN 1 END) as drink_count,
       count(CASE WHEN actions.name = 'bedtime' THEN 1 END) as bedtime_count,
       count(CASE WHEN actions.name = 'enjoy' THEN 1 END) as enjoy_count,
(SELECT LEVEL_CHECK(tamagotchis.id)) as level        
FROM tamagotchis
LEFT JOIN deaths ON tamagotchis.id = deaths.tamagotchi_id
JOIN actions ON tamagotchis.id = actions.tamagotchi_id
GROUP BY tamagotchis.id, deaths.deathdate, tamagotchis.birthdate");


?>