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

// ����������� �� �����, ���� �������� ����� ����� * ��� ���� ����� � ����� �� �����������

if (!isset($_GET['pid']) || !isset($_GET['i'])) die('Insufficient parameters.');

$idir = dirname(dirname(dirname(__FILE__))).'/';
$ddir = $idir;

include("f_usermenu.php");
include_once($idir."lib/translation.php");
include_once($idir."lib/f_edit_record_form.php");
include_once($idir."lib/f_db_insert_1.php");
include_once($idir."lib/f_page_cache.php");
include_once($idir."/lib/f_mod_picker.php");

// ����� �� ����������, �� ����� � ������
$page_id = 1*$_GET['pid'];

// ����� �� ����������
$page_data = db_select_1('*', 'pages', "`ID`=$page_id");

// �������� �� ������� �� �����������
usermenu(true);

// ���� ��� ���� ����� �� ���������
if (!$can_edit) die('You have no permission to edit this text');

$page_header = '<link href="'.$pth.'_style.css" rel="stylesheet" type="text/css">'."\n";

// ����� �� ������ �� ������� content
$i = 1*$_GET['i'];

$cp = array(
'ID' => 1*$_GET['i'],
'text' => translate('usermenu_texttoedit')
);


$page_content = '<h1>'.translate('usermenu_edittext').'</h1>
<p>Name: '.db_table_field('name','content','`ID`='.(1*$_GET['i']))."</p>\n".
mod_picker();

// ����������� �� ��������� �����
if (count($_POST)){
  if ($i) process_record($cp, 'content');
  else {
  db_insert_1(array(
'name' => addslashes($_GET['i']),
'date_time_1'=>'NOW()',
'date_time_2'=>'NOW()',
'language' => addslashes($_GET['lang']),
'text' => addslashes(element_correction($_POST['text']))
), 'content');
  }
  purge_page_cache($_SESSION['http_referer']);
  header('Location: '.$_SESSION['http_referer']);
}
else if (isset($_SERVER['HTTP_REFERER'])) $_SESSION['http_referer'] = $_SERVER['HTTP_REFERER'];

// ����� �� ����������� �� ������
$page_content .= edit_record_form($cp, 'content');

$pt = $_SESSION['http_referer'];

$page_content .= '<p><a href="'.$pt.'">'.translate('usermenu_back').'</a></p>';

include($idir."lib/build_page.php");

?>
