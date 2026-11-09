<?php
function toggle_group($c='',$class='',$attrs=[]){return el('div','toggle-group',"flex w-fit items-center rounded-md",$c,$class,['role'=>'group']+$attrs);}
// $type 'radio' = one on, 'checkbox' = many on. $attrs go on the input.
function toggle_group_item($c,$name,$value='',$checked=false,$type='radio',$variant='default',$size='default',$class='',$attrs=[]){
return el('label','toggle-group-item',cn(toggle_classes($variant,$size),"shrink-0 rounded-none px-3 shadow-none! first:rounded-s-md last:rounded-e-md focus-within:z-10",$variant=='outline'?"border-s-0 first:border-s":''),raw('<input'.attrs(['type'=>$type,'name'=>$name,'value'=>$value,'class'=>'sr-only','checked'=>$checked]+$attrs).'>'.h($c)),$class);
}
