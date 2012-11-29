<?php

// ���� ���� � ������������ �� �� ������� �������� � include("build_page.php");
// �� ����� php �������, �� �� ������ ����������� � ��� ��������.
// �������� �� ���� �� ���������� �� ���������� �� �������������� �� ���������� $adm_pth (��� conf_paths.php).
// ����� ����������� �� ������������ �� ���������� ������ �� � ��������� �� ������������ $page_content.
//
// ��� � ���������� �� ���������� ��������� � ��:
//
// $page_title - ���������� �� ����������
// $page_header - ������������ ������, ����� �� ������� ����� <head></head>
// $body_adds - ������������ �������� �� <body> ����

// ����� �� ��������������� �� ����������
//include("count-visits.php");

$idir = dirname(dirname(__FILE__)).'/';

include_once($idir.'conf_paths.php');

if (!isset($page_title)) $page_title = '';
if (!isset($page_header)) $page_header = '';
if (!isset($body_adds)) $body_adds = '';

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>'.$page_title.'</title>
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
   <link href="'.$pth.'style.css" rel="stylesheet" type="text/css">
   '.$page_header.'
</head>

<body'.$body_adds.'>
'.$page_content.
//visit_count().  // ����� �� ��������������� �� ����������
'
</body>
</html>
';

?>
