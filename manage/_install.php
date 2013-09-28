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

// ������ �� ��������� �� ������� � ������ ����� �� ������� �� ��������� ��� ��������.
// SQL �������� �� ��������� � �������� �� ����� � ��������� �� ��� ���� tables.sql � 
// ���������� manage ��� � ������������ �� ���������� ����� mod/����������.
// $_GET['m'] � ����� �� ������, � ��� �� � ��������, �� ������� ���� conf_database.php 
// � �� ���������� �������� �� manage/table.sql

error_reporting(E_ALL); ini_set('display_errors',1);

include('conf_manage.php');

// ��� ���� conf_database.php ����, ��������� �� ����� �� ����������� ��
if (!file_exists($idir.'conf_database.php')) create_conf_database();

include($idir.'conf_paths.php');

$p = 'tables.sql';

// ��� � ��������� ��� �� �����, $p � .sql ���� �� ������������� �� 
if (isset($_GET['m'])) $p = $_SERVER['DOCUMENT_ROOT'].$mod_pth.$_GET['m']."/$p";

// ��� �� � ��������� ��� �� �����, �� ��������� ������ �������
else create_conf_database();

header("Content-Type: text/html; charset=windows-1251");

if (!file_exists($p)){ // ��� .sql ���� �� � � ���������� $mod_pth �� ��������� � ���������� 'mod'
  $p = $_SERVER['DOCUMENT_ROOT'].$pth.'mod/'.$_GET['m']."/tables.sql";
  if (!file_exists($p)) die("$p file not found");
}

$fc = file_get_contents($p);

$fc = str_replace('CREATE TABLE IF NOT EXISTS `',   "CREATE TABLE IF NOT EXISTS `$tn_prefix",$fc);

$fc = str_replace('INSERT INTO `',   "INSERT INTO `$tn_prefix",$fc);

$fa = explode('-- --------------------------------------------------------',$fc);

foreach($fa as $q){
//  echo "$q<p>";
  mysqli_query($db_link,$q);
}

echo '<p>Success</p>

<p><a href="'.$pth.'">Go next</a></p>';

// 
// �������, ��������� ����� �� ��������� �� �������, ����� ������
// �� �� ������� ��� ���� conf_database.php.
//
function create_conf_database(){
global $idir;
include_once($idir.'lib/o_form.php');
// ��� ���� conf_database.php ���� ����������
if (file_exists($idir.'conf_database.php')){
  // ��� ���� � ���������� �� �� ��������
  if (isset($_POST['continue'])&&($_POST['continue']=='yes')) return;
  // ������� �� ����� �� ������������
  $f = new HTMLForm('pform'); $f->astable = false;
  $i = new FormInput('','continue','hidden','yes'); $f->add_input($i);
  $i = new FormInput('Click the button to ','','submit','continue'); $f->add_input($i);
  echo '<p>File '.$idir.'<strong>conf_database.php</strong>'.' exists.</p>
  '.$f->html().'
  <p>Or remove it to start a new instalation.</p>';
  die;
}
$f = new HTMLForm('pform');
$i = new FormInput('Database','database','text'); $f->add_input($i);
$i = new FormInput('User','user','text'); $f->add_input($i);
$i = new FormInput('Password','password','text'); $f->add_input($i);
$i = new FormInput('Table prefix','prefix','text'); $f->add_input($i);
$i = new FormInput('','','button','Save'); 
$i -> set_event('onclick','ifNotEmpty_pform();');
$f->add_input($i);
if (count($_POST)) process_data();
else { echo $f->html(); die; }
}

//
// ������� �� ��������� �� ����������� � $_POST �����,
// ����� ������� conf_database.php �����.
//
function process_data(){
global $idir;
// ���������� �� conf_database.php �����
$s = '<?php
/*
MyCMS - a simple Content Management System
Copyright (C) 2012  Vanyo Georgiev <info@vanyog.com>

This file is generated by _install.php script
*/

$database ="'.$_POST['database'].'";
$user     ="'.$_POST['user'].'";
$password ="'.$_POST['password'].'";
$tn_prefix = "'.$_POST['prefix'].'";
?>
';
// ��� ������������ � ��������� �� ���� - ���������
if (!is_writable($idir)) {
  echo "<p>Can't write to file ".$idir.'<strong>conf_database.php</strong>'.'</p>
<p>Please, create it manually with the following content:</p>
';
  echo '<textarea rows="20" cols="100">'.htmlentities($s).'</textarea>';
  die;
}
// ��������� �� �����
$f = fopen($idir.'conf_database.php','w');
if ($f){
  fwrite($f,$s);
  fclose($f);
}
}

?>
