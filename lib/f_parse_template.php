<?php
// Copyright: Vanyo Georgiev info@vanyog.com

// ��������� parse_template($p) �������� ������� �� �������� $p.
// ($p � ����������� ����� ��� ������ �� ���������� �� ������� $tn_prefix.'pages'.)
// ��� �������� ��� ���� ��� ������ �������� �� ������ � ��� � ���� ���� �������
// ��������� parse_content()

include_once('translation.php');

function parse_template($p){
global $content_date_time;

// ������ �� ������� �� ���������� �� ������� `templates`
$t = db_select_1('*','templates',"ID=".$p['template_id']);
if (!$t) return 'No page template found. May be the system is not installed.';
$cnt = stripslashes($t['template']);

// ��� �������� � ������ ��� �� ����������
if (!$cnt) $cnt = '<h1><!--$$_PAGETITLE_$$--></h1>
<!--$$_CONTENT_$$-->';

// ��� �������� ��� �������, ��������� �� ���e � � ���� �� ������ �������
// ������� �� �� ������ ��� �������
while ($t['parent']){
$t0 = db_select_1('*','templates',"ID=".$t['parent']);
$cnt = str_replace('<!--$$_TEMPLATE_$$-->', $cnt, stripslashes($t0['template']) );
$t = $t0;
}

return parse_content($cnt);
}

function show_visits($p){
if (show_adm_links()) return '   Visited: '.$p['tcount'].', Today: '.$p['dcount'];
else return '';
}

?>
