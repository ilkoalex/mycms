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

// ��������� edit_record_form($cp, $tn) ����� html ��� ��
// ����� �� ����������� �� ����� �� ������ �����.
// ����������� $cp e ����������� ����� � ������� - ������� �� ��������,
// � ��������� - ����������� �������, ����� �� �������� ���� ���� ������ ��� �������.
// � ���� ����� ������ �� ��� � ������� 'ID'=>�������������, ����� �� ���������.

include_once($idir."lib/f_db_field_names.php");
include_once($idir."lib/f_db_field_types.php");
include_once($idir."lib/f_db_table_field.php");
include_once($idir."lib/f_db_show_columns.php");
include_once($idir."lib/f_db_enum_values.php");
include_once($idir."lib/o_form.php");

function edit_record_form($cp, $tn){
// ��������� �������� �� �������� �� ���������
$ft = db_show_columns($tn, '', 'Type');
// ��������� ������� �� �������� �� ��������� 
$fn = db_field_names($tn);
// ��������� �� ��� ����������� ����� � ������� ������� �� �������� � ��������� - �������� ��
$ft = array_combine($fn, $ft);
// ��������� �� ������, ����� �� �� ���������
$d = db_select_1('*', $tn, "`ID`=".$cp['ID']); //print_r($d); die;
// ������ ��������
$rz = '';
// ���������� ������� �� ���������� ������
$max_size = 80;
// ���������� ���� ������ �� ���������� �������
$max_lines = 25;
// ��������� �� �������
$hf = new HTMLForm('editrecord_form');
// �������� �� �������� �� ����� �� ��������, ����� �� �� ����������
foreach($cp as $n => $v){
  switch ($n) {
  case 'ID': // ������� - ������ ����
    $fi = new FORMInput('', 'ID', 'hidden', $v);
    $hf->add_input($fi);
    break;
  default:
    // ����������� ���� �� ��������
    preg_match('/([a-z]*)\((.*)\)/', $ft[$n], $tp);
    if (count($tp)<2) $tp[1] = $ft[$n];
    switch ($tp[1]){
    case 'varchar': switch($tp[2]){
      case '255': case '100': case '50': case '20':
        $t = 'text';
        if ($n=='password'){
          $vl = '';
          $t = $n;
          $fi =  new FORMInput($v, $n, $t, $vl);
          if ($tp[2]<$max_size) $fi->size = $tp[2];
          else $fi->size = $max_size;
          $hf->add_input($fi);
          $n = 'password2';
          $v = translate('user_passwordconfirm');
        } 
        else { $vl = htmlspecialchars(stripslashes($d[$n]), ENT_COMPAT, 'cp1251'); }
        $fi =  new FORMInput($v, $n, $t, $vl);
        $fi->size = 80;
        $hf->add_input($fi);
        break;
      default: die("Unknown subtype of '$ft[$n]'");
      }
      break;
    case 'text':
      $vl = stripslashes($d[$n]);
      $la = explode("\n", $vl);
      $lc = count($la);
      if ($lc<3) $lc = 3;
      if ($lc>$max_lines) $lc = $max_lines;
      $hf->add_input( new FormTextArea($cp[$n], $n, $max_size, $lc, $vl) );
      break;
    case 'int':
      $vl = $d[$n];
      $fi =  new FORMInput($v, $n, 'text', $vl);
      $hf->add_input($fi);
      break;
    case 'tinyint': switch($tp[2]){
      case 1:
        $fi =  new FORMInput($v, $n, 'checkbox', 1);
        if ($d[$n]) $fi->checked = ' checked';
        $hf->add_input($fi);
        break;
      default: die("Unknown subtype of '$ft[$n]'");
      }
      break;
    case 'enum':
      $op = str_getcsv($tp[2], ',', "'");
      $i = array_search($d[$n], $op);
      $fi =  new FormSelect($v, $n, $op, $i);
      if ($d[$n]) $fi->checked = ' checked';
      $hf->add_input($fi);
      break;
    default: die("Unknown type '$ft[$n]' of field `$n`");
    }
  }
}
$hf->add_input( new FORMInput('','','submit',translate('saveData')) );
$rz .= $hf->html();
return $rz;
}

// ��������� �� ����������� ��� ������� �����

function process_record($cp, $tn){
global $tn_prefix, $db_link;
// ��������� �������� �� �������� �� ���������
$ft = db_field_types($tn);
// ��������� ������� �� �������� �� ��������� 
$fn = db_field_names($tn);
// ��������� �� ��� ����������� ����� � ������� ������� �� �������� � ��������� - �������� ��
$ft = array_combine($fn, $ft); //print_r($ft); die;
$k = array_keys($cp); // ����� �� ������� �� ��������, �� ����� �� ��������� �����.
$rz = ''; // ������ �������� - ������, ������� ��������� �� ����������� �� �������.
$q = ''; // SQL ������, ����� �� ��������.
$w = ''; // WHERE ������ �� SQL ��������.
$pu = false; // ���� �� �� ������� ������� �� ����������� � �������� �����.
// �������� ��, ������ �� ��������� ������� � ����� �� ������� ����������.
foreach($k as $n) switch($n){
case 'ID':
  // ��� �� � �������� ����� �� �����, �� �� ����� ����.
  if (!isset($_POST['ID'])) return;
  // �� �������� ���� ��� ����� � ���������� �����:
  $id = db_table_field('ID', $tn, "`ID`=".(1*$_POST['ID']));
  // ��� ��� ����� � ���������� ����� �� �������� ������ UPDATE,
  // � � �������� ������ - ������ INSERT.
  if ($id) $w = " WHERE `ID`=".(1*$_POST['ID']).";";
  break;
case 'password':
  // ��� � ��������� ���� ������ � ����� ����������
  if ( isset($_POST['password2']) && $_POST['password2'])
    if ( ($_POST['password2']==$_POST['password']) ){
      if ($q) $q .= ', ';
      $q .= "`$n`='".sha1($_POST[$n])."'";
      $pu = true;
      $rz .= '<span class="message">'.translate('user_passwordchanged')."</span><br>\n";
    }
    else $rz .= '<span class="warning">'.translate('user_passwordinvalid')."</span><br>\n";
  break;
default:
  if ($q) $q .= ', ';
  if ($ft[$n]=='int') 
    if (isset($_POST[$n])) $q .= "`$n`='".(1*$_POST[$n])."'";
    else $q .= "`$n`=0";
  else $q .= "`$n`='".addslashes($_POST[$n])."'";
}
// ���������� ������� �� ����������� � �������� �����.
if ($pu) process_user();
// ���������� ������� � ������ �����.
if ($w) $q = "UPDATE `$tn_prefix"."$tn` SET `date_time_2`=NOW(), $q$w";
else $q = "INSERT INTO `$tn_prefix"."$tn` SET `date_time_1`=NOW(), `date_time_1`=NOW(), $q;";
//print_r($q); die;
if (mysql_query($q,$db_link)) $rz .= '<span class="message">'.translate('dataSaved')."</span>";
if ($rz) $rz = '<p class="message">'.$rz.'</p>';
return $rz;
}

?>