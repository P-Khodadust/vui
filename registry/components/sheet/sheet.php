<?php
function sheet_trigger($c,$for,$class='',$attrs=[]){return dialog_trigger($c,$for,$class,$attrs);}
function sheet_content($c,$id,$side='end',$close='Close',$class='',$attrs=[]){return el('dialog','sheet-content',cn("max-h-none max-w-none flex-col gap-4 bg-background text-foreground shadow-lg outline-none open:flex backdrop:bg-black/50",['start'=>"me-auto h-full w-3/4 border-e sm:max-w-sm [--x:-100%] rtl:[--x:100%]",'top'=>"mb-auto w-full border-b [--y:-100%]",'bottom'=>"mt-auto w-full border-t [--y:100%]"][(string)$side]??"ms-auto h-full w-3/4 border-s sm:max-w-sm [--x:100%] rtl:[--x:-100%]"),raw(h($c).dialog_x($close)),$class,$attrs+['id'=>$id,'closedby'=>'any','aria-labelledby'=>"$id-title",'aria-describedby'=>"$id-description"]);}
function sheet_header($c='',$class='',$attrs=[]){return el('div','sheet-header',"flex flex-col gap-1.5 p-4",$c,$class,$attrs);}
function sheet_footer($c='',$class='',$attrs=[]){return el('div','sheet-footer',"mt-auto flex flex-col gap-2 p-4",$c,$class,$attrs);}
function sheet_title($c,$for,$class='',$attrs=[]){return el('h2','sheet-title',"font-semibold",$c,$class,['id'=>"$for-title"]+$attrs);}
function sheet_description($c,$for,$class='',$attrs=[]){return el('p','sheet-description',"text-sm text-muted-foreground",$c,$class,['id'=>"$for-description"]+$attrs);}
