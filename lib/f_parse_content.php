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

// ��������� parse_content($cnt) �������� ���������� <!--$$_XXX_$$--> � ������� $cnt
// ��� ����������, ���������� �� php ���������, ����� �� ���������� � ������� $tn_prefix.'scripts'
// ��� ������ �� ���������� mod

include_once($idir.'lib/f_translate.php');
include_once($idir.'lib/f_adm_links.php');

function parse_content($cnt){
global $page_options, $page_data, $content_date_time, $body_adds, $page_header, $idir, $adm_pth, $apth, $mod_pth, $idir;

$l = strlen($cnt);
$str1 = '<!--$$_'; // ��������� �� ������ �� ����������� �������
$str2 = '_$$-->';  // ��������� �� ���� �� ����������� �������

// ����� �� ���������� �� ��������
// $p0 - ������� �� ������� ���������� �����
while ( !(($p0 = strrpos($cnt,$str1))===false) ){

$p1 = $p0 + strlen($str1); // ������� �� ������ ������ �� ����� �� ��������
$p2 = strrpos($cnt,$str2); // ������� �� ������ ������� ���� ����������� ������ 
$p3 = $p2 + strlen($str2); // ������� �� ��������� ��������� ������

// �������� �� ����� �� ����������
$tg = explode('_',substr($cnt,$p1,$p2-$p1),2);

$tx = ''; // Html ���, ����� �� ������� ��������

// ������ �� ������� � ��� $tg[0] �� ������� $tn_prefix.'scripts'
$sc = db_select_1('*','scripts',"`name`='".$tg[0]."'");

if (!$sc){ // ��� ���� ����� ������ �� ����� ����� � ���� ���
  // ������ �� ������ �� ��� ����� - ����� � ���������� mod
  $f = strtolower($tg[0]);
  $fn = "$idir/mod/$f/f_$f.php"; 
  $afn = $_SERVER['DOCUMENT_ROOT']."$fn";
  // � ����� � ������������, �������� � ����������� mod_path, ��� � �������� �������� �� /mod/
  if ( ($mod_pth!='/mod/') && !file_exists($afn) ){
    $fn = "$mod_pth$f/f_$f.php";
    $afn = $_SERVER['DOCUMENT_ROOT']."$fn";
  }
//  print_r($afn); die;
  if (file_exists($afn)){
    $c = "include_once('$afn');\n";
    if (isset($tg[1])) $c .= '$tx = '."$f('$tg[1]');";
    else $c .= '$tx = '."$f();";
    eval($c); // ��������� �� ������
  }
  else { // ��� ���� ����� �� ������� ���� �� ����������� ��������� �� �����
    if (show_adm_links()) $tx = '<p>Can\'t parse content <a href="'.$adm_pth.'new_mod.php?n='.$tg[0].'">'.$tg[0].'</a></p>';
    else $tx = '<p>Can\'t parse content '.$tg[0].'</p>';
  }
}
else eval(stripslashes($sc['script'])); // ��������� �� �������

// ���������� �� �������� � ����������� html ���, ����� � �������� �� $tx
$cnt = substr_replace($cnt,$tx,$p0,$p3-$p0);

} // ���� �� ������ �� ��������� �� ����������

return $cnt;

} // ���� �� ��������� parce_content()

?>
