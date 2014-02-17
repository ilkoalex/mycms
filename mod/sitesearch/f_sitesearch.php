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

// ����� �� ������� � �����

include_once($idir.'lib/o_form.php');
include_once($idir.'lib/translation.php');
include_once($idir.'lib/f_db_select_m.php');

// 
// ������� ������� �� ������, ����� ����� html ��� �� ����� �� �������,
// �� ��� ���� �� ��������� ����� �� �������, ����������� �� ������� ������
// �� ������� � ����� � �� ����� �� ���������� �� ��������� �� ��������.
// ��� �� ��������� � �������� ��������� $r='result' �� ����� ��������� �� ���������.
//

function sitesearch($r=''){
if ($r=='result') return site_search_result();
if (isset($_POST['text'])) do_site_search();
$f = new HTMLForm('site_search_form',false);
$f->add_input(new FormInput('','text','text'));
$f->add_input(new FormInput('','','submit',translate('sitesearch_submit')));
return $f->html();
}

//
// ���� ������� ������� ����������� �� ������� ������ � $_SESSION
// � ����� ������������ ��� ���������� �� ��������� �� ��������.
function do_site_search(){
  if (!$_POST['text']) return;
  if (!session_id()) session_start();
  $_SESSION['text_to_search']=$_POST['text'];
  $l = stored_value('sitesearch_resultpage');
  if (!$l) $l = current_pth(__FILE__).'result.php';
  header("Location: $l");
}

//
// �������, ����� ����� ���������� �� ���������
//
function site_search_result(){
global $language, $pth;
  if (!session_id()) session_start();
  if (!isset($_SESSION['text_to_search'])) return translate('sitesearch_notext');
  $ts = $_SESSION['text_to_search'];
  // ��������� �� ������ �� ������� �� ����
  $wa = explode(' ',$ts);
  // ������� ������� �� ���������, � ����� �� ������ ������ ����
  $q = where_part($wa,'AND');
  $r = db_select_m('name','content',"$q AND `language`='$language'");
  // ��� �� ����� ������� �� ������ ������� �� ���������, � ����� �� ������ ���� ��������� ����
  if (!count($r)){
    $q = where_part($wa,'OR');
    $r = db_select_m('name','content',"$q AND `language`='$language'");
  }
  $nf = '<p>'.translate('sitesearch_notfound').'"'.$_SESSION['text_to_search'].'"'.'</p>';
  if (!count($r)) return $nf;
  // ������ �������� �� ����������, ����� ���� �� ����������, ���������� ���������
  $q = '';
  foreach($r as $i)
    if ($q) $q .= " OR `content`='".$i['name']."'"; 
    else $q .= "`content`='".$i['name']."'";
  // ������������ �������, ����� ���������� ���������� �� �� �� �������� � ���������
  $w = stored_value('sitesearch_restr');
  if ($w) $q = "( $q )$w";
  $pa = db_select_m('`ID`,`title`','pages',$q);
  if (!count($pa)) return $nf;
  $rz  = '<p>'.translate('sitesearch_searchfor').": \"$ts\"<br>\n";
  $rz .= translate('sitesearch_count').': '.count($pa)."</p>\n";
  foreach($pa as $p){
    $t = db_table_field('text','content',"`name`='".$p['title']."' AND `language`='$language'");
    $rz .= "<a href=\"$pth"."index.php?pid=".$p['ID']."\">$t</a><br>\n";
  }
  return $rz;
}

//
// ������� WHERE ������ �� SQL �������� �� ������� �� ����
// $wa - ����� �� ����
// $o  - ���������, ����� � 'AND' ��� 'OR'
//
function where_part($wa,$o){
  $q = '';
  foreach($wa as $w){
    $w1 = addslashes(trim($w));
    if ($w){
       if ($q) $q .= " $o `text` LIKE '%$w1%'";
       else $q .= "`text` LIKE '%$w1%'";
    }
  }
  return $q;
}
?>