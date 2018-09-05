DROP PROCEDURE IF EXISTS `proc_get_labs_tat`;
DELIMITER //
CREATE PROCEDURE `proc_get_labs_tat`
<<<<<<< HEAD
(IN filter_year INT(11), IN filter_month INT(11))
BEGIN
  SET @QUERY =    "SELECT 
                        `lb`.`labname`, 
                        AVG(`vls`.`tat1`) AS `tat1`, 
                        AVG(`vls`.`tat2`) AS `tat2`, 
                        AVG(`vls`.`tat3`) AS `tat3`, 
                        AVG((`vls`.`tat1`+`vls`.`tat2`+`vls`.`tat3`)) AS `tat4` 
=======
(IN filter_year INT(11), IN from_month INT(11), IN to_year INT(11), IN to_month INT(11))
BEGIN
  SET @QUERY =    "SELECT 
                        `lb`.`labname`, 
                        `vls`.`tat1`, 
                        `vls`.`tat2`, 
                        `vls`.`tat3`, 
                        `vls`.`tat4` 
>>>>>>> dfa5047ba0638ef2034b95dfa69e0cd14bb05ef6
                    FROM `vl_lab_summary` `vls` 
                    JOIN `labs` `lb` 
                        ON `vls`.`lab` = `lb`.`ID` WHERE 1";

<<<<<<< HEAD
    IF (filter_month != 0 && filter_month != '') THEN
       SET @QUERY = CONCAT(@QUERY, " AND `vls`.`year` = '",filter_year,"' AND `vls`.`month`='",filter_month,"' ");
    ELSE
        SET @QUERY = CONCAT(@QUERY, " AND `vls`.`year` = '",filter_year,"' ");
    END IF;

    SET @QUERY = CONCAT(@QUERY, " GROUP BY `lb`.`labname` ");
=======
   
    IF (from_month != 0 && from_month != '') THEN
      IF (to_month != 0 && to_month != '' && filter_year = to_year) THEN
            SET @QUERY = CONCAT(@QUERY, " AND `year` = '",filter_year,"' AND `month` BETWEEN '",from_month,"' AND '",to_month,"' ");
        ELSE IF(to_month != 0 && to_month != '' && filter_year != to_year) THEN
          SET @QUERY = CONCAT(@QUERY, " AND ((`year` = '",filter_year,"' AND `month` >= '",from_month,"')  OR (`year` = '",to_year,"' AND `month` <= '",to_month,"') OR (`year` > '",filter_year,"' AND `year` < '",to_year,"')) ");
        ELSE
            SET @QUERY = CONCAT(@QUERY, " AND `year` = '",filter_year,"' AND `month`='",from_month,"' ");
        END IF;
    END IF;
    ELSE
        SET @QUERY = CONCAT(@QUERY, " AND `year` = '",filter_year,"' ");
    END IF;

    SET @QUERY = CONCAT(@QUERY, " ORDER BY `lb`.`labname`, `vls`.`month` ASC ");
>>>>>>> dfa5047ba0638ef2034b95dfa69e0cd14bb05ef6

     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END //
<<<<<<< HEAD
DELIMITER ;
=======
DELIMITER ;
>>>>>>> dfa5047ba0638ef2034b95dfa69e0cd14bb05ef6
