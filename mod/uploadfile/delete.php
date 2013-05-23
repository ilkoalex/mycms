<?php
/*
MyCMS - a simple Content Management System
Copyright (C) 2013  Vanyo Georgiev <info@vanyog.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// ��������� �� ����� �� ����� ����.
// ����� �� �� ������ ������ � ������!

if (!isset($_GET['fid'])) die("No upload id");

$idir = dirname(dirname(dirname(__FILE__))).'/';

include($idir.'lib/translation.php');
//include($idir.'lib/f_db_select_1.php');

// ����� �� ������ �� �����.
$fid = 1*$_GET['fid'];

// ������ �� ������� �� ����� �� ������� $tn_prefix.'files'.
$fd = db_select_1('*','files',"`ID`='$fid'");

if (!$fd) die(translate('uploadfile_idnotexists'));

// ��������� �� ����� �� �������
unlink($fd['filename']);

// ��������� ��� ��������� �� ������ �� ������ �����
if (stored_value('uploadfile_deletefileonly')=='true')
  $q = "UPDATE `$tn_prefix"."files` SET `filename`='' WHERE `ID`=$fid;";
else
  $q = "DELETE FROM `$tn_prefix"."files` WHERE `ID`=$fid;";
mysql_query($q,$db_link);

// ���������� ��� ���������� � ���� ��� �����
header("Location: $pth"."index.php?pid=".$fd['pid']);


?>