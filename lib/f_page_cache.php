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

include_once($idir.'lib/f_stored_value.php');
include_once($idir.'lib/f_db_delete_where.php');

// � ���� ���� �� ��������� ��� �������, �������� � ���������� �� ��������

// page_cache() ���� html ���� �� ���������� �� ������� $tn_prefix.'page_cache'
// ������ ���������� �� �� �������� ������.
// ��� ���������� �� ������� �� ��������, ������� �� ����������� �� ���� � ������, �������� � �����������
// cache_time � �������, ��� �� � ��������, ����� ������ ������.
// �������� cache_time=-1 �������� ������� � ���� �� �� ���������, ���� ��� ��������� �� ����������.

// save_cache($cnt) ������� html ���� �� ���������� � ������� $tn_prefix.'page_cache'

function page_cache(){
// ������, � ����� �� �� �������� ���:
if (do_not_cache()) return '';
global $language, $page_data;
$t = stored_value('cache_time');
// �� � �������� ����� �� ��������, ��� �� � 0
if (!$t) return '';
// �������� ������ �����
$htp = acceptable($_SERVER['REQUEST_URI'],false);
// ������ �� ������� �� ��� ���������
$d = db_select_1('*', 'page_cache', 
     '`page_ID`='.$page_data['ID']." AND `name`='".addslashes($htp)."' AND `language`='$language'");
if (!$d) return '';
else{
  $td = time() - strtotime($d['date_time_1']);
  if ( !($t<0) && ($td > ($t*60)) ) return '';
  else return $d['text'];
}
}

//
// ��������� html ���� �� ���������� � ������� $tn_prefix.'page_cache'

function save_cache($cnt){
// ������, � ����� �� �� ������� ���
if (do_not_cache()) return;
global $language, $page_data, $tn_prefix, $db_link;
// ���������� ����� �� ������
$htp = acceptable($_SERVER['REQUEST_URI'],true);
// ��� ������� �� � ��������, �� �� ������� ���
if (!$htp) return;
$id = db_table_field('ID','page_cache',
      "`page_ID`=".$page_data['ID'].
      " AND `name`='".addslashes($htp).
      "' AND `language`='$language'");
if (!$id) $q = "INSERT INTO `$tn_prefix"."page_cache` SET ";
else      $q = "UPDATE `$tn_prefix"."page_cache` SET ";
if (isset($_SERVER['HTTP_REFERER'])) $r = ", `referer`='".addslashes($_SERVER['HTTP_REFERER'])."'";
else $r = '';
$q .= "`page_ID`=".$page_data['ID'].
      ", `name`='".addslashes($htp).
      "', `language`='$language', `date_time_1`=NOW(), `text`='".addslashes($cnt)."'".
      $r;
if ($id) $q .= " WHERE `ID`=$id;";
else $q .';';
mysqli_query($db_link,$q);
}

//
// ����� ������ ��� ������ ������, � ����� �� ������ �� �� ����� ��������

function do_not_cache(){
global $page_data;
if (!session_id()) session_start();
return
  ($page_data['ID']==0) ||
  (isset($page_data['donotcache']) && ($page_data['donotcache']==1)) ||
  in_edit_mode() || 
  count($_POST) || 
  (isset($_SESSION) && count($_SESSION)) || 
  (!is_local() && show_adm_links());
}

//
// �������� ���� �� ����� $a

function purge_page_cache($a){
$b = parse_url($a);
$c = array(); 
$d = $b['path'];
if (isset($b['query'])) parse_str($b['query'],$c);
if (isset($c['pid'])) $d .= 'pid='.$c['pid'];
if ($d>'/') db_delete_where('page_cache',"`name` LIKE '%$d%'");
}

//
// ����� ���������� ����� �� �������� �����
// ��� $y=true - ����� ������ ������ �� ���������� ���������
// ��� $y=false - ���� �������� ������������� ���������

function acceptable($u,$y){
$a = parse_url($u);
$b = array();
if (isset($a['query'])) parse_str($a['query'],$b);
$ka = array_keys($b);
$o = stored_value('acceptable_params');
foreach($ka as $k){
  if ($k=='lang') {
     unset($b[$k]);
     continue;
  }
  if (strpos($o,"=$k=")===false)
     if ($y) return '';
     else unset($b[$k]);
}
ksort($b);
$a['query'] = http_build_query($b);
$rz = $a['path'];
if ($a['query']) $rz .= '?'.$a['query'];
return $rz;
}

?>
