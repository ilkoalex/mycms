<?php
// Copyright: Vanyo Georgiev info@vanyog.com

// ������� ������� �� ������ ��� ��� �� �������
// ���������� �� ������ ������ �� �� ���������� ������

function view_table($da,$id=''){
if ($id) $id = " id=\"$id\"";
$rz = "<table$id>";
foreach($da as $i=>$d){
  if ($i==0){ // ������� �� ���������
    $rz .= '<tr>';
    foreach($d as $k=>$l) $rz .= "<th>$k</th>";
    $rz .= "</tr>\n";
  }
  $rz .= '<tr>';
  foreach($d as $k=>$l) $rz .= "<td>".stripslashes($l)."</td>";
  $rz .= "</tr>\n";
}
$rz .= '</table>';
return $rz;
}
 
?>
