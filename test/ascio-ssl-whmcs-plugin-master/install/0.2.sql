SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

-- mod_asciossl

ALTER TABLE mod_asciossl ADD `code` int(11)  NULL;
ALTER TABLE mod_asciossl ADD `message` varchar(1024)  NULL;
ALTER TABLE mod_asciossl ADD `errors` varchar(4096)  NULL;
ALTER TABLE mod_asciossl ADD `whmcs_service_id` int(11) NOT NULL;
ALTER TABLE mod_asciossl ADD `common_name` varchar(2048) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `csr` varchar(2048) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `webserver` varchar(2048) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `verification_type` enum('Email','Dns','File') NOT NULL;
ALTER TABLE mod_asciossl ADD `dns_name` varchar(1024)  NULL;
ALTER TABLE mod_asciossl ADD `dns_value` varchar(1024)  NULL;
ALTER TABLE mod_asciossl ADD `dns_error_code` varchar(256)  NULL;
ALTER TABLE mod_asciossl ADD `dns_error_message` varchar(2048)  NULL;
ALTER TABLE mod_asciossl ADD `create_dns_record` tinyint(1) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `dns_created` tinyint(1)  NULL;
ALTER TABLE mod_asciossl ADD `approval_email` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `expire_date` date DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerTitle` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerFirstName` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerLastName` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerCompanyName` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerPhone` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerAddress1` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerAddress2` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerCity` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerState` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerPostcode` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerCountry` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminTitle` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminFirstName` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminLastName` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminCompanyName` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminPhone` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminAddress1` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminAddress2` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminCity` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminState` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminPostcode` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminCountry` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techTitle` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techFirstName` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techLastName` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techCompanyName` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techPhone` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techAddress1` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techAddress2` varchar(512) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techCity` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techState` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techPostcode` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techCountry` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `ownerEmail` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `adminEmail` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `techEmail` varchar(256) DEFAULT NULL;
ALTER TABLE mod_asciossl ADD `completed_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE mod_asciossl ADD `module` varchar(20) NOT NULL DEFAULT 'ssl';

ALTER TABLE `mod_asciossl`
  ADD KEY `whmcs_service_id` (`whmcs_service_id`),
  ADD KEY `order_id` (`order_id`);


--  mod_asciossl_sans

CREATE TABLE `mod_asciossl_sans` (
  `id` int(11) NOT NULL,
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
  `dns_created` tinyint(1) NOT NULL
);


ALTER TABLE `mod_asciossl_sans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whmcs_service_id` (`service_id`),
  ADD KEY `name` (`name`);

--  mod_asciossl_settings

CREATE TABLE `mod_asciossl_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `role` enum('User','Admin','') NOT NULL DEFAULT 'User'
);

ALTER TABLE `mod_asciossl_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`name`),
  ADD KEY `name` (`name`),
  ADD KEY `role` (`role`);

INSERT INTO `mod_asciossl_settings` (`name`, `value`, `role`) VALUES ( 'DbVersion', '0.2', 'Admin');

INSERT INTO mod_asciossl (user_id,whmcs_service_id,order_id,type,status,module,period,code,message,verification_type)
SELECT `userid`, `serviceid`, `remoteid`,`certtype`,`status`,'autoinstallssl',1,0,null,"File" FROM `tblsslorders`;
COMMIT;

