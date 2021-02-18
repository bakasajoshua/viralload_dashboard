<?php
namespace App\Models;
/**
 * 
 */
class Age
{
	public static function refresh_all()
	{
		self::national();
		self::county();
		self::county_poc();
		self::subcounty();
		self::partner();
		self::facility();
		self::regimen();
		self::fundingagencies();
		return true;
	}

	public static function national()
	{
		DB::statement('
			DROP PROCEDURE IF EXISTS `proc_get_national_age`;
			DELIMITER //
			CREATE PROCEDURE `proc_get_national_age`
			(IN filter_year INT, IN from_month INT, IN to_year INT, IN to_month INT)
			BEGIN
			  SET @QUERY =    "SELECT
			                    `ac`.`name`, 
			                    SUM(`vna`.`tests`) AS `agegroups`,
			                    SUM(`vna`.`undetected` AS `undetected`,
			                    SUM(`vna`.`less1000`) AS `less1000`,
			                    SUM(`vna`.`less5000`) AS `less5000`,
			                    SUM(`vna`.`above5000`) AS `above5000`,
			                    (SUM(`vna`.`undetected`)+SUM(`vna`.`less1000`)) AS `suppressed`,
			                    (SUM(`vna`.`less5000`)+SUM(`vna`.`above5000`)) AS `nonsuppressed`
			                FROM `vl_national_age` `vna`
			                JOIN `agecategory` `ac`
			                    ON `vna`.`age` = `ac`.`ID`
			                WHERE 1";

			    
			    IF (from_month != 0 AND from_month != '') THEN
			      IF (to_month != 0 AND to_month != '' AND filter_year = to_year) THEN
			            SET @QUERY = CONCAT(@QUERY, " AND `year` = '",filter_year,"' AND `month` BETWEEN '",from_month,"' AND '",to_month,"' ");
			        ELSE IF(to_month != 0 AND to_month != '' AND filter_year != to_year) THEN
			          SET @QUERY = CONCAT(@QUERY, " AND ((`year` = '",filter_year,"' AND `month` >= '",from_month,"')  OR (`year` = '",to_year,"' AND `month` <= '",to_month,"') OR (`year` > '",filter_year,"' AND `year` < '",to_year,"')) ");
			        ELSE
			            SET @QUERY = CONCAT(@QUERY, " AND `year` = '",filter_year,"' AND `month`='",from_month,"' ");
			        END IF;
			    END IF;
			    ELSE
			        SET @QUERY = CONCAT(@QUERY, " AND `year` = '",filter_year,"' ");
			    END IF;

			    SET @QUERY = CONCAT(@QUERY, " GROUP BY `ac`.`ID` ORDER BY `ac`.`ID` ASC ");

			    PREPARE stmt FROM @QUERY;
			    EXECUTE stmt;
			END //
			DELIMITER ;');
		return true;
	}

	public static function county()
	{

	}
	public static function county_poc()
	{

	}

	public static function subcounty()
	{

	}

	public static function partner()
	{

	}

	public static function facility()
	{

	}

	public static function regimen()
	{

	}

	public static function fundingagencies()
	{

	}	
}

?>