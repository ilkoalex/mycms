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

// ��������� translate($n) ����� ����� � ��� $n �� �����, ��������� � ���������� ���������� $language.

// ��� � ������� `content` �� ������ ����� ���� ����� �����,
// �� ������� ������ ��� � ����� �� ����������� 
// �� ������� �� ������ �� ������� ����������� ��� �������� �� �������� �� �����,
// �� �� ������ ������ � ������� �����, ��������� ����� ����� �� ������������� �� ����.

include_once($idir."lib/f_is_local.php");
include_once($idir."conf_paths.php");
include_once($idir."lib/f_db_select_1.php");
//include_once($idir."lib/f_query_or_cookie.php");
include_once($idir."lib/f_parse_content.php");

$content_date_time    = '';// ����������, ����� ������� ������ � ���� �� ���������� �������� �� �������� ����� 
$content_create_time = ''; // ����������, ����� ������� ������ � ���� �� ������� ��������� �� �������� ����� 

function translate($n){
global $language, $adm_pth, $default_language, $content_date_time, $content_create_time;

$content_date_time = '';
$content_create_time = '';

$el = ''; // ���� �� �����������. ������� �� ��� ������ � � ����� �� �����������.
if (in_edit_mode()){
  $id = db_select_1('ID','content',"name='$n' AND language='$language'");
  $el = '<a href="'.$adm_pth.'edit_record.php?t=content&r='.$id['ID'].'">*</a>';
}

$r = db_select_1('*','content',"name='$n' AND language='$language'");
if ($r){ 
  $content_create_time = $r['date_time_1']; 
  $content_date_time = $r['date_time_2'];
  $t = $r['text']; /*if (get_magic_quotes_runtime())*/ $t = stripslashes($t);
  return parse_content($t).$el;
}
else if (is_local() || in_edit_mode()) // �� ������� ������ ��� � ����� �� ����������� �� ������� ����� �� ������� ���� ����
         return "<a href=\"$adm_pth"."new_content.php?n=$n&l=$language\">$n</a>";
       else { 
         $r = db_select_1('*','content',"name='$n' AND language='$default_language'");// print_r($r); echo "<br>";
         if ( !$r ) $r['text'] = $n; else {
           $content_create_time = $r['date_time_1'];
           $content_date_time = $r['date_time_2'];
         }
         $t = $r['text']; /*if (get_magic_quotes_runtime())*/ $t = stripslashes($t);
         return parse_content($t);
       }

}

?>
