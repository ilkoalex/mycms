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
if (!session_id()) session_start();
if (isset($_SESSION['text_to_search'])){
  // ����� "�������� ��������"
  $p = stored_value('sitesearch_resultpage');
  $b = new FormInput('','','button',translate('sitesearch_last'));
  $b->js = 'onclick="document.location=\''.$p.'\';"';
  $f->add_input( $b );
  // ����� "����������"
  $p = current_pth(__FILE__).'clear.php';
  $b = new FormInput('','','button',translate('sitesearch_clear'));
  $b->js = 'onclick="document.location=\''.$p.'\';"';
  $f->add_input( $b );
}
return $f->html();
}

//
// ���� ������� ������� ����������� �� ������� ������ � $_SESSION
// � ����� ������������ ��� ���������� �� ��������� �� ��������.
//
function do_site_search(){
  if (!trim($_POST['text'])) return;
  if (!session_id()) session_start();
  $_SESSION['text_to_search']=trim($_POST['text']);
  $_SESSION['sitesearch_saved']=0;
  $l = stored_value('sitesearch_resultpage');
  if (!$l) $l = current_pth(__FILE__).'result.php';
  header("Location: $l");
}

//
// �������, ����� ����� ��������� �� ���������
//
function site_search_result(){
global $language, $pth;
  if (!session_id()) session_start();
  if (!isset($_SESSION['text_to_search'])) return translate('sitesearch_notext');
  $ts = $_SESSION['text_to_search'];
  // ��������� �� ������ �� ������� �� ����
  $wa = array_unique(explode(' ',$ts));
  // ��������� �� ���������� �� ������, �� ����� �� �����
  site_search_stat($wa);
  // ������� ������� �� ���������, � ����� �� ������ ������ ����
  $q = where_part($wa,'AND');
  // ����������� ������
  $msg = translate('sitesearch_allwords');
  $r = db_select_m('name','content',"($q) AND `language`='$language'");
  // ��� �� ����� ������� �� ������ ������� �� ���������, � ����� �� ������ ���� ��������� ����
  if (!count($r)){
    $q = where_part($wa,'OR');
    $r = db_select_m('name','content',"($q) AND `language`='$language'");
    $msg = translate('sitesearch_anyword');
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
  if ($w && !in_edit_mode() && !show_adm_links()) $q = "( $q )$w";
  $pa = db_select_m('`ID`,`title`','pages',"$q GROUP BY `content`");
  if (!count($pa)) return $nf;
  $rz  = '<p>'.translate('sitesearch_searchfor').": \"$ts\"<br>\n";
  $rz .= translate('sitesearch_count').': '.count($pa)."</p>\n";
  $rz .= "<p>$msg</p>\n";
  foreach($pa as $p){
    $t = db_table_field('text','content',"`name`='".$p['title']."' AND `language`='$language'");
    if (!$t) $t = "No title";
    $mi = stored_value('sitesearch_indexfile', $pth."index.php");
    $rz .= "<a href=\"$mi"."?pid=".$p['ID']."\">$t</a><br>\n";
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
//       if ($q) $q .= " $o `text` LIKE '%$w1%'";
//       else $q .= "`text` LIKE '%$w1%'";
      if (strlen($w>3)){
       if ($q) $q .= " $o MATCH (`text`) AGAINST ('$w1')";
       else $q .= "MATCH (`text`) AGAINST ('$w1')";
      }
      else {
       if ($q) $q .= " $o `text` REGEXP '".$w1."'";
       else $q .= "`text` REGEXP '".'[[:<:]]'.$w1.'[[:>:]]'."'";
      }
    }
  }
  return $q;
}

// ��������� �� ���������� �� ������, �� ����� �� �����
// ����������� �� ��� ��� ����� sitesearch_stat ��� �������� 1

function site_search_stat($wa){
// ��� ���� ����� sitesearch_stat - ����
if (!stored_value('sitesearch_stat')) return;
// � ����� �� �������������� ��� ����������� - ����
global $can_edit;
if ( show_adm_links() || $can_edit ) return;
// ��� ������ ���� �� �������� - ����
if ($_SESSION['sitesearch_saved']) return;
global $db_link,$tn_prefix;
foreach($wa as $w){
 $w1 = addslashes(trim($w));
 // ����� �� ������, ��� ���� � ��������
 $id = db_table_field('ID', 'sitesearch_words', "`word`='$w1'");
 if ($id){ $q1 = "UPDATE `$tn_prefix"."sitesearch_words` SET "; $q2 = " WHERE `ID`=$id;"; }
 else { $q1 = "INSERT INTO `$tn_prefix"."sitesearch_words` SET `date_time_1`=NOW(), "; $q2 = ';'; }
 $q = $q1."`date_time_2`=NOW(), `word`='$w1', `count`=`count`+1, `IP`='".$_SERVER['REMOTE_ADDR']."'".$q2;
 mysqli_query($db_link,$q);
}
$_SESSION['sitesearch_saved']=1;
}

?>