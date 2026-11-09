<?php
// switch is a reserved word in PHP, hence switch_().
function switch_($size='default',$class='',$attrs=[]){
return el('input','switch',cn(
"peer inline-flex shrink-0 appearance-none items-center rounded-full border border-transparent shadow-xs transition-all outline-none not-checked:bg-input checked:bg-primary focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 dark:not-checked:bg-input/80 before:pointer-events-none before:block before:rounded-full before:bg-background before:transition-transform checked:before:translate-x-[calc(100%-2px)] rtl:checked:before:-translate-x-[calc(100%-2px)] dark:not-checked:before:bg-foreground dark:checked:before:bg-primary-foreground",
$size=='sm'?"h-3.5 w-6 before:size-3":"h-[1.15rem] w-8 before:size-4"
),null,$class,['type'=>'checkbox','role'=>'switch','data-size'=>$size]+$attrs);
}
