<?php
/* 
MyCMS - a simple Content Management System
Copyright (C) 2012 Vanyo Georgiev <info@vanyog.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// ����������� "Edit/Normal" �� ������������ �� ����� �� ����������� ��� �������� �����.

include_once($idir."lib/f_is_local.php");
include_once($idir."lib/f_set_query_var.php");

function edit_normal_link(){
global $edit_name, $edit_value;
if (in_edit_mode()) 
  return '<a href="'.$_SERVER['PHP_SELF'].'?'.set_query_var($edit_name,'0').'" title="Switch to normal mode">Page Normal</a>';
else
  return '<a href="'.$_SERVER['PHP_SELF'].'?'.set_query_var($edit_name,$edit_value).'" title="Switch to edit mode">Page Edit</a>';
}

?>
