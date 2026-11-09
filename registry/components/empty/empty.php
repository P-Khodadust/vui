<?php
// empty is a reserved word in PHP, hence empty_().
function empty_($c='',$class='',$attrs=[]){return el('div','empty',"flex min-w-0 flex-1 flex-col items-center justify-center gap-6 rounded-lg border-dashed p-6 text-center text-balance md:p-12",$c,$class,$attrs);}
function empty_header($c='',$class='',$attrs=[]){return el('div','empty-header',"flex max-w-sm flex-col items-center gap-2 text-center",$c,$class,$attrs);}
function empty_media($c='',$variant='default',$class='',$attrs=[]){return el('div','empty-icon',cn("mb-2 flex shrink-0 items-center justify-center [&_svg]:pointer-events-none [&_svg]:shrink-0",$variant=='icon'?"size-10 rounded-lg bg-muted text-foreground [&_svg:not([class*='size-'])]:size-6":"bg-transparent"),$c,$class,['data-variant'=>$variant]+$attrs);}
function empty_title($c='',$class='',$attrs=[]){return el('div','empty-title',"text-lg font-medium tracking-tight rtl:tracking-normal",$c,$class,$attrs);}
function empty_description($c='',$class='',$attrs=[]){return el('div','empty-description',"text-sm/relaxed text-muted-foreground [&>a]:underline [&>a]:underline-offset-4 [&>a:hover]:text-primary",$c,$class,$attrs);}
function empty_content($c='',$class='',$attrs=[]){return el('div','empty-content',"flex w-full max-w-sm min-w-0 flex-col items-center gap-4 text-sm text-balance",$c,$class,$attrs);}
