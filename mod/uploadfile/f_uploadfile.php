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

// ����� �� ������� �� �������
// ��������� uploadfile($n) �������� html ���� �� ����������� ���
// ����� �� ������� ����.
// � ����� �� ����������� ��� ������������� �� �������� �����:
// +  �� ������� �� ���� � 
// -  �� ��������� �� ������� ����.

function uploadfile($n){
global $mod_pth, $page_id;
// �� ����� ������, ��� �� � ��������� ����� �� ��������.
if (!isset($page_id)) $page_id = 1*$_GET['pid'];
$pid = $page_id;
// ��������� �� ���������� �� ��� � �����.
$na = explode(',',$n);
// ��� � �������� � ����� �� �������� - ���������� �� $n � $pid
if (isset($na[1])){ $pid = 1*$na[1]; $n = $na[0]; }
// ������ ��������
$rz = '';
// ������ �� ������� �� �����
$fr = db_select_1('*','files',"`pid`=$pid AND `name`='$n'");
$ne = false;
if (!$fr){ // ��� ���� ����� �� ���� - ������ "���� ����� ����"
  $rz .= translate('uploadfile_nofile');
  $fid = 0;
}
else {
  $l = strlen($_SERVER['DOCUMENT_ROOT']);
  $ne = $_SERVER['DOCUMENT_ROOT'] != substr($fr['filename'], 0, $l);
//  echo $_SERVER['DOCUMENT_ROOT']."<br>".substr($fr['filename'], 0, $l); die;
  $f = substr($fr['filename'], $l, strlen($fr['filename'])-$l);
  if (!$fr['filename'] || $ne) $rz .= stripslashes($fr['text']);
  else $rz .= '<a href="'.$f.'">'.stripslashes($fr['text']).'</a>';
  $fid = $fr['ID'];
}
if (show_adm_links()){
  $cp = current_pth(__FILE__);
  $rz .= ' <a href="'.$cp."upload.php?pid=$pid&amp;fid=$fid&amp;fn=$n"."\" title=\"Update\">+</a>\n";
  if ( isset($fr['filename']) && $fr['filename'] && !$ne ) 
    $rz .= ' <a href="'.$cp."delete.php?fid=$fid".'" title="Delete" onclick="return confirm(\''.
      translate('uploadfile_confdel').$f.' ?\');">-</a>'."\n";
}
return $rz;
}

?>