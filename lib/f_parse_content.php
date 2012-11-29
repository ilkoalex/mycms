<?php
// Copyright: Vanyo Georgiev info@vanyog.com

// ��������� parse_content($cnt) �������� ���������� <!--$$_XXX_$$--> � ������� $cnt
// ��� ����������, ���������� �� php ���������, ����� �� ���������� � ������� $tn_prefix.'scripts'

function parse_content($cnt){
global $page_options, $page_data, $content_date_time, $body_adds, $page_header, $idir, $adm_pth, $apth;

$l = strlen($cnt);
$str1 = '<!--$$_';
$str2 = '_$$-->';

// ����� �� ���������� �� ����������
while ( !(($p0 = strrpos($cnt,$str1))===false) ){

$p1 = $p0 + strlen($str1);
$p2 = strrpos($cnt,$str2); 
$p3 = $p2 + strlen($str2);

$tg = explode('_',substr($cnt,$p1,$p2-$p1),2);

$tx = ''; // Html ���, ����� �� ������� ��������

// ������ �� ������� � ��� $tg[0] �� ������� $tn_prefix.'scripts'
$sc = db_select_1('*','scripts',"`name`='".$tg[0]."'");

if (!$sc){
  $f = strtolower($tg[0]);
  $fn = "$f/f_$f.php";
  if (file_exists("$apth$fn")){
    $c = "include('$fn');\n";
    if (isset($tg[1])) $c .= '$tx = '."$f('$tg[1]');";
    else $c .= '$tx = '."$f();";
    eval($c);
  }
  else {
    if (show_adm_links()) $tx = '<p>Can\'t parse content <a href="'.$adm_pth.'new_record.php?t=scripts&name='.$tg[0].'">'.$tg[0].'</a></p>';
    else $tx = '<p>Can\'t parse content '.$tg[0].'</p>';
  }
}
else eval(stripslashes($sc['script']));

$cnt = substr_replace($cnt,$tx,$p0,$p3-$p0);

} // ���� �� ������ �� ��������� �� ����������

return $cnt;

} // ���� �� ��������� parce_content()

?>
