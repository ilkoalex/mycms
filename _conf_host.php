<?php

// ���������� � �������, ����� ����� ���������� �� �������� � ����������������

$web_host = 'yoursite.org'; // ������ �� �����.

$local_host = 'test'; // ������� ������ �� �����, ����� �� � �������� ���� ��������.
                      // �������� ��, ������ �� ������� ������ ������� ����� �� �����.

$phpmyadmin = 'http://yoursite.org/phpmyadmin'; // ����� �� phpMyAdmin �� ����������� ������

// ����� ������, ��� ������ �� ������ �� ������� ������.
function is_local(){
global $local_host;
return $local_host==$_SERVER['HTTP_HOST'];
}

include($idir."lib/f_query_or_cookie.php");

// ��������� in_edit_mode() ����� ������ ��� ������ � � ����� �� �����������
// � ����� ����� �� �������� ������� �� ����������� �� �������, ��������, ������ � ��.
// ������ � � ����� �� ����������� ���:
// - ������ ��������� im=admin
// - ������ $_GET['im']=='admin'

function in_edit_mode(){
global $edit_name, $edit_value;
return query_or_cookie($edit_name,$edit_value);
}

?>
