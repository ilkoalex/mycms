<?php

//
// ��������� ���� ����� $f �� ������ � ���������� $mod_pth ��� � ���������� mod
// ����� ���������� ��� �� ������������ �� ������ ��� '', ��� �� � ������� �����.
//
function mod_path($f){
global $mod_pth,$pth;
$fn = "$mod_pth$f/f_$f.php";
$afn = $_SERVER['DOCUMENT_ROOT']."$fn";
if ( ($mod_pth!='/mod/') && !file_exists($afn) ){
  $fn = $pth."mod/$f/f_$f.php"; 
  $afn = $_SERVER['DOCUMENT_ROOT']."$fn";
}
if (file_exists($afn)) return $afn;
else return '';
}

?>
