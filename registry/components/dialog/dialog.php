<?php
function dialog_trigger($c,$for,$class='',$attrs=[]){
return el('button','dialog-trigger','',$c,$class,['type'=>'button','aria-haspopup'=>'dialog','commandfor'=>$for,'command'=>'show-modal','onclick'=>"this.commandForElement||document.getElementById(this.getAttribute('commandfor')).showModal()"]+$attrs);
}
function dialog_close($c='Close',$class='',$attrs=[]){
return el('button','dialog-close','',$c,$class,['type'=>'button','onclick'=>"this.closest('dialog').close()"]+$attrs);
}
function dialog_x($close){return $close?dialog_close(raw(icon('M18 6 6 18M6 6l12 12').'<span class="sr-only">'.e($close).'</span>'),"absolute end-4 top-4 rounded-xs opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-hidden"):'';}
function dialog_content($c,$id,$close='Close',$class='',$attrs=[]){
return el('dialog','dialog-content',"m-auto w-full max-w-[calc(100%-2rem)] gap-4 rounded-lg border bg-background p-6 text-foreground shadow-lg outline-none open:grid sm:max-w-lg backdrop:bg-black/50",raw(h($c).dialog_x($close)),$class,$attrs+['id'=>$id,'closedby'=>'any','aria-labelledby'=>"$id-title",'aria-describedby'=>"$id-description"]);
}
function dialog_header($c='',$class='',$attrs=[]){return el('div','dialog-header',"flex flex-col gap-2 text-center sm:text-start",$c,$class,$attrs);}
function dialog_footer($c='',$class='',$attrs=[]){return el('div','dialog-footer',"flex flex-col-reverse gap-2 sm:flex-row sm:justify-end",$c,$class,$attrs);}
function dialog_title($c,$for='',$class='',$attrs=[]){return el('h2','dialog-title',"text-lg leading-none font-semibold",$c,$class,['id'=>$for?"$for-title":null]+$attrs);}
function dialog_description($c,$for='',$class='',$attrs=[]){return el('p','dialog-description',"text-sm text-muted-foreground",$c,$class,['id'=>$for?"$for-description":null]+$attrs);}
