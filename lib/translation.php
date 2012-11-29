<?php

// ����� ������� �� ���������� ������������ �� �������� �����

include($idir."conf_languages.php");
include_once($idir."lib/f_translate.php");

$language = getLanguage();

// ����� �������� �� ���������� ����, ���� ����� �������� ��������

function getLanguage(){
global $languages,$default_language;
$l1 = '';                                           // ������ ��������
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $l1 = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
$l2 = '';                                           // ������ ����������� language
if (isset($_COOKIE['language'])) $l2 = $_COOKIE['language'];
$l3 = '';
if (isset($_GET['lang'])) $l3 = $_GET['lang'];      // ������ ?lang=xx
$l = $default_language;                             // �� ������������
if ($l1) $l = $l1;
if ($l2) $l = $l2;
$ks = array_keys($languages); //print_r($ks);
if ($l3){ 
  $l = $l3;
  if (in_array($l,$ks)) setcookie('language',$l3,time()+30*3600*24,'/');
}  // echo "| $l1 | $l2 | $l3 | $l |";// print_r(array_keys($languages));
if (in_array($l,$ks)) return $l;
else return $default_language;
}

// ����� html ��� �� ��������� �� ��������, �� ����� �����

function flags(){
global $language, $languages, $dont_translate;
$ls = array_keys($languages);
$r = "";
if (!$dont_translate) foreach($ls as $l) if ($l!=$language){
  $u = $_SERVER['REQUEST_URI'];
  $h = '';
  if (strpos($u,'?')){
     $p = strpos($u,'lang=');
     if ($p) $h = substr_replace($u,'lang='.$l,$p,7); 
     else $h = $u.'&lang='.$l;
  }
  else $h = $u.'?lang='.$l;
  $r .= '<a href="'.$h.'">
<img src="/images/flag-'.$l.'.gif" alt="'.$l.'" border="0">
</a>
';
}
return "\n<!--�������� �� ����� �� �����-->$r<!--���� �� ����������-->\n";
}

?>
