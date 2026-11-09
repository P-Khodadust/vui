<?php
function separator($orientation='horizontal',$decorative=true,$class='',$attrs=[]){
return el('div','separator',"shrink-0 bg-border data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px",'',$class,['data-orientation'=>$orientation,'role'=>$decorative?'none':'separator','aria-orientation'=>!$decorative&&$orientation=='vertical'?'vertical':null]+$attrs);
}
