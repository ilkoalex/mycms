<script language="php">
// Copyright: Vanyo Georgiev info@vanyog.com

// ��������� db_select_m, ���������� � ���� ����
// ���� �������� $fn �� ������ ������ �� ������� $tb,
// ��������������� ��������� $whr.
// ��������� � �� ������ �����, ���������� �� ������������ $db_link
// (��� usedatabase.php).
// ��������� ����� ����� �� ���������� ������,
// ������������� �� ����� �� ����������� ������.
// ��������� �� ������ �� ����� ����� �� ������� �� ��������,
// � ����������� - ������������ �� �������� �� ���������.

include_once("usedatabase.php");

function db_select_m($fn,$tb,$whr){
global $db_link, $tn_prefix;
$q="SELECT $fn FROM `$tn_prefix$tb` WHERE $whr;"; //echo "$q<br>";
$dbr=mysql_query($q,$db_link);
$r=array();
if (!$dbr) return $r; 
while ( $rc=mysql_fetch_assoc($dbr) ){
 $r[]=$rc; //print_r($rc);
}
mysql_free_result($dbr);
return $r;
}

</script>
