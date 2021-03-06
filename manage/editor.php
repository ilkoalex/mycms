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

include("ta_ctag.php");
include_once($idir.'lib/f_mod_list.php');
include_once($idir.'lib/f_mod_picker.php');

function editor($n,$tx){
global $ta_ctag, $ta_fctag, $page_header;
$tx = str_replace('&','&amp;',$tx);
$tx = str_replace(chr(60).'!--$$_',chr(60).' !--$$_',$tx);
// ���� �� textarea ����������
static $tec = 0;
// ��� ��� ���� textarea �������� �� ������� javascript-��
// � mod_picker
if (!$tec){
$js = '
<script type="text/javascript"><!--
var tefc;
function onTeFocus(){
tefc = document.activeElement;
}
function doInsertTag(){
var t = prompt("Enter a html tag to be inserted");
insert_tag(t,t);
}
function insert_tag(t1,t2){
var te = tefc;
te.focus();
var s = te.selectionStart;
var e = te.selectionEnd;
var v = te.value;
if (t2.length) v = v.substring(0,e)+"</"+t2+">"+v.substring(e,v.length); 
te.value = v.substring(0,s)+"<"+t1+">"+v.substring(s,v.length);
s += t1.length + 2;
e += t1.length + 2;
te.selectionStart = s;
te.selectionEnd = e;  
}
function insert_text(t1){
var te = tefc;
te.focus();
var s = te.selectionStart;
var e = te.selectionEnd;
var v = te.value;
te.value = v.substring(0,s)+t1+v.substring(e,v.length);
e = s + t1.length;
te.selectionStart = s;
te.selectionEnd = e;  
}
function insert_2_texts(t1,t2){
var te = tefc;
te.focus();
var s = te.selectionStart;
var e = te.selectionEnd;
var v = te.value;
if (t2.length) v = v.substring(0,e)+t2+v.substring(e,v.length); 
te.value = v.substring(0,s)+t1+v.substring(s,v.length);
s += t1.length;
e += t1.length;
te.selectionStart = s;
te.selectionEnd = e;  
}
var tag_a1 = "a href=\"index.php?pid=\"";
var tag_a2 ="a";
var tag_s1 = "<script type=\"text/javascript\"><!--\n";
var tag_s2 = "\n--><"+"/script>";
--></script>
'.mod_picker();
} else $js = '';
$tec += 1;
// ������� �� ���������
return $js.

'<input type="button" value="tag" onclick="doInsertTag();">'.'
'.make_tag_button('a','tag_a1','tag_a2').'
'.make_insert_button('php','<?php\n// Copyright: Vanyo Georgiev info@vanyog.com\n\n?>\n').'
'.make_insert_2_button('case','\'case \\\'\'','\'\\\': break;\'').'
'.make_insert_2_button('include','\'include(\\\'\'','\'\\\');\'').'
'.make_insert_2_button('include_once','\'include_once(\\\'\'','\'\\\');\'').'
'.make_insert_2_button('print_r','\'print_r($\'','\'); die;\'').'
'.make_insert_2_button('<!--$$_','\'<!--$$_\'','\'_$$-->\'').'
'.make_insert_2_button('javascript','tag_s1','tag_s2').ckeb($tec).'
<textarea id="editor'.$tec.'" cols="120" name="'.$n.'" rows="22" style="font-size:120%;" onfocus="onTeFocus();">'.
str_replace($ta_ctag,$ta_fctag,$tx).$ta_ctag;

}

function make_tag_button($n,$t1,$t2){
return '<input type="button" value="'.$n.'" onclick="insert_tag('.$t1.','.$t2.');">';
}

function make_insert_button($n,$t1){
return '<input type="button" value="'.$n.'" onclick="insert_text(\''.$t1.'\');">';
}

function make_insert_2_button($n,$t1,$t2){
return '<input type="button" value="'.$n.'" onclick="insert_2_texts('.$t1.','.$t2.');">';
}

// HTML ��� �� ��������� �� ����� �� ��������� �� CKEditor
function ckeb($n){
global $page_header, $ckpth;
// ��� �� �������� ���� �� CKEditor
$ckep = $_SERVER['DOCUMENT_ROOT'].$ckpth.'ckeditor.js';
// �������� ���� CKEditor ����������
if (file_exists($ckep)){
  $page_header .= '<script type="text/javascript" src="'.$ckpth.'ckeditor.js"></script>';
  return '
<input type="button" onclick="CKEDITOR.replace( \'editor'.$n.'\' );" value="CKEditor">';
}
return '';
}
