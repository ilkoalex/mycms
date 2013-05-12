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

include_once($idir.'lib/translation.php');
include_once($idir.'lib/o_form.php');
include_once($idir.'lib/f_db_table_field.php');

// ��������� user() ��������� ���� ��� ������ � ������ ����������.
// ��� ���� �����, ������� ����� �� �������.
// ��� ��������������� ��� ��� �������� �� ��������� ����� "Access denied."
// ��� ��������������� ��� � �������� �� ������� ����� ������ ���.
// ��� � �������� ��������� $a='new' �� ������� ����� �� ��������� ������� �� ��� ����������.

session_start();

function user(){
if (show_adm_links()) return '';
// ��� ���� ������ ���������� �� ������ �������� �� �������.
if (!isset($_SESSION['user_username'])) get_user();
$rz = db_table_field('ID','users',
      "`username`='".addslashes($_SESSION['user_username'])."' AND `password`='".$_SESSION['user_password']."'");
if (!$rz) { session_destroy(); header("Status: 403"); die("Access denied."); }
return '';
}

// ��������� get_user() ����� HTML ��� � ����� �� ������� �� ����������

function get_user(){
// ��� ������� �� ������� ���� � ���������, �� ���������� ����������� � ��� �����
if (isset($_POST['username'])){ process_user(); return; }
global $idir;
$guf = new HTMLForm('login_form');
$guf->add_input( new FORMInput(translate('user_username'),'username','text') );
$guf->add_input( new FORMInput(translate('user_password'),'password','password') );
$guf->add_input( new FORMInput('','','submit',translate('user_login_button')) );
$page_title = translate('user_login');
$page_content = "<h1>$page_title</h1>".$guf->html().'';
include($idir.'lib/build_page.php');
die;
}

// ��������� process_user() ��������� ������� �� ������� �� ����������� - 
// ��������� �� �� ����������� ���������� �� �������

function process_user(){
$_SESSION['user_username'] = $_POST['username'];
if (isset($_POST['password'])) $_SESSION['user_password'] = sha1($_POST['password']); else $_SESSION['user_password'] = '';
//print_r($_SESSION); die;
}

?>
