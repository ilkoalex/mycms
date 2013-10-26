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

// ����������� ������� �� ������ ���������� � ��������� �� ���� � ����������� �� ��������.
// ������ $nom=false (�� ������������) �� ������� ���� � ����������� �� ������� ���������� ��������.
// ��� $nom=true ���� �� ���������� ������� ��� �� �� ������� ����.

include_once($idir."conf_paths.php");
//include_once($idir."lib/f_db_select_1.php");
include_once($idir."lib/f_db_select_m.php");
//include_once($idir."lib/f_db_table_field.php");
include_once($idir."lib/f_edit_normal_links.php");

if (!session_id()) session_start();

function usermenu($nom = false){

global $page_data, $can_edit, $can_create, $can_manage, $pth, $page_header;

// ��� � ������� ���� ����� �� ����������, ����� ������ ������.
if (!isset($_SESSION['user_username'])||!isset($_SESSION['user_password'])) return '';

// $id - ����� �� ������ ����������
$id = db_select_1('ID','users', 
      "`username`='".addslashes($_SESSION['user_username'])."' AND `password`='".$_SESSION['user_password']."'");

// ��� ���� ���������� ��� ���������� � ������� ��� � ������, ����� ������ ������.
if (!$id) return '';
$id = $id['ID'];

// ������ �� ������� �� �����������
$p = db_select_m('*', 'permissions', "`user_id`=$id");// print_r($p); die;
$rz = '';

// ������������ �� ������� �� ���������� ������
$can_edit = false; // ����� �� ����������� �� ��������� ��������� �� ���������� 
$can_create = false; // ����� �� ����������� �� ������/������� �������� � ������� ������(�������) �� �����
$can_manage = array(); // ����� �� �������������� �� ������

foreach($p as $q) switch($q['type']) {
case 'menu':// print_r($page_data); die;
  $can_create = in_that_branch($page_data['menu_group'], $q['object']) && $q['yes_no'];
  $can_edit = $can_create;
  break;
case 'page':
  if ($q['object']==$page_data['ID']) $can_edit = $q['yes_no'];
  break;
case 'module':
  $can_manage[$q['object']]=$q['yes_no'];
  break;
}

// ��������� �� ������
$pt = current_pth(__FILE__);
if ($can_create){
 $rz .= '<a href="'.$pt.'new_page.php?p='.$page_data['ID']."\">New page</a><br>\n";
 // ���� �� ���������� � �������
 $gc = db_table_field('COUNT(*)','menu_items','`group`='.$page_data['menu_group']);
 // ������ �� �������� �������� �� �������
 $mi = db_table_field('index_page','menu_tree','`group`='.$page_data['menu_group']);
 // �������� �������� �� ����� � �������� �������� �� ������, � ����� ��� � ����� ��������,
 // �� ����� �� �� �����
 if ($can_edit && ($page_data['ID']>1) && ( ($gc==1)||($mi!=$page_data['ID']) ) ){
  $page_header = '<script type="text/javascript"><!--
function confirm_page_deleting(){
if (confirm("'.translate('usermenu_confirdeleting').'")) document.location = "'.$pt.'delete_page.php?pid='.$page_data['ID'].'";
}
--></script>';
  $rz .= '<a href="" onclick="confirm_page_deleting();return false;">Delete page</a><br>'."\n";
 }
}
if ($can_edit) $rz .= edit_normal_link();
if ($nom) return '';
else return '<div id="user_menu">'."\n".$rz."\n</div>";
}

//
// ��������� ���� ������ �� ���������� � ������� �� ����������� ����
//
function in_that_branch($pi,$j){// echo "$pi $j<br>";
if ($pi==$j) return true;
$rz = false;
do{
 $pi = db_table_field('parent', 'menu_tree', "`group`=$pi");// print_r($pi);// die;
 $rz = $pi==$j;
} while ( !($rz || ($pi==0)) );
//echo "$rz $pi"; die;
return $rz;
}

?>