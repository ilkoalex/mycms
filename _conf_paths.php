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

// ������ �� �������� �� ���� �� ����� ����������

// ���������� �� �������� ��������.
// ������ �� ��� ������ �� �������� ���� ������ � ���� ���� � �� ������ � ���������� �� ������� ����.
// ������ �� �������� � /.
$pth = '/';

// ��������� ���������� �� �������� �������� ��� ��������� ������� �� �������
$apth = $_SERVER['DOCUMENT_ROOT'].$pth;

// ���������� �� ��������������
$adm_pth = '/manage/';

// ��������� ���������� �� ������������ �� �������������� ��� ��������� ������� �� �������
$adm_apth = $_SERVER['DOCUMENT_ROOT'].$adm_pth;

// ����� �� phpMyAdmin �� ����������� ������ 
$phpmyadmin = $adm_pth.'db/index.php';

// ��� �� ckeditor
$ckpth = '/ckeditor/';

// ����� ���������, �� ����� �� ������ ����������� �� ���������������� ������ �� �����:
$adm_name = 'admin'; // ��� �� ����������, ����� �� ������� � GET �� �� �� ������ ������ �� �������������� 
$adm_value = 'on'; // �������� �� ����������, ����� �� ������� � GET �� �� �� ������ ������ �� �������������� 

$edit_name = 'edit';  // ��� �� ����������, ����� �� ������� � GET �� �� �� ���� � ����� �� �����������
$edit_value = 'on';  // �������� �� ����������, ����� �� ������� � GET �� �� �� ���� � ����� �� �����������

// ����� ������, ��� �� ��������� ������ �� ������������ �� ��������������
function in_admin_path(){
global $adm_pth;
return ( substr($_SERVER['PHP_SELF'],0,strlen($adm_pth))==$adm_pth );
}

?>
