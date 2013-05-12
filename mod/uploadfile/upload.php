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

// �������� �� ������� �� ���� �� �����

if (!isset($_GET['fid'])) die("No upload id");
if (!isset($_GET['fn' ])) die("No upload name");

$idir = dirname(dirname(dirname(__FILE__))).'/';

include($idir.'lib/translation.php');
include($idir.'lib/o_form.php');
include_once($mod_apth.'user/f_user.php');

// �������� ���� ��� ������ ����������
user('new');

// ����� �� �������� ����������.
$pid = 1*$_GET['pid'];

// ����� �� ������ �� ����� � ������� $tn_prefix.'files'.
// ��� � 0 �� ���� �������� ��� �����.
$fid = 1*$_GET['fid'];

// ��� �� ����� �� �������� ����������.
$fn = addslashes($_GET['fn']);

// ������ ����� ������������� �� �����
$ftx = '';

// ������ �� ������� �� ����� �� ������� $tn_prefix.'files'.
$fd = db_select_1('*','files',"`pid`='$pid' AND `name`='$fn'");

if ($fd) $ftx = $fd['text'];

// ��� �� ��������� ����� �� ����������.
if (count($_POST) && !isset($_POST['password'])) process_data();
// ��� �� �� ��������� ����� �� ������� ����� �� �����������.
else { 
  show_form(); 
  include($idir.'lib/build_page.php');
}

// ------ ������� -------

//
// ��������� ����� �� ������� �� ����.
// 
function show_form(){
global $ftx, $page_content;

$page_content = '<h1>'.translate('uploadfile_upladpagetitle')."</h1>\n";

$uf = new HTMLForm('uploadform');
$tx = new FormInput(translate('uploadfile_linktext'), 'text', 'text', $ftx);  $tx->size = 80;  $uf->add_input( $tx );
$fl = new FormInput(translate('uploadfile_file'), 'file', 'file');            $fl->size = 70;  $uf->add_input($fl);
$uf->add_input(new FormInput('', '', 'submit', translate('uploadfile_submit')) );

$page_content .= $uf->html();
}

//
// ��������� �� ��������� �����.
//
function process_data(){
global $pid, $fid, $fd, $tn_prefix, $fn, $db_link, $pth;
// ��� �� ���������� �� ������� �� �������. ��� �� � �������� �����, ������� � ������������ �� ������.
$fld = current_pth(__FILE__);
$fld = $_SERVER['DOCUMENT_ROOT'].stored_value('uploadfile_dir',$fld); //echo "$fld<br>"; die;
// ��� �� ������� ����
$fln = $fld.$_FILES['file']['name'];
// ������ �� ����� �� ����� �� ���� ��� ������ ���.
$dt = db_select_1('*','files',"`filename`='$fln'");
// ��� ��� ����� ����� - ������������ ��� ��������� �� ������ 
if ($dt && ($dt['ID']!=$fid)){
  header("Content-Type: text/html; charset=windows-1251");
  die(translate('uploadfile_fileinuse'));
}
// ��� ��� ���� ���� �� ������� �� ���� �����, ������ �� �������.
if ($fd && file_exists($fd['filename'])) unlink($fd['filename']);
// �������� ���� ��� ���� �� ������� ��� ������ ���, ����� �� �� ������ �� ����� �����.
if (file_exists($fln) && (!$dt || ($dt['ID']!=$fid)) ){
  header("Content-Type: text/html; charset=windows-1251");
  die(translate('uploadfile_fileexists'));
}
// ����������� �� ������� ���� � ���������� �� ������� �� �������
if (!move_uploaded_file($_FILES['file']['tmp_name'], $fln)) die('Do not uploaded');
// ��������� �� ����� � ������
$w = '';
if ($fd) { $q = "UPDATE `"; $w = "WHERE `ID`=$fid"; }
else $q = "INSERT INTO `";
$q .= $tn_prefix."files` SET `pid`='$pid', `name`='$fn', `filename`='$fln', `text`='".addslashes($_POST['text'])."' $w;";
mysql_query($q,$db_link);
//print_r($q); die;
header("Location: $pth"."index.php?pid=$pid");
}

?>