<?php
function checkbox($class='',$attrs=[]){
return el('input','checkbox',"peer grid size-4 shrink-0 appearance-none place-content-center rounded-[4px] border border-input shadow-xs transition-shadow outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 checked:border-primary checked:bg-primary checked:text-primary-foreground dark:bg-input/30 dark:aria-invalid:ring-destructive/40 dark:checked:bg-primary before:size-2.5 before:bg-current before:[clip-path:polygon(14%_44%,0_65%,50%_100%,100%_16%,80%_0,43%_62%)] before:transition-[scale] not-checked:before:scale-0",null,$class,['type'=>'checkbox']+$attrs);
}
