<?php
function drawer_trigger($c,$for,$class='',$attrs=[]){return dialog_trigger($c,$for,$class,$attrs);}
function drawer_close($c='Close',$class='',$attrs=[]){return dialog_close($c,$class,$attrs);}
function drawer_content($c,$id,$class='',$attrs=[]){return el('dialog','drawer-content',"mt-auto max-h-[80vh] w-full max-w-none flex-col rounded-t-lg border-t bg-background text-foreground outline-none open:flex backdrop:bg-black/50",raw('<div class="mx-auto mt-4 h-2 w-[100px] shrink-0 rounded-full bg-muted"></div>'.h($c)),$class,$attrs+['id'=>$id,'closedby'=>'any','aria-labelledby'=>"$id-title",'aria-describedby'=>"$id-description"]);}
function drawer_header($c='',$class='',$attrs=[]){return el('div','drawer-header',"flex flex-col gap-0.5 p-4 text-center md:gap-1.5 md:text-start",$c,$class,$attrs);}
function drawer_footer($c='',$class='',$attrs=[]){return el('div','drawer-footer',"mt-auto flex flex-col gap-2 p-4",$c,$class,$attrs);}
function drawer_title($c,$for='',$class='',$attrs=[]){return el('h2','drawer-title',"font-semibold text-foreground",$c,$class,['id'=>$for?"$for-title":null]+$attrs);}
function drawer_description($c,$for='',$class='',$attrs=[]){return el('p','drawer-description',"text-sm text-muted-foreground",$c,$class,['id'=>$for?"$for-description":null]+$attrs);}
