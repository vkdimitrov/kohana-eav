<?php defined('SYSPATH') or die('No direct script access.'); 

/**
 * Helper Procedure
 *
 * Created on 2014-04-09
 * @author Vladimir Dimitrov
 */

class Procedure {

	/**
	 * Procedure to create eav table
	 * @param  string $dbname Database name
	 * @param  string $tname  Table name
	 * @param  string $setid  name of table's column pointing eav_set table
	 */
	public static function create_eav($dbname, $tname, $setid, $instance = NULL)
	{
		DB::query(NULL, "CREATE TABLE IF NOT EXISTS {$dbname}.{$tname} (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, {$setid} int(10) unsigned NOT NULL, `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP, `updated_at` timestamp NULL DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
			)->execute($instance);
	}

	/**
	 * Procedure to create eav_set table without country
	 * @param  string $tname eav_set table name
	 */
	public static function create_eav_set_wo_country($tname, $instance = NULL)
	{
		$foreign_key = $tname.'-fk-'.$tname;
		DB::query(NULL, "CREATE TABLE IF NOT EXISTS {$tname} (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, `name` varchar(45) NOT NULL UNIQUE, `parent_set_id` int(10) unsigned DEFAULT NULL, `comment` text DEFAULT NULL, `visual_data` text DEFAULT NULL, PRIMARY KEY (`id`), KEY `{$foreign_key}` (`parent_set_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
			)->execute($instance);

		DB::query(NULL, "ALTER TABLE {$tname} ADD CONSTRAINT `{$foreign_key}` FOREIGN KEY (`parent_set_id`) REFERENCES `{$tname}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;")->execute($instance);
	}

	/**
	 * Procedure to create eav_set_attributes table
	 * @param  string $tname Table name
	 * @param  string $setid Name of table's column pointing eav_set table
	 * @param  string $settable Eav_set table 
	 */
	public static function create_eav_set_attributes($tname, $setid, $settable, $instance = NULL)
	{
		$foreign_key = $tname.'-fk-eav_attributes';
		$foreign_key2 = $tname.'-fk-'.$settable;
		DB::query(NULL, "CREATE TABLE IF NOT EXISTS {$tname} (`{$setid}` int(10) unsigned NOT NULL, `eav_attribute_id` int(10) unsigned NOT NULL, PRIMARY KEY (`{$setid}`,`eav_attribute_id`), KEY `{$foreign_key}` (`eav_attribute_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8;")->execute($instance);
		DB::query(NULL, "ALTER TABLE {$tname} ADD CONSTRAINT `{$foreign_key}` FOREIGN KEY (`eav_attribute_id`) REFERENCES `eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE, ADD CONSTRAINT `{$foreign_key2}` FOREIGN KEY (`{$setid}`) REFERENCES `{$settable}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;")->execute($instance);
	}

	/**
	 * Procedure to create user's database 
	 * @param  string $dbname Name of db
	 */
	public static function create_userdb($dbname, $instance = NULL)
	{
		DB::query(NULL, "CREATE DATABASE IF NOT EXISTS {$dbname} COLLATE=`utf8_general_ci`;")->execute();
	}
}