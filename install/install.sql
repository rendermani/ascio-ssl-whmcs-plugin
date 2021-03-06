CREATE TABLE IF NOT EXISTS `mod_asciossl` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL,
  `order_id` char(10) NOT NULL,
  `certificate_id` char(20)  NULL,
  `type` char(255) NOT NULL,
  `period` int(2) NOT NULL,
  `status` char(100)  NULL,
  `code` int(11)  NULL,
  `message` varchar(1024)  NULL,
  `errors` varchar(4096)  NULL,
  `token` char(100)  NULL,
  `whmcs_service_id` int(11) NOT NULL,
  `common_name` varchar(2048) DEFAULT NULL,
  `csr` varchar(2048) DEFAULT NULL,
  `webserver` varchar(2048) DEFAULT NULL,
  `verification_type` enum('Email','Dns','File') NOT NULL,
  `dns_name` varchar(1024) NULL,
  `dns_value` varchar(1024) NULL,
  `dns_error_code` varchar(256) NULL,
  `dns_error_message` varchar(2048) NULL,
  `create_dns_record` tinyint(1) DEFAULT NULL,
  `dns_created` tinyint(1) NULL,
  `approval_email` varchar(256) DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `ownerTitle` varchar(256) DEFAULT NULL,
  `ownerFirstName` varchar(256) DEFAULT NULL,
  `ownerLastName` varchar(256) DEFAULT NULL,
  `ownerCompanyName` varchar(512) DEFAULT NULL,
  `ownerPhone` varchar(256) DEFAULT NULL,
  `ownerAddress1` varchar(512) DEFAULT NULL,
  `ownerAddress2` varchar(512) DEFAULT NULL,
  `ownerCity` varchar(256) DEFAULT NULL,
  `ownerState` varchar(256) DEFAULT NULL,
  `ownerPostcode` varchar(256) DEFAULT NULL,
  `ownerCountry` varchar(256) DEFAULT NULL,
  `adminTitle` varchar(256) DEFAULT NULL,
  `adminFirstName` varchar(256) DEFAULT NULL,
  `adminLastName` varchar(256) DEFAULT NULL,
  `adminCompanyName` varchar(512) DEFAULT NULL,
  `adminPhone` varchar(256) DEFAULT NULL,
  `adminAddress1` varchar(512) DEFAULT NULL,
  `adminAddress2` varchar(512) DEFAULT NULL,
  `adminCity` varchar(256) DEFAULT NULL,
  `adminState` varchar(256) DEFAULT NULL,
  `adminPostcode` varchar(256) DEFAULT NULL,
  `adminCountry` varchar(256) DEFAULT NULL,
  `techTitle` varchar(256) DEFAULT NULL,
  `techFirstName` varchar(256) DEFAULT NULL,
  `techLastName` varchar(256) DEFAULT NULL,
  `techCompanyName` varchar(512) DEFAULT NULL,
  `techPhone` varchar(256) DEFAULT NULL,
  `techAddress1` varchar(512) DEFAULT NULL,
  `techAddress2` varchar(512) DEFAULT NULL,
  `techCity` varchar(256) DEFAULT NULL,
  `techState` varchar(256) DEFAULT NULL,
  `techPostcode` varchar(256) DEFAULT NULL,
  `techCountry` varchar(256) DEFAULT NULL,
  `ownerEmail` varchar(256) DEFAULT NULL,
  `adminEmail` varchar(256) DEFAULT NULL,
  `techEmail` varchar(256) DEFAULT NULL,
  `completed_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP, 
  `module` varchar(20) NOT NULL DEFAULT 'ssl',  
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `whmcs_service_id` (`whmcs_service_id`)
);

--  mod_asciossl_sans 

CREATE TABLE `mod_asciossl_sans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `verification_type` varchar(255) DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `mx_fqdn` tinyint(1) DEFAULT NULL,
  `mx_domain` tinyint(1) DEFAULT NULL,
  `dns_name` varchar(255) DEFAULT NULL,
  `dns_value` varchar(255) NOT NULL,
  `dns_error_message` varchar(255) NOT NULL,
  `dns_error_code` varchar(255) NOT NULL,
  `dns_created` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
   KEY `whmcs_service_id` (`service_id`),
   KEY `name` (`name`)

);

-- mod_asciossl_settings 

CREATE TABLE `mod_asciossl_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `role` enum('User','Admin','') NOT NULL DEFAULT 'User',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`name`),
  KEY `name` (`name`),
  KEY `role` (`role`)
);

INSERT INTO `mod_asciossl_settings` (`id`, `name`, `value`, `role`) VALUES
(1, 'Account', '', 'User'),
(2, 'Password', '', 'User'),
(3, 'AccountTesting', '', 'User'),
(4, 'PasswordTesting', '', 'User'),
(5, 'Environment', '', 'User'),
(6, 'CreateDns', '1', 'User'),
(7, 'RequireDomain', '1', 'User'),
(9, 'DbVersion', '0.2', 'Admin')


