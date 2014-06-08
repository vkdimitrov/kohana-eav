SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Table structure for table `eav_attribute_types`
--

CREATE TABLE IF NOT EXISTS `eav_attribute_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(45) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `db_type` varchar(64) NOT NULL DEFAULT 'varchar(45)',
  `value_type` enum('basic','select','object') NOT NULL DEFAULT 'basic' COMMENT 'The Type of the attribute value',
  `class` varchar(45) DEFAULT NULL,
  `validation` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;


--
-- Table structure for table `eav_attributes`
--

CREATE TABLE IF NOT EXISTS `eav_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `default` varchar(32) DEFAULT NULL,
  `comment` text,
  `unique` tinyint(1) DEFAULT '0',
  `validation` varchar(255) DEFAULT NULL,
  `obligatory` tinyint(1) DEFAULT '0',
  `use_in_company` tinyint(1) DEFAULT '0',
  `use_in_documents` tinyint(1) DEFAULT '0',
  `use_in_document_articles` tinyint(1) DEFAULT '0',
  `use_in_tax_rules` tinyint(1) DEFAULT '0',
  `use_in_account_pairs` tinyint(1) DEFAULT '0',
  `use_in_reporting` tinyint(1) DEFAULT '0',
  `show_in_listing` tinyint(1) DEFAULT '0',
  `use_in_sorting` tinyint(1) DEFAULT '0',
  `show_in_view` tinyint(1) DEFAULT '0',
  `label` varchar(128) NOT NULL DEFAULT '',
  `use_in_document_rules` tinyint(1) DEFAULT '0',
  `use_in_totaling_rules` tinyint(1) DEFAULT '0',
  `use_in_accounting_rules` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `eav_attributes-fk-eav_attribute_types_idx` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Table structure for table `eav_attribute_options`
--

CREATE TABLE IF NOT EXISTS `eav_attribute_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eav_attribute_id` int(10) unsigned NOT NULL,
  `option` varchar(255) DEFAULT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eav_attribute_options-fk-eav_attributes_idx` (`eav_attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
