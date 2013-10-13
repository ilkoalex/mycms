<?php

/*
MyCMS - a simple Content Management System
Copyright (C) 2012  Vanyo Georgiev <info@vanyog.com>

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

// ������� ������ �� ���������� �� ��������
 
// �������� �� ������������ � �����, ����� �� ������� � $_GET['pid']
// ����� �������� �� ������ � ������, ��������, ���������� � ��., ����� �� ��������
// � ����� �� ������� $tn_prefix.`pages`.

error_reporting(E_ALL); ini_set('display_errors',1);

header("Content-Type: text/html; charset=windows-1251");

if (phpversion()>'5.0') date_default_timezone_set("Europe/Sofia");

// ����� �� ����������
$page_id = 1;
if (isset($_GET['pid'])) $page_id = 1*$_GET['pid'];

// ��� �� ������������ �� ���������
$idir = dirname(__FILE__).'/';

// ��� �� ���� conf_database.php � ����� �� ������ �� ������ �����. 
// ���� �� � �������� �� ���� � $idir, ��� � ����������.
$ddir = $idir;

if (
  !file_exists($idir.'conf_database.php')
  || !file_exists($idir.'conf_paths.php')
) 
die('��������� ��� ��� �� � �������� ����������� � �������������. ����� ���� <a href="http://vanyog.com/_new/index.php?pid=91">USAGE.txt</a>.');

include($idir.'lib/f_db_select_1.php');
include($idir.'lib/f_db_select_m.php');
include($idir.'lib/f_parse_template.php');
include_once($idir.'lib/translation.php');

// ����� �� ��������� ����
$ind_fl = $_SERVER['PHP_SELF'];

$page_header = ''; // ������� ��� ������ �� ����������
$body_adds   = ''; // ������� ��� body ����

$can_edit = false;     // ����� �� ����������� �� ��������� ��������� �� ����������
$can_create = false;   // ����� �� ����������� �� ������/������� �������� � ������� ������(�������) �� �����
$can_manage = array(); // ����� �� �������������� �� ������

// ���� �� ���������� �� ���������� �� ������� $tn_prefix.'pages'
$page_data = db_select_1('*','pages',"ID=$page_id");
if (!$page_data) 
   if (is_local()) die('<a href="'.$adm_pth.'new_record.php?t=pages&ID='.$page_id.'">Click here</a> to create a page.');
   else $page_data = page404();

// ����� �� ������������ �� ����������
count_visits($page_data);

// ����� � �����
$page_options = '';
if ($page_data['options']) { $page_options = explode(' ',$page_data['options']); }

// ��������� ��� ���������� �� ���������� � �������
$cnt = parse_template($page_data);

// ��������� �� ����������
echo $cnt;

// --------------------------------

// ����� ��������, ����� ������� ������, �� ���� �������� � ����� �����
function page404(){
return Array (
'ID' => 0,
'menu_group' => 1,
'title' => 'error_404_title',
'content' => 'error_404_content',
'template_id' => 1,
'options' => '',
'tcount'=>0,
'dcount'=>0
);
}

// ���� �����������
function count_visits($p){
global $tn_prefix, $db_link, $idir;
include_once($idir."lib/f_adm_links.php");
// ��� �� �������� ������� �� �������������� �� �� ���� ����
if (!$p['ID'] || show_adm_links()) return '';
new_day();
$q = "UPDATE `$tn_prefix"."pages` SET dcount = dcount+1 WHERE `ID`=".$p['ID'].";";
mysqli_query($db_link,$q);
}

// ��� ������� ��� ��� �� �������� ������� �� ���������� ��������� � ������� $tn_prefix.'visit_history'
function new_day(){
global $apth, $tn_prefix, $db_link, $idir;
// ���� �� ���������� ���� �� ������� $tn_prefix.'options'
include_once($idir.'lib/f_stored_value.php');
$td = stored_value('today');
$d = getdate();
// ��� �� �� � ������� ������ �� �� ����� ����
if ($d['mday']==$td) return;
$dd = $d['year'].'-'.$d['mon'].'-'.$d['mday'];
// ����� �� �������� �� ���������� ���� ���� �������� �� ������� $tn_prefix.'pages'
$dt = db_select_m('ID,dcount','pages','`dcount`>0');
// �������� �� ���� ��������� �� ����� �������� � ������� $tn_prefix.'visit_history'
foreach($dt as $r){
  $q = "INSERT INTO `$tn_prefix"."visit_history` SET `page_id`=".$r['ID'].", `date`='$dd', `count`=".$r['dcount'].";";
  mysqli_query($db_link,$q);
}
// ������� �� ���������� ������ � ������� $tn_prefix.'options'
store_value('today',$d['mday']);
// ������ �� ���� �� ����������� � ������� $tn_prefix.'pages'
$q = "UPDATE `$tn_prefix"."pages` SET tcount = tcount + dcount, dcount = 0;";
mysqli_query($db_link,$q);
}

?>

