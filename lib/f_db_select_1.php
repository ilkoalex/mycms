<script language="php">

// ��������� db_select_1, ���������� � ���� ����
// ���� �������� $fn �� ���� ����� �� ������� $tb,
// �� ������ �����, ���������� �� ������������ $db_link
// (��� usedatabase.php).
// ����������� ����� ������������� ��������� $whr.
// ��������� �����, ��������� �����, ��� false ��� �������.
// ��������� �� ������ �� ������� �� ��������,
// � ����������� - ������������ �� �������� �� ���������.

include_once("usedatabase.php");

function db_select_1($fn,$tb,$whr){
global $db_link, $tn_prefix;
$q="SELECT $fn FROM `$tn_prefix$tb` WHERE $whr LIMIT 1;";
$r=mysql_query($q,$db_link);
if (!$r) return false;
$rc=mysql_fetch_assoc($r);
mysql_free_result($r);
return $rc;
}

</script>
