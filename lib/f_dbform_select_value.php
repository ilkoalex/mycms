<?php
// Copyright: Vanyo Georgiev info@vanyog.com

// ��������� �������� html ��� �� ����� ������ �� ����� �� ��������,
// ������� �� � ���� $f �� ������� $tn_prefix.$t �� ������ �����.

include_once($idir.'/lib/f_db_field_values.php');

function dbform_select_value($f,$t,$sl='',$js=''){
$va = db_field_values($f,$t,1);
if ($js) $js = ' onchange="'.$js.'"';
$rz = "<select name=\"$f\"$js>\n";
foreach($va as $v){
 if ($v==$sl) $s = ' SELECTED'; else $s = '';
 $rz .= '<option value="'.$v."\"$s>".$v."</option>\n";
}
$rz .= "</select>\n";
return $rz;
}

?>
