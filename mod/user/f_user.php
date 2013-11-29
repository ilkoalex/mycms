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
include_once($idir.'lib/f_set_self_query_var.php');
include_once($idir.'lib/f_unset_self_query_var.php');
include_once($idir.'lib/f_edit_record_form.php');

// ��������� user() ��������� ���� ��� ������ � ������ ����������.
// ��� ���� �����, ������� ����� �� ������� �
// ��� ��������������� ��� ��� �������� �� ��������� ����������� "Access denied."
// ��� ��������������� ��� � �������� �� ������� ����� ��������� "�����".
// ��� � �������� ��������� $_GET['user']='newreg' �� ������� ����� �� ��������� �� ����� �� ��� ����������,
// ��� �������, �� ��� ������ ���������� � ����� �� ������� ����� �����������.
// ��� � �������� ��������� $_GET['user']='logout' �� ����������� �������� �� �����������. 
// $a = 'login' ��������, �� ����������, �� ����� �� ���� user, � ��������� �������� �� ������� � ���� �������
// ������� ����� ���������� ��� ����� stored_value('user_loginpage',''), ��� � ������� �����.
// $a = 'edit' ��������, �� ����������, �� ����� �� ���� user, � �������� �� ����������� ������� ��
// ����������� � ����� ����� �� ����������� �� ���� �����.
// $a = 'enter' ��������, ��� ���� ������ ���������� �� �� ������� ���� "����".
// $a = 'create' ����� ����� �� ��������� �� ��� ����������, ��� �������, �� ��� ������ ���������,
// � ����� �� ������� ����� �����������.

if (!session_id()) session_start();


function user($a = ''){
global $tn_prefix, $db_link, $user_table;
//if (show_adm_links()) return '';
// ��� � �������� ���� "�����"
if (isset($_GET['user'])&&($_GET['user']=='logout')) logout_user();
$rz = '';
// ��� �� ��������� � ����� �� �����������
$user_table = stored_value('user_table','users');
// $c - ���� �� �������� � ����������� � ������� $user_table
$c = db_table_field('COUNT(*)',$user_table,'1');// print_r($c); die;
// ������ - ����� ���� ������� $user_table.
if ($c===false) die("Table '$user_table' is not set up.");
// ��� ���� �����������, �� ������� ��� ����������.
if ( !$c && (!isset($_GET['user']) || ($_GET['user']!='newreg')) ){ return new_user($a); }
// ��� ���� ������ ���������� �� ������ �������� �� �������.
if (!isset($_SESSION['user_username'])){
  // �� ��� $a == 'enter' �� ������� ����������� "����"
  // ����� ��� �� � �������� ��������� user=enter ��� ��� ��� ���� ������������ �����������.
  if ( ($a == 'enter') && $c && (!isset($_GET['user'])||($_GET['user']!='enter')) ) return enter_link();
  $rz = get_user($a,$c);
  if ($rz) return $rz;
}
// ������ �� ������ �� ���������� � ��� $_SESSION['user_username'] � ������ $_SESSION['user_password'].
$rz = db_select_1('ID',$user_table,
      "`username`='".addslashes($_SESSION['user_username'])."' AND `password`='".$_SESSION['user_password']."'");
// ��� ���� ����� ���������� - Access denied
if (!$rz) { session_destroy(); header("Status: 403"); die("Access denied."); }
else{
  // ��� �� ���������� ������� �� �����������.
  if ($a == 'edit') return edit_user($rz['ID']);
  // ����������� ���� �� �������
  $tm = date('Y-m-d H:m:s', $_SESSION['session_start']);
  mysqli_query($db_link, "UPDATE `$tn_prefix"."users` SET `date_time_2`='$tm' WHERE `ID`=".$rz['ID'].";");
  // ��� � �������� ��������� �� ��������� �� ��� ����������.
  if ( ($a=='create') || (isset($_GET['user']) && ($_GET['user']=='newreg')) ) create_user();
  // ����� �� ����������, �� ����� �� �� ����� ���� �������.
  $lp = stored_value('user_loginpage',''); 
  // ��� � �������� �� �������� ����������.
  if ($lp && ($a=='login')){
    header("Location: $lp");
    die;
  }
  // ��� �� � �������� �� ����� ���� "�����".
  else $rz = '<span class="user">'.$_SESSION['user_username'].
       ' <a href="'.set_self_query_var('user','logout').'">'.translate('user_logaut').'</a></span>';
}
return $rz;
}

// ��������� get_user() ����� HTML ��� � ����� �� �������/������������ �� ����������

function get_user($a,$c){
// ��� ������� �� ������� ���� � ���������, �� ���������� ����������� � ��� �����
if (isset($_POST['username'])){ process_user(); return ''; }
global $idir;
// �������� �� ���������� �� �������/��������� �� ����������
if (!$c && isset($_GET['user']) && ($_GET['user']=='newreg')) $page_title = translate('user_newreg');
else $page_title = translate('user_login');
// ��� ���� ��� ���� ���� ���������� - ������, ����� �������� ����
$m = '';
if (!$c) $m = translate('user_firstuser');
// ���������� �� ����������
$page_content = '<div id="user_login">'."\n<h1>$page_title</h1>\n$m\n".user_form($c)->html();
if (stored_value('user_showreglink', 'false')=='true')
   $page_content .= '<p><a href="'.set_self_query_var('user','newreg').'">'.translate('user_newreg')."</a></p>";
$page_content .= "\n</div>";
// ��� ���������� �� �� ������ � ������ �� ������� � build_page.php,
if ($a != 'login'){ include($idir.'lib/build_page.php'); die; }
// ����� �� ����� ������� ����������, �� �� �� ������ � �������.
else return $page_content;
}

// ��������� process_user() ��������� ������� �� ������� �� ����������� - 
// ��������� �� �� ����������� ���������� �� �������

function process_user(){
if (isset($_POST['password2'])) save_user();
$_SESSION['user_username'] = $_POST['username'];
if (isset($_POST['password'])) $_SESSION['user_password'] = pass_encrypt($_POST['password']); else $_SESSION['user_password'] = '';
$_SESSION['session_start'] = time();
// ���������� �� ��������� $_GET['user']=='enter' � ������������ �� ����������
if (isset($_GET['user'])&&($_GET['user']=='enter')){
  $l = unset_self_query_var('user',true); //echo $l; die;
  header('Location: '.$l);
}
}

// �������� �� �������� �� ���� �� ��� ������

function pass_encrypt($p){
if (stored_value('user_mysqlpass','')=='yes') return '*'.strtoupper(sha1(sha1($p,true)));
else return sha1($p);
}

// ��������� �� ������� �� ��� ����������

function save_user(){
global $tn_prefix, $db_link;
// ��� �� ��������� � ����� �� �����������
$user_table = stored_value('user_table','users');
if ( !isset($_GET['user']) || ($_GET['user']!='newreg') || ($_POST['password2']!=$_POST['password']) || !$_POST['username'] )
   return;
$u = db_table_field('username', $user_table, "`username`='".addslashes($_POST['username'])."'");
if ($u) return;
$q = "INSERT INTO `$tn_prefix".
     "users` SET `date_time_0`=NOW(), `date_time_1`=NOW(), `username`= '".addslashes($_POST['username']).
     "', `password`='".pass_encrypt($_POST['password'])."';";
return mysqli_query($db_link,$q);
}

// ����� ����� ����� �� �������/������������ �� ����������

function user_form($c){
$guf = new HTMLForm('login_form');
$guf->add_input( new FORMInput(translate('user_username'),'username','text') );
$guf->add_input( new FORMInput(translate('user_password'),'password','password') );
if (!$c && isset($_GET['user']) && ($_GET['user']=='newreg'))
  $guf->add_input( new FORMInput(translate('user_passwordconfirm'),'password2','password') );
$guf->add_input( new FORMInput('','','submit',translate('user_login_button')) );
return $guf;
}


// ��������� ������� � ���������� ��� ���������� ���� ��������

function logout_user(){
// ����� �� ����������, ����� �� ������� ���� ��������
$lp = current_pth(__FILE__).'logout.php';
// ���������� � ����������� ���� �� � �������� ����� 
$lp = stored_value('user_logoutpage',$lp); //print_r($lp); die;
// ������������ �� �������
session_destroy();
// ������������ ��� ���������� ���� ��������
header("Location: $lp");
}

// ����� ����� �� ����������� ������� �� �����������

function edit_user($id){
// ��� �� ��������� � ����� �� �����������
$user_table = stored_value('user_table','users');
$cp = array(
'ID'=>$id,
'username'=>translate('user_username'),
'password'=>translate('user_password'),
'email'=>translate('user_email'),
'firstname'=>translate('user_firstname'),
'secondname'=>translate('user_secondname'),
'thirdname'=>translate('user_thirdname'),
'telephone'=>translate('user_telephone')
);
$rz = '';
if (count($_POST)) $rz .= process_record($cp, $user_table);
return $rz.edit_record_form($cp, $user_table);
}

//
// ������� new_user() �� �������, ������ ��� ��� ���� ���� ���� ����������.
// �� ���������� ����������, ���� ������ � ������ � �������� user=newreg � ���� ����������� ��������� �� ����� �� 
// ����������� �� ��� ����������.
//
function new_user($a){// print_r($_SESSION); die;
  // ��� ������ �� �� ������� ���� ���� "����", �� ����� ���� ����
  if (($a=='enter')&&!(isset($_GET['user'])&&($_GET['user']=='enter'))) return enter_link();
  // � �������� ������ �� ������� ��������� user=newreg � ���������� �� ����������
  $l = set_self_query_var('user','newreg',false);
  header("Location: $l");
}

//
// ������� create_user() �� ���������, ������ � ������ �� ���������� ��� ��������� user=newreg
// �� �� ������ ����� �� ��������� �� ��� ����������.
//
function create_user(){
if (count($_POST)) save_user();
global $idir;
// ��� �� ��������� � ����� �� �����������
$user_table = stored_value('user_table','users');
// ����� �� �����������
$i = db_table_field('ID',$user_table,"`username`='".$_SESSION['user_username']."' AND `password`='".$_SESSION['user_password']."'");
// �������� ���� ������������ ��� ������ �����
$p = db_table_field('yes_no','permissions',"`type`='all' AND `user_id`=$i");
// ��� ���� ������ �����, �������� ���� ���� ����� ��� ����� user
if (!$p) $p = db_table_field('yes_no','permissions',"`type`='module' AND `object`='user' AND `user_id`=$i");
// ��� ���� � ���� ����� - ������
if (!$p) die(translate('user_cnnotcreate'));
$page_title = translate('user_newreg');
$page_content = '<div id="user_login">'."\n<h1>$page_title</h1>\n".user_form(0)->html();
include($idir.'lib/build_page.php');
die;
}

// �������, ����� ����� html ��� �� ����������� "����"
//
function enter_link(){
// ����� �� ���������� �� �������
$ep = stored_value('user_loginpage');
if (!$ep) $ep = set_self_query_var('user','enter');
return '<a href="'.$ep.'">'.translate("user_enter").'</a>';
}

?>
