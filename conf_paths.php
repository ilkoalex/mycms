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

$idir = dirname(__FILE__).'/';

include_once($idir.'lib/f_stored_value.php');

// ���������� �� �������� ��������.
// ������ �� ��� ������ �� �������� ���� ������ � ���� ���� � �� ������ � ���������� �� ������� ����.
// ������ �� �������� � /.
$pth = current_pth();

// ��������� ���������� �� �������� �������� ��� ��������� ������� �� �������
$apth = $_SERVER['DOCUMENT_ROOT'].$pth;

// ���������� �� ��������������
$adm_pth = stored_value('admin_path').'/';
if ($adm_pth[0]!='/') $adm_pth = $pth.$adm_pth;

// ��������� ���������� �� ������������ �� �������������� ��� ��������� ������� �� �������
$adm_apth = $_SERVER['DOCUMENT_ROOT'].$adm_pth;

// ����� �� phpMyAdmin �� ����������� ������ 
$phpmyadmin = $adm_pth.'db/index.php';

// ��� �� ckeditor
$ckpth = '/ckeditor/';

// ����� ���������, �� ����� �� ������ ����������� �� ���������������� ������ �� �����:

// ��� �� ����������, ����� �� ������� � GET �� �� �� ������ ������ �� �������������� 
$adm_name = stored_value('adm_name');
// �������� �� ����������, ����� �� ������� � GET �� �� �� ������ ������ �� �������������� 
$adm_value = stored_value('adm_value');

// ��� �� ����������, ����� �� ������� � GET �� �� �� ���� � ����� �� �����������
$edit_name = stored_value('edit_name');
// �������� �� ����������, ����� �� ������� � GET �� �� �� ���� � ����� �� �����������
$edit_value = stored_value('edit_value');;

// ����� ������, ��� �� ��������� ������ �� ������������ �� ��������������
function in_admin_path(){
global $adm_pth;
return ( substr($_SERVER['PHP_SELF'],0,strlen($adm_pth))==$adm_pth );
}

// ����� �������� ����������
function current_pth(){
$p1 = $_SERVER['DOCUMENT_ROOT']; $n1 = strlen($p1);
$p2 = dirname(__FILE__);         $n2 = strlen($p2);
$r = substr($p2,$n1,$n2-$n1).'/';
return $r;
}

?>
