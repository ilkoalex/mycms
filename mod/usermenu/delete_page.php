<?php
/* 
MyCMS - a simple Content Management System
Copyright (C) 2013 Vanyo Georgiev <info@vanyog.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// ��������� �� �������� ����� $_GET['pid']

$idir = dirname(dirname(dirname(__FILE__))).'/';
$ddir = $idir;

include("f_usermenu.php");
include_once($idir."lib/translation.php");
//include_once($idir."lib/f_parse_content.php");
//include_once($idir."lib/f_db_insert_1.php");
//include_once($idir."lib/f_db_insert_m.php");
//include_once($idir."lib/o_form.php");

// ����� �� ����������
$page_id = 1*$_GET['pid'];

// ����� �� ����������
$page_data = db_select_1('*', 'pages', "`ID`=$page_id");

// ����������� ������� �� �����������
usermenu(true);

//if ($can_create) echo "can create<br>";
//if ($can_edit) echo "can edit<br>";
//die;

// ��� ������������ ���� ����� �� ������� ���������� - ����.
if (!$can_create || !$can_edit) echo die("Your have no permission to delete this page.");

// ���������
$q = "DELETE FROM `$tn_prefix"."pages` WHERE `ID`=$page_id;";
//echo "$q<br>";
mysql_query($q,$db_link);

// ����� �� ������
$m = db_select_1('*', 'menu_items', "`group`=".$page_data['menu_group']." AND `link`=".$page_data['ID']);
// ���������
$q = "DELETE FROM `$tn_prefix"."menu_items` WHERE `group`=".$page_data['menu_group']." AND `link`=".$page_data['ID'].";";
//echo "$q<br>";
mysql_query($q,$db_link);

// ����� �� ���������
$t = db_select_m('*', 'content', "`name`='".$m['name']."' OR `name`='".$page_data['title']."' OR `name`='".$page_data['content']."'");
// ���������
$q = "DELETE FROM `$tn_prefix"."content` WHERE `name`='".$m['name']."' OR `name`='".$page_data['title']."' OR `name`='".$page_data['content']."';";
//echo "$q<br>";
mysql_query($q,$db_link);

// ������� ��� �������� �������� �� �������
$pid = db_table_field('index_page', 'menu_tree', "`group`=".$page_data['menu_group']);
header("Location: $pth"."index.php?pid=$pid");

?>
