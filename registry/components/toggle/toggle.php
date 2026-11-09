<?php
function toggle_classes($variant='default',$size='default'){
return cn("inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium whitespace-nowrap transition-all outline-none hover:bg-muted hover:text-muted-foreground has-focus-visible:border-ring has-focus-visible:ring-[3px] has-focus-visible:ring-ring/50 has-disabled:pointer-events-none has-disabled:opacity-50 has-checked:bg-accent has-checked:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4",$variant=='outline'?"border border-input bg-transparent shadow-xs hover:bg-accent hover:text-accent-foreground":"bg-transparent",$size=='sm'?"h-8 min-w-8 px-1.5":($size=='lg'?"h-10 min-w-10 px-2.5":"h-9 min-w-9 px-2"));
}
// A checkbox in a label: no JS and it submits with forms. $attrs go on the input.
function toggle($c='',$pressed=false,$variant='default',$size='default',$class='',$attrs=[]){
return el('label','toggle',toggle_classes($variant,$size),raw('<input'.attrs(['type'=>'checkbox','class'=>'sr-only','checked'=>$pressed]+$attrs).'>'.h($c)),$class);
}
