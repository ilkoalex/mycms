<script language="php">

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

// ��������� db_table_field($fn,$tb,$whr), ����� ������������ �� �������� $fn
// �� ������, ��������� �� ��������� $whr ����� �� ������� $tb.

// ��������� � �� ������ �����, ���������� �� ������������ $db_link,
// ���������� � usedatabase.php.

// ��� ���� ����� ��������� �� ��������� �������� ������,
// ������ ��� �� � �������, �� � ��������� ��� �����, ��������� �� ��������� $whr 
// �� ������������ � �� �� �������� ��������� db_select_1(),
// ����� � ����� ������ ����� false ��� �� �� �������� ������

include_once("usedatabase.php");

function db_table_field($fn,$tb,$whr){
global $db_link,$tn_prefix;
$q="SELECT $fn FROM $tn_prefix$tb WHERE $whr;"; //echo "$q<br>";
$r=mysql_query($q,$db_link);
if (!$r){ echo $q.'<br>'; return false; }
$rc=mysql_fetch_assoc($r);
if ($fn[0]=='`') $fn = substr($fn,1,strlen($fn)-2);
return stripslashes($rc[$fn]);
}

</script>
