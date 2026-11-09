<?php
function alert($c='',$variant='default',$class='',$attrs=[]){
return el('div','alert',cn(
"relative grid w-full grid-cols-[0_1fr] items-start gap-y-0.5 rounded-lg border px-4 py-3 text-sm has-[>svg]:grid-cols-[calc(var(--spacing)*4)_1fr] has-[>svg]:gap-x-3 [&>svg]:size-4 [&>svg]:translate-y-0.5 [&>svg]:text-current",
['default'=>"bg-card text-card-foreground",'destructive'=>"bg-card text-destructive *:data-[slot=alert-description]:text-destructive/90 [&>svg]:text-current"][(string)$variant]??''
),$c,$class,$attrs+['role'=>'alert']);
}
function alert_title($c='',$class='',$attrs=[]){return el('div','alert-title',"col-start-2 line-clamp-1 min-h-4 font-medium tracking-tight rtl:tracking-normal",$c,$class,$attrs);}
function alert_description($c='',$class='',$attrs=[]){return el('div','alert-description',"col-start-2 grid justify-items-start gap-1 text-sm text-muted-foreground [&_p]:leading-relaxed",$c,$class,$attrs);}
