UPDATE `#__js_ticket_config` SET `configname`='cplink_myticket_staff' WHERE `configname`='staff_cplink_myticket';
UPDATE `#__js_ticket_config` SET `configname`='cplink_openticket_staff' WHERE `configname`='staff_cplink_openticket';
UPDATE `#__js_ticket_config` SET `configname`='cplink_addrole_staff' WHERE `configname`='staff_cplink_addrole';
UPDATE `#__js_ticket_config` SET `configname`='cplink_roles_staff' WHERE `configname`='staff_cplink_roles';
UPDATE `#__js_ticket_config` SET `configname`='cplink_addstaff_staff' WHERE `configname`='staff_cplink_addstaff';
UPDATE `#__js_ticket_config` SET `configname`='cplink_staff_staff' WHERE `configname`='staff_cplink_staff';
UPDATE `#__js_ticket_config` SET `configname`='cplink_adddepartment_staff' WHERE `configname`='staff_cplink_adddepartment';
UPDATE `#__js_ticket_config` SET `configname`='cplink_department_staff' WHERE `configname`='staff_cplink_department';
UPDATE `#__js_ticket_config` SET `configname`='cplink_addcategory_staff' WHERE `configname`='staff_cplink_addcategory';
UPDATE `#__js_ticket_config` SET `configname`='cplink_category_staff' WHERE `configname`='staff_cplink_category';
UPDATE `#__js_ticket_config` SET `configname`='cplink_addkbarticle_staff' WHERE `configname`='staff_cplink_addkb';
UPDATE `#__js_ticket_config` SET `configname`='cplink_kbarticle_staff' WHERE `configname`='staff_cplink_kb';
UPDATE `#__js_ticket_config` SET `configname`='cplink_adddownload_staff' WHERE `configname`='staff_cplink_adddownload';
UPDATE `#__js_ticket_config` SET `configname`='cplink_download_staff' WHERE `configname`='staff_cplink_download';
UPDATE `#__js_ticket_config` SET `configname`='cplink_addannouncement_staff' WHERE `configname`='staff_cplink_addannouncement';
UPDATE `#__js_ticket_config` SET `configname`='cplink_announcement_staff' WHERE `configname`='staff_cplink_announcement';
UPDATE `#__js_ticket_config` SET `configname`='cplink_addfaq_staff' WHERE `configname`='staff_cplink_addfaq';
UPDATE `#__js_ticket_config` SET `configname`='cplink_faq_staff' WHERE `configname`='staff_cplink_faq';
UPDATE `#__js_ticket_config` SET `configname`='cplink_mail_staff' WHERE `configname`='staff_cplink_mail';
UPDATE `#__js_ticket_config` SET `configname`='cplink_profile_staff' WHERE `configname`='staff_cplink_profile';
UPDATE `#__js_ticket_config` SET `configname`='tplink_home_staff' WHERE `configname`='staff_tplink_home';
UPDATE `#__js_ticket_config` SET `configname`='tplink_ticket_staff' WHERE `configname`='staff_tplink_ticket';
UPDATE `#__js_ticket_config` SET `configname`='tplink_knowledgebase_staff' WHERE `configname`='staff_tplink_kb';
UPDATE `#__js_ticket_config` SET `configname`='tplink_announcement_staff' WHERE `configname`='staff_tplink_announcement';
UPDATE `#__js_ticket_config` SET `configname`='tplink_download_staff' WHERE `configname`='staff_tplink_download';
UPDATE `#__js_ticket_config` SET `configname`='tplink_faq_staff' WHERE `configname`='staff_tplink_faq';
UPDATE `#__js_ticket_config` SET `configname`='cplink_openticket_user' WHERE `configname`='user_cplink_openticket';
UPDATE `#__js_ticket_config` SET `configname`='cplink_myticket_user' WHERE `configname`='user_cplink_myticket';
UPDATE `#__js_ticket_config` SET `configname`='cplink_checkstatus_user' WHERE `configname`='user_cplink_checkstatus';
UPDATE `#__js_ticket_config` SET `configname`='cplink_download_user' WHERE `configname`='user_cplink_download';
UPDATE `#__js_ticket_config` SET `configname`='cplink_announcement_user' WHERE `configname`='user_cplink_announcement';
UPDATE `#__js_ticket_config` SET `configname`='cplink_faq_user' WHERE `configname`='user_cplink_faq';
UPDATE `#__js_ticket_config` SET `configname`='cplink_knowledgebase_user' WHERE `configname`='user_cplink_kb';
UPDATE `#__js_ticket_config` SET `configname`='tplink_knowledgebase_user' WHERE `configname`='user_tplink_kb';
UPDATE `#__js_ticket_config` SET `configname`='tplink_home_user' WHERE `configname`='user_tplink_home';
UPDATE `#__js_ticket_config` SET `configname`='tplink_ticket_user' WHERE `configname`='user_tplink_ticket';
UPDATE `#__js_ticket_config` SET `configname`='tplink_announcement_user' WHERE `configname`='user_tplink_announcement';
UPDATE `#__js_ticket_config` SET `configname`='tplink_download_user' WHERE `configname`='user_tplink_download';
UPDATE `#__js_ticket_config` SET `configname`='tplink_faq_user' WHERE `configname`='user_tplink_faq';
INSERT INTO `#__js_ticket_config` SET configfor='email', configname='ticket_priority_admin', configvalue=1;
INSERT INTO `#__js_ticket_config` SET configfor='email', configname='ticket_priority_user', configvalue=1;
INSERT INTO `#__js_ticket_config` SET configfor='email', configname='ticket_priority_staff', configvalue=1;
INSERT INTO `#__js_ticket_config` SET configfor='ticketviaemail', configname='tve_ssl', configvalue='';
INSERT INTO `#__js_ticket_config` SET configfor='ticketviaemail', configname='tve_hostportnumber', configvalue='';
UPDATE `#__js_ticket_config` SET `configvalue`='101' WHERE `configname`='version';

CREATE TABLE IF NOT EXISTS `#__js_ticket_fieldsordering` (
	`id` int(11) NOT NULL AUTO_INCREMENT, 
	`field` varchar(50) NOT NULL, 
	`fieldtitle` varchar(50) DEFAULT NULL, 
	`ordering` int(11) DEFAULT NULL, 
	`section` varchar(20) DEFAULT NULL, 
	`fieldfor` tinyint(2) DEFAULT NULL, 
	`published` tinyint(1) DEFAULT NULL, 
	`sys` tinyint(1) NOT NULL, 
	`cannotunpublish` tinyint(1) NOT NULL, 
	`required` tinyint(1) NOT NULL DEFAULT '0', 
	PRIMARY KEY (`id`), KEY `fieldordering_filedfor` (`fieldfor`) ) 
ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
INSERT INTO `#__js_ticket_fieldsordering` VALUES (1,'emailaddress','Email Address',1,'10',1,1,0,1,1), (2, 'users', 'User', 2, '10', 1, 1, 0, 0, 0), (3,'fullname','Full Name',4,'10',1,1,0,1,1), (4,'phone','Phone',5,'10',1,1,0,0,0), (5,'department','Department',6,'10',1,1,0,0,0), (6,'helptopic','Help Topic',7,'10',1,1,0,0,0), (7,'priority','Priority',3,'10',1,1,0,1,1), (8,'subject','Subject',9,'10',1,1,0,1,1), (9,'premade','Premade',8,'10',1,1,0,0,0), (10,'issuesummary','Issue Summary',10,'10',1,1,0,1,1), (11,'attachments','Attachments',11,'10',1,1,0,0,0), (12,'internalnotetitle','Internal Note Title',12,'10',1,1,0,0,1), (13,'assignto','Assign To',13,'10',1,1,0,0,0), (14,'duedate','Due Date',14,'10',1,1,0,0,0), (15, 'status', 'Status', 15, '10', 1, 1, 0, 0, 0);


