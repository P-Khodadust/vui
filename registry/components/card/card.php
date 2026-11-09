<?php
function card($c='',$class='',$attrs=[]){return el('div','card',"flex flex-col gap-6 rounded-xl border bg-card py-6 text-card-foreground shadow-sm",$c,$class,$attrs);}
function card_header($c='',$class='',$attrs=[]){return el('div','card-header',"grid auto-rows-min grid-rows-[auto_auto] items-start gap-2 px-6 has-data-[slot=card-action]:grid-cols-[1fr_auto] [.border-b]:pb-6",$c,$class,$attrs);}
function card_title($c='',$class='',$attrs=[]){return el('div','card-title',"leading-none font-semibold",$c,$class,$attrs);}
function card_description($c='',$class='',$attrs=[]){return el('div','card-description',"text-sm text-muted-foreground",$c,$class,$attrs);}
function card_action($c='',$class='',$attrs=[]){return el('div','card-action',"col-start-2 row-span-2 row-start-1 self-start justify-self-end",$c,$class,$attrs);}
function card_content($c='',$class='',$attrs=[]){return el('div','card-content',"px-6",$c,$class,$attrs);}
function card_footer($c='',$class='',$attrs=[]){return el('div','card-footer',"flex items-center px-6 [.border-t]:pt-6",$c,$class,$attrs);}
