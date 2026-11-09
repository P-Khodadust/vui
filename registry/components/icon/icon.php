<?php
// $d: one 24x24 stroke path (Lucide geometry)
function icon($d,$class='size-4',$attrs=[]){
return el('svg',null,$class,raw('<path'.attrs(['d'=>$d]).'/>'),'',$attrs+['viewBox'=>'0 0 24 24','fill'=>'none','stroke'=>'currentColor','stroke-width'=>2,'stroke-linecap'=>'round','stroke-linejoin'=>'round','aria-hidden'=>'true']);
}
