<?php
function dropdown_menu_trigger($c,$for,$class='',$attrs=[]){return el('button','dropdown-menu-trigger','',$c,$class,['type'=>'button','popovertarget'=>$for,'style'=>"anchor-name:--$for"]+$attrs);}
function dropdown_menu_content($c,$id,$class='',$attrs=[]){return el('div','dropdown-menu-content',"m-auto max-h-[calc(50vh-1.5rem)] min-w-[8rem] rounded-md border bg-popover p-1 text-popover-foreground shadow-md [position-area:block-end_span-inline-end] [position-try-fallbacks:flip-block,flip-inline] supports-[anchor-name:--a]:m-0 supports-[anchor-name:--a]:mt-1",$c,$class,['id'=>$id,'popover'=>true,'style'=>"position-anchor:--$id"]+$attrs);}
function dropdown_menu_item($c='',$for='',$variant='default',$class='',$attrs=[]){
$a=isset($attrs['href']);
return el($a?'a':'button','dropdown-menu-item',cn(
"relative flex w-full items-center gap-2 rounded-sm px-2 py-1.5 text-start text-sm outline-hidden select-none disabled:pointer-events-none disabled:opacity-50 [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4",
$variant=='destructive'?"text-destructive hover:bg-destructive/10 focus-visible:bg-destructive/10 dark:hover:bg-destructive/20 dark:focus-visible:bg-destructive/20":"hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent focus-visible:text-accent-foreground"
),$c,$class,['data-variant'=>$variant,'type'=>$a?null:'button','popovertarget'=>$a?null:($for?:null),'popovertargetaction'=>$a||!$for?null:'hide']+$attrs);
}
function dropdown_menu_label($c='',$class='',$attrs=[]){return el('div','dropdown-menu-label',"px-2 py-1.5 text-sm font-medium",$c,$class,$attrs);}
function dropdown_menu_separator($class='',$attrs=[]){return el('div','dropdown-menu-separator',"-mx-1 my-1 h-px bg-border",'',$class,['role'=>'separator']+$attrs);}
function dropdown_menu_shortcut($c='',$class='',$attrs=[]){return el('span','dropdown-menu-shortcut',"ms-auto text-xs tracking-widest text-muted-foreground rtl:tracking-normal",$c,$class,$attrs);}
