<?php
function breadcrumb($c='',$class='',$attrs=[]){return el('nav','breadcrumb','',$c,$class,$attrs+['aria-label'=>'breadcrumb']);}
function breadcrumb_list($c='',$class='',$attrs=[]){return el('ol','breadcrumb-list',"flex flex-wrap items-center gap-1.5 text-sm break-words text-muted-foreground sm:gap-2.5",$c,$class,$attrs);}
function breadcrumb_item($c='',$class='',$attrs=[]){return el('li','breadcrumb-item',"inline-flex items-center gap-1.5",$c,$class,$attrs);}
function breadcrumb_link($c='',$href='#',$class='',$attrs=[]){return el('a','breadcrumb-link',"transition-colors hover:text-foreground",$c,$class,['href'=>$href]+$attrs);}
function breadcrumb_page($c='',$class='',$attrs=[]){return el('span','breadcrumb-page',"font-normal text-foreground",$c,$class,['role'=>'link','aria-disabled'=>'true','aria-current'=>'page']+$attrs);}
function breadcrumb_separator($c=null,$class='',$attrs=[]){return el('li','breadcrumb-separator',"[&>svg]:size-3.5",$c??raw(icon('m9 18 6-6-6-6','rtl:rotate-180')),$class,['aria-hidden'=>'true']+$attrs);}
function breadcrumb_ellipsis($label='More',$class='',$attrs=[]){return el('span','breadcrumb-ellipsis',"flex size-9 items-center justify-center",raw(icon('M12 12h.01M19 12h.01M5 12h.01').'<span class="sr-only">'.e($label).'</span>'),$class,$attrs);}
