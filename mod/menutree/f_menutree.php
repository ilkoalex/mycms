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

// ��������� menu_tree() ������� �������� ��� �� �������� �������� ��� �������� ��������.
// ��������� �� �������� �� ������� $tn_prefix.`menu_tree`, ����� �������� ������������ ���� �� ����� ����. 

include_once($idir.'lib/f_db_select_1.php');

function menutree(){
global $pth, $ind_pth, $page_id, $page_data;
//global $p; // print_r($p);
$rz = '';
// ������ ������ �� ������ �� ����������
$pr = db_select_1('*','menu_tree',"`group`=".$page_data['menu_group']);
if (!$pr) return $rz;
// ������ ������ �� �������� �������� �� ������
$pg = db_select_1('*','pages','ID='.$pr['index_page']);
// ��� �������� �������� � ������ �� ������� ��� ����
if ($page_id==$pg['ID']) $rz = translate($pg['title']);
// ����� �� ������� � ����
else $rz = '<a href="'.$ind_pth.'index.php?pid='.$pg['ID'].'">'.translate($pg['title']).'</a>'.$rz;
// ��� ������ ��� �������� �� ������� � ��.
while ($pr['parent'])
{
  $pr = db_select_1('*','menu_tree',"`group`=".$pr['parent']); // print_r($pr); echo "<br>";
  $pg = db_select_1('*','pages','ID='.$pr['index_page']);
  if ($rz) $rz = " >> \n".$rz;
  $rz = '<a href="'.$ind_pth.'index.php?pid='.$pg['ID'].'">'.translate($pg['title']).'</a>'.$rz;
}
return '<p id="menu_tree">'."\n$rz\n</p>";
}

?>