<?php
// Copyright: Vanyo Georgiev info@vanyog.com

// �������� �� ���������� � ������� content

$idir = dirname(dirname(__FILE__)).'/';

include($idir."lib/usedatabase.php");

$n = $_GET['n']; // ��� �� ������
$l = $_GET['l']; // ���� �� ������

$q = "INSERT INTO $tn_prefix"."content SET name='$n', language='$l', date_time_1=NOW(), date_time_2=NOW();";

mysql_query($q,$db_link);

$i = mysql_insert_id($db_link);

header('Location: '.$adm_pth.'edit_record.php?t=content&r='.$i);

?>
