<?php
function slider($value=null,$min=0,$max=100,$step=1,$class='',$attrs=[]){return el('input','slider',"w-full rounded-full accent-primary outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:opacity-50",null,$class,['type'=>'range','min'=>$min,'max'=>$max,'step'=>$step,'value'=>$value]+$attrs);}
