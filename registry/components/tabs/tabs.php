<?php
function tabs($c='',$class='',$attrs=[]){return el('div','tabs',"flex flex-col gap-2",$c,$class,$attrs);}
function tabs_list($c='',$class='',$attrs=[]){return el('div','tabs-list',"inline-flex h-9 w-fit items-center justify-center rounded-lg bg-muted p-[3px] text-muted-foreground",$c,$class,['role'=>'tablist']+$attrs);}
// $value: unique per page. attrs href: a link, for tabs without tabs.js.
function tabs_trigger($c,$value,$active=false,$class='',$attrs=[]){
$a=isset($attrs['href']);
return el($a?'a':'button','tabs-trigger',"relative inline-flex h-[calc(100%-1px)] flex-1 items-center justify-center gap-1.5 rounded-md border border-transparent px-2 py-1 text-sm font-medium whitespace-nowrap text-foreground/60 transition-all hover:text-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 focus-visible:outline-ring disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm dark:text-muted-foreground dark:hover:text-foreground dark:data-[state=active]:border-input dark:data-[state=active]:bg-input/30 dark:data-[state=active]:text-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4",$c,$class,['type'=>$a?null:'button','role'=>'tab','id'=>"tab-$value",'aria-controls'=>"panel-$value",'aria-selected'=>$active?'true':'false','data-state'=>$active?'active':'inactive','tabindex'=>$active?0:-1]+$attrs);
}
function tabs_content($c,$value,$active=false,$class='',$attrs=[]){return el('div','tabs-content',"flex-1",$c,$class,['role'=>'tabpanel','id'=>"panel-$value",'aria-labelledby'=>"tab-$value",'tabindex'=>0,'hidden'=>!$active]+$attrs);}
