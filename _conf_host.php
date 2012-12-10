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

// ���������� � �������, ����� ����� ���������� �� �������� � ����������������

$web_host = 'yoursite.org'; // ������ �� �����.

$local_host = 'test'; // ������� ������ �� �����, ����� �� � �������� ���� ��������.
                      // �������� ��, ������ �� ������� ������ ������� ����� �� �����.

$phpmyadmin_site = 'http://yoursite.org/phpmyadmin';     // ����� �� phpMyAdmin �� ����������� ������
$phpmyadmin_local = 'http://localhost/phpmyadmin'; // ����� �� phpMyAdmin �� �������� ������

// ����� ������, ��� ������ �� ������ �� ������� ������.
function is_local(){
global $local_host;
return $local_host==$_SERVER['HTTP_HOST'];
}

include($idir."lib/f_query_or_cookie.php");

// ��������� in_edit_mode() ����� ������ ��� ������ � � ����� �� �����������
// � ����� ����� �� �������� ������� �� ����������� �� �������, ��������, ������ � ��.
// ������ � � ����� �� ����������� ���:
// - ������ ��������� im=admin
// - ������ $_GET['im']=='admin'

function in_edit_mode(){
global $edit_name, $edit_value;
return query_or_cookie($edit_name,$edit_value);
}

?>
