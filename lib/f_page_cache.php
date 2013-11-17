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

// � ���� ���� �� ��������� ��� �������, �������� � ���������� �� ��������

// page_cache() ���� html ���� �� ���������� �� ������� $tn_prefix.'page_cache'
// ������ ���������� �� �� �������� ������.
// ��� ���������� �� ������� �� ��������, ������� �� ����������� �� ����, �������� � �����������
// cache_time � �������, ��� �� � ��������, ����� ������ ������.

// save_cache($cnt) ������� html ���� �� ���������� � ������� $tn_prefix.'page_cache'

function page_cache(){
global $page_data;
// ���������� �� ������� �� ��������
if ( (count($_GET)==1) && !isset($_GET['pid']) ) return '';
if (count($_POST) || (count($_GET)>1) || !isset($page_data['donotcache']) || $page_data['donotcache']) return '';
$t = stored_value('cache_time');
// �� � �������� ����� �� ��������, ��� �� � 0
if (!$t) return '';
// ������ �� ������� �� ��� ���������
$d = db_select_1('*','page_cache','`page_ID`='.$page_data['ID']);
if (!$d) return '';
else{
  $td = time() - strtotime($d['date_time_1']);
  if ($td > ($t*60)) return '';
  else return $d['text'];
}
}

function save_cache($cnt){
global $page_data, $tn_prefix, $db_link;
$id = db_table_field('page_ID','page_cache',"`page_ID`=".$page_data['ID']);
if (!$id) $q = "INSERT INTO `$tn_prefix"."page_cache` SET ";
else $q = "UPDATE `$tn_prefix"."page_cache` SET ";
$q .= "`page_ID`=".$page_data['ID'].", `date_time_1`=NOW(), `text`='".addslashes($cnt)."'";
if ($id) $q .= " WHERE `page_ID`=$id;";
else $q .';';
mysqli_query($db_link,$q);
}

?>
