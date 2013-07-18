<?php
/* 
MyCMS - a simple Content Management System
Copyright (C) 2013 Vanyo Georgiev <info@vanyog.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// ��������� �� ���� �������� � ������/���� $_GET['m']

$idir = dirname(dirname(dirname(__FILE__))).'/';
$ddir = $idir;

include_once($idir."lib/translation.php");
include_once($idir."lib/f_db_insert_1.php");
include_once($idir."lib/f_db_insert_m.php");
include_once($idir."lib/f_parse_content.php");
include_once($idir."lib/o_form.php");

// ����������� ������� �� �����������
$tx = parse_content('<!--$$_USERMENU_$$-->');

// ��� ������������ ���� ����� �� ������� ���� �������� - ����.
if (!$can_create) echo die("Your have no permission to create new page here.");

// ��������� �� ��������� �����
if (count($_POST)) process_data();

// ����� �� ������ �� ����������
$mi = 1*$_GET['m'];

// �������  �� ������ �������� � ������ - �� ������������ ���-������.
$pz = db_table_field('place', 'menu_item', "`ID`=$mi ORDER BY `place` DESC")+10;

// ��������� �� ����� �� ��������� �� ����� �� ���� �������� 
$pf = new HTMLForm('new_page_fotm');

$ti = new FormSelect(translate('usermenu_language'), 'lang', $languages);
$ti->values = 'k';
$pf->add_input( $ti );

$pf->add_input(new FormInput(translate('usermenu_menupos'), 'place', 'text', $pz) );

$ti = new FormInput(translate('usermenu_linktext'), 'linktext', 'text');
$ti->size = '50';
$pf->add_input( $ti );

$ti = new FormInput(translate('usermenu_newpagetitle'), 'title', 'text');
$ti->size = '100';
$pf->add_input( $ti );

$pf->add_input(new FormTextArea(translate('usermenu_newpagecontent'), 'content', 100, 30) );

$pf->add_input( new FormInput('','','submit',translate('usermenu_newpagesubmit')) );

$page_content = '<h1>'.translate('usermenu_createnewpage').'</h1>'.$pf->html();
$page_header = '<style><!--
th { text-align: right; vertical-align:top; }
--></style>';

include($idir."lib/build_page.php");

//
// ��������� �� ��������� �����
//
function process_data(){ // print_r($_POST); die;
global $pth;
// ������������ ����� �� ������ ��������
$pi = db_table_field('MAX(`ID`)', 'pages', '1')+1;
// ����� �� ������� 'pages'
$d1 = array(
  'menu_group'=>1*$_GET['m'],
  'title'=>"p$pi"."_title",
  'content'=>"p$pi"."_content",
  'template_id'=>1*$_GET['t'],
);
// ��������� � ���������
$pi = db_insert_1($d1,'pages');
// ����� �� ������ � ������� 'menu_items'
$d2 = array (
  'place'=>1*$_POST['place'], 
  'group'=>1*$_GET['m'], 
  'name'=>"p$pi"."_link",
  'link'=>$pi
);
// ��������� � ���������
$pp = db_insert_1($d2,'menu_items');
// ����� �� �������� � ������� 'content'
$d3 = array (
// ������� ����� ����� � ������
array('name'=>$d2['name'],
      'date_time_1'=>'NOW()',
      'date_time_2'=>'NOW()',
      'language'=>addslashes($_POST['lang']),
      'text'=>addslashes($_POST['linktext'])
      ),
// �������� �� ����������
array('name'=>$d1['title'],
      'date_time_1'=>'NOW()',
      'date_time_2'=>'NOW()',
      'language'=>addslashes($_POST['lang']),
      'text'=>addslashes($_POST['title'])
      ),
// ���������� �� ����������
array('name'=>$d1['content'],
      'date_time_1'=>'NOW()',
      'date_time_2'=>'NOW()',
      'language'=>addslashes($_POST['lang']),
      'text'=>addslashes($_POST['content'])
      )
);
// ��������� � ���������
db_insert_m($d3,'content');
$l = 'Location: '.$pth.'index.php?pid='.$pi;
header($l); 
}

?>
