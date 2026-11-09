<?php
function accordion($c='',$class='',$attrs=[]){return el('div','accordion','',$c,$class,$attrs);}
// Same $name on several items = one open at a time. No $name = independent.
function accordion_item($c='',$name='',$open=false,$class='',$attrs=[]){return el('details','accordion-item',"group border-b last:border-b-0",$c,$class,['name'=>$name?:null,'open'=>$open]+$attrs);}
function accordion_trigger($c='',$class='',$attrs=[]){return el('summary','accordion-trigger',"flex items-start justify-between gap-4 rounded-md py-4 text-start text-sm font-medium transition-all outline-none hover:underline focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 [&::-webkit-details-marker]:hidden",raw(h($c).icon('m6 9 6 6 6-6',"pointer-events-none size-4 shrink-0 translate-y-0.5 text-muted-foreground transition-transform duration-200 group-open:rotate-180")),$class,$attrs);}
function accordion_content($c='',$class='',$attrs=[]){return el('div','accordion-content',"overflow-hidden pt-0 pb-4 text-sm",$c,$class,$attrs);}
