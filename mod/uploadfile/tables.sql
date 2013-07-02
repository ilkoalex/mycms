CREATE TABLE IF NOT EXISTS `files` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `pid` (`pid`),
  KEY `name` (`name`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
INSERT INTO `content` (`name`,`date_time_1`,`date_time_2`,`language`,`text`) VALUES
('uploadfile_file',NOW(),NOW(),'bg','���� �� �������:'),
('uploadfile_fileinuse',NOW(),NOW(),'bg','���� ��� ������ ��� �� �������� �� ����� ����� �� �����. ������������� ����� � �������� ������.'),
('uploadfile_linktext',NOW(),NOW(),'bg','����� �� ������������� ��� �����:'),
('uploadfile_nofile',NOW(),NOW(),'bg','���� ����� ����'),
('uploadfile_submit',NOW(),NOW(),'bg','�������'),
('uploadfile_upladpagetitle',NOW(),NOW(),'bg','������� �� ����'),
('uploadfile_idnotexists',NOW(),NOW(),'bg','�� ����������� ����� �� ����.'),
('uploadfile_confdel',NOW(),NOW(),'bg','�� �� ������ �� ���� '),
('uploadfile_fileexists',NOW(),NOW(),'bg','�� ������� ���� ��� ���� ��� ������ ���, ����� �������� �� �������� �� ����� ��������. ������������� ����� � �������� ������.');