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

// ��������� menu($i) ������� ���������������� �� ����������� (����).
// $i � ����� �� ������� ����������� �� ������� $tb_preffix.'menu_items'

include_once($idir."lib/f_is_local.php");
include_once($idir.'conf_paths.php');
include_once($idir.'lib/f_db_select_m.php');

function menu($i, $id = 'page_menu'){
global $ind_fl, $adm_pth, $page_id, $page_data;
$d = db_select_m('*','menu_items',"`group`=$i ORDER BY `place`");
$rz = '';  $once = false;
foreach($d as $m){
  $lnn = 1*$m['link'];
  $ln = $m['link']; 
  if ($lnn) $ln = $ind_fl.'?pid='.$lnn;
  $pl = '';
  if (in_edit_mode()) $pl = $m['place'];
  if ($once || !is_parrent_menu($i, $m['link']))
     $rz .= '<a href="'.$ln.'">'.$pl.translate($m['name']).'</a> '."\n";
  else {
     $once = true;
     $rz .= '<span>'.$pl.translate($m['name'])."</span> \n";
  }
}
if (in_edit_mode()){
  $ni = db_table_field('MAX(`ID`)','menu_items','1')+1;
  $rz .= "id $i ".'<a href="'.$adm_pth.'new_record.php?t=menu_items&group='.$i.'&link='.$page_id.
         '&name=p'.$ni.'_link">New</a> '."\n";
}
if ($rz) $rz = "\n<div id=\"$id\">\n$rz</div>\n";
return $rz;
}

//
// ���� ������� ��������� ���� �� ���� � ����� $i, ������� �� �������� $mlk,
// ��� ����� ���� ����� �� ���� ����, ��� ����� �������� �� ������ �����������
// �� ����� � ������. ��� � ���� ����� ������. �������� �� �� ���������� ����
// � ������ ���������� �� �� ������ � ���� ��� ��.

function is_parrent_menu($i, $mlk){
global $page_data;
  // ��� ����������, ��� ����� ���� ������� �� ������ � �������� - ������
  if ($mlk==$page_data['ID']) return true;
  // ��� ���� $i � ������ �� �������� �������� �� ����� ������ - ��������
  if ($i==$page_data['menu_group']) return false;
  // ������� � ������� �� ��������
//  echo "++$mlk++<br>";
  return is_subpage_of($mlk);
}

//
// ��������� ���� �������� �������� e ����������� �� �������, � ����� � �������� � ����� $i

function is_subpage_of($i, $frst = true){//   echo "==$i==<br>";
global $page_id;
  // �� ��������� ��� ������ �� ���������
  static $chm = array();
  // �� ��������� ��� �������� �� ���������
  static $chp = array();
  // �������������� �� $chm � $chp
  if ($frst){ $chm = array(); $chp = array(); }

  // ��� �������� $i � ������, ����� ������ 
  if ($i==$page_id) return true;

  // ��� ���������� �� � ��������� �� ���������
  if (!in_array($i,$chp)){
     // �������� ��� ����������� ��������
     $chp[] = $i;
//     echo "��������� ��������: ".print_r($chp,true)."<br>";
     // ����� �� ������, ��� ����� ���������� ���������� � ����� $i 
     $ip = db_table_field('menu_group','pages',"`ID`=$i");
     // ��� ���������� ���� ����, ����� ��������
     if ($ip==0) return false;
     // ��� ���� ���� ��� �� � ���������, �� ���������
     if ($ip && !in_array($ip,$chm))
     {
        // �������� ��� ����������� ������
        $chm[] = $ip;
//        echo "��������� ������: ".print_r($chm,true)."<br>";
        // �������� � ���� $ip
        $pgs = db_select_m('ID','pages',"`menu_group`=$ip");
//        echo "--$ip ".print_r($pgs,true)."<br>";
        foreach($pgs as $pg){
          // ��������� �� ����������
          $y = is_subpage_of($pg['ID'], false);
          if ($y) return true;
        }
     }
     // ���������, ����� ��� �� ������� ���� $ip
     $ms = db_select_m('`group`','menu_tree',"`parent`=$ip");
//     echo "���������: ".print_r($ms,true)."<br>";
     foreach($ms as $m){
        // �������� ��� ����������� ������
        $chm[] = $m['group'];
        // �������� � ���� $m['group']
        $pgs = db_select_m('ID','pages',"`menu_group`=".$m['group']);
//        echo "--".$m['group']."--".print_r($pgs,true)."<br>";
        foreach($pgs as $pg){
          // ��������� �� ����������
          $y = is_subpage_of($pg['ID'], false);
          if ($y) return true;
        }
     }
  }
//  echo "-$i-<br>";
  return false;
}

?>