<script language="php">

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
