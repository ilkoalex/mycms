CREATE TABLE IF NOT EXISTS `permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('page','menu','module') COLLATE cp1251_bulgarian_ci NOT NULL DEFAULT 'page',
  `object` varchar(20) COLLATE cp1251_bulgarian_ci NOT NULL,
  `yes_no` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `object` (`object`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 COLLATE=cp1251_bulgarian_ci AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
INSERT INTO `content` (`name`,`nolink`,`date_time_1`,`date_time_2`,`language`,`text`) VALUES
('usermenu_confirdeleting',1,NOW(),NOW(),'bg','����������� �� �������� �� ������� ��������� ������ �����������, ����� ����� ��� ���. �������� �� ������ �� �������� ���� ��������?'),
('usermenu_createnewpage',1,NOW(),NOW(),'bg','��������� �� ���� ��������'),
('usermenu_language',1,NOW(),NOW(),'bg','����:'),
('usermenu_linktext',1,NOW(),NOW(),'bg','����� �� ����� � ������:'),
('usermenu_menupos',1,NOW(),NOW(),'bg','������� � ������:'),
('usermenu_newpagecontent',1,NOW(),NOW(),'bg','���������� �� ����������:'),
('usermenu_newpagesubmit',1,NOW(),NOW(),'bg','��������� � �������� �� ����������'),
('usermenu_texttoedit',1,NOW(),NOW(),'bg','Text:'),
('usermenu_back',1,NOW(),NOW(),'bg','������� �������'),
('usermenu_newpagetitle',1,NOW(),NOW(),'bg','��������:'),
('usermenu_edittext',1,NOW(),NOW(),'bg','����������� �� �����');