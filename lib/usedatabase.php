<script language="php">

// ���� ���� ������������ ������������ $db_link
// ����� �� �������� � mysql_query($q,$db_link);

include($idir."conf_database.php");

$db_link = get_db_link($user, $password, $database);

function get_db_link($user, $password, $database){
$l = mysql_connect("localhost",$user,$password);
if (!$l){
 echo '<p>�� �� �������� ������ � MySQL �������!'; die;
}
if (!mysql_select_db($database,$l)){
 echo '<P>�� ���� �� ���� ������� ���� �����.'; die;
}
mysql_query("SET NAMES 'cp1251';",$l);
//mysql_query("SET CHARACTER SET 'cp1251';",$l);
return $l;
}

</script>
