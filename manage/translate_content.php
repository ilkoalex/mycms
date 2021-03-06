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

error_reporting(E_ALL); ini_set('display_errors',1);

include_once("conf_manage.php");
include_once($idir."lib/translation.php");
include_once($idir."lib/f_db_select_m.php");
include_once($idir."lib/o_form.php");
include_once($idir."lib/f_db_insert_1.php");

// ���� ������ �� �������� �� ����������� ������� �� ���������� �� ������������.
// ������ ������� ���������� ������ � ������� ����� �� ������������ ��.

// � ��� �������� � ���������� �� ���������� ��������� ���� �� �� �������
// ��������� $_GET['p'], ����� �������� � ����, ����� ������ �� �� ����� � ������� �� 
// ����������� �� ����������.


// ���������� �� ����������� �����, ��� ��� ������
if (count($_POST)) process_trans();

// ������� � WHERE ������ �� sql �������� �� �������� �� ���������
$w = '';

// ��� ��� ��������� 
if (isset($_GET['p'])) $w = " AND `name` LIKE '%".$_GET['p']."%'"; 

// ����� �� ������� �� ������ ��������� �� ����� �� ������������.
$na = db_select_m('name','content',"`language`='$default_language'$w"); //print_r($na); die;

// �������� �� ������ �������� ����� ��� ����� �� ������������
$la = $languages;
unset($la[$default_language]);
$la = array_keys($la);

$page_content = '';

if (!count($la)) $page_content = '<p>���� ���� � ���� �� '.$languages[$default_language].'</p>';
else {

// �� ����� ��� �� ������
foreach($na as $n){
  $n1 = $n['name']; //echo "$n1<br>";
  // ��� � ������� ������� ������, �� ������� ����� �� ���������� � ������� �� ����������
  $page_content .= untraslated_string($n1);
  if ($page_content) break;
}

} // ���� �� if (���� ���� ����) ...

// ��������� �� ����������
include_once("build_page.php");

//----------- ������� --------------

//
// �������, ����� ������� ����� �� ����������
//
function new_translation($n1,$l){
global $languages, $default_language;
  $d = db_select_1('*','content',"`name`='$n1' AND `language`='$default_language'");
  $f = new HTMLForm('new_tralslation');
  $f->add_input(new FormInput('','name','hidden',$n1));
  $f->add_input(new FormSelect('Not editable','nolink',array('0','1'),$d['nolink']));
  $f->add_input(new FormInput('','language','hidden',$l));
  $f->add_input(new FormTextArea('Text in '.$languages[$l],'text',100,15,
                                  str_replace('&','&amp;',stripslashes($d['text']))) );
  $f->add_input(new FormInput('','','submit','Save'));
  return "<p>String name: '".$d['name']."'<br>\nIn ".$languages[$default_language].
         ":</p>\n".'<textarea id="deflang" cols="100" rows="10"  disabled="disabled">'.
         str_replace('&','&amp;',stripslashes($d['text'])).
         "</textarea>\n".$f->html();
}

//
// �� ����� ����, �������� �� ������������� ��, �� ��������� ���� ��� ������ ��
// ������� � ��� $n1. ��� ���� �� ����� ����� �� ����������� ��, � ��� ��� �������
// �� ������ ����� �� ����� ������ ������.
//
function untraslated_string($n1){
global $la;
  // �� ����� ����, �������� �� ������������� ��
  foreach($la as $l){
    $r = db_select_1('*','content',"`name`='$n1' AND `language`='$l'");
    if (!$r) return new_translation($n1,$l);
  }
  return '';
}

//
// ��������� ��������� ����������� �����
//
function process_trans(){
  $_POST['date_time_1']='NOW()';
  $_POST['date_time_2']='NOW()';
  db_insert_1($_POST,'content');
}

?>
