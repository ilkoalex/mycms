<?php
// Copyright: Vanyo Georgiev info@vanyog.com

// � ����� �� ��������� ��� ���� place, ����� ����� �� �������� ���� �� ��������� �� ��������.
// ���� ���� ������� ����������� ��������� ���� 10.
// ��� ������������ �� ������� �� ���� �� ��������� �� ������� ������ place �� ������, 
// ����� ������ �� �� ��������. ��������, �� �� �� 
// �������� ����� ������ � place=20 � place=30, �� ������ place=25.

// ��������� ������ ������� ������ ����������� �� ���� place ���� 10
// � ������ �� �� �������� ���� ���� �� ���������� ������������.

include("../conf_paths.php");
include("../f_db_select_m.php");

$t = $_GET['t']; // ��� �� ���������

// �������� �� ��������� ���� 1
$i = 1;
$r = db_select_m('ID', $t, '1 ORDER BY `place` ASC');
foreach($r as $r1){
  $q = "UPDATE `$tn_prefix$t` SET `place`=$i WHERE ID=".$r1['ID'].";";
  mysql_query($q,$db_link);
  $i++;
}

// ���������� �� ����������� �� 10
$q = "UPDATE `$tn_prefix$t` SET `place` = `place` * 10;";
$q = mysql_query($q,$db_link);

// ������� �� ����������, �������� �������
header('Location: '.$_SERVER['HTTP_REFERER']);
?>
