<?php
function select($c='',$size='default',$class='',$attrs=[]){
return el('div','select-wrapper',"relative w-fit has-[select:disabled]:opacity-50",raw(
el('select','select',"h-9 w-full min-w-0 appearance-none rounded-md border border-input bg-transparent px-3 py-2 pe-9 text-sm shadow-xs transition-[color,box-shadow] outline-none selection:bg-primary selection:text-primary-foreground placeholder:text-muted-foreground disabled:pointer-events-none disabled:cursor-not-allowed data-[size=sm]:h-8 data-[size=sm]:py-1 dark:bg-input/30 dark:hover:bg-input/50 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40",$c,'',['data-size'=>$size,'class'=>null]+$attrs).
icon('m6 9 6 6 6-6',"pointer-events-none absolute end-3.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground opacity-50 select-none")
),cn($class,$attrs['class']??''));
}
function select_option($c='',$value=null,$selected=false,$class='',$attrs=[]){return el('option','select-option',"bg-[Canvas] text-[CanvasText]",$c,$class,['value'=>$value,'selected'=>$selected]+$attrs);}
function select_optgroup($c='',$label='',$class='',$attrs=[]){return el('optgroup','select-optgroup',"bg-[Canvas] text-[CanvasText]",$c,$class,['label'=>$label]+$attrs);}
