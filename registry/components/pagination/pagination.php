<?php
function pagination($c='',$class='',$attrs=[]){return el('nav','pagination',"flex w-full justify-center",$c,$class,$attrs+['aria-label'=>'pagination']);}
function pagination_content($c='',$class='',$attrs=[]){return el('ul','pagination-content',"flex items-center gap-1",$c,$class,$attrs);}
function pagination_item($c='',$class='',$attrs=[]){return el('li','pagination-item','',$c,$class,$attrs);}
function pagination_link($c='',$href='#',$active=false,$class='',$attrs=[]){return el('a','pagination-link',button_classes($active?'outline':'ghost','icon'),$c,$class,['href'=>$href,'aria-current'=>$active?'page':null]+$attrs);}
function pagination_previous($href='#',$label='Previous',$class='',$attrs=[]){return pagination_link(raw(icon('m15 18-6-6 6-6','size-4 rtl:rotate-180').e($label)),$href,false,cn("w-auto gap-1 px-2.5",$class),$attrs);}
function pagination_next($href='#',$label='Next',$class='',$attrs=[]){return pagination_link(raw(e($label).icon('m9 18 6-6-6-6','size-4 rtl:rotate-180')),$href,false,cn("w-auto gap-1 px-2.5",$class),$attrs);}
function pagination_ellipsis($label='More pages',$class='',$attrs=[]){return el('span','pagination-ellipsis',"flex size-9 items-center justify-center",raw(icon('M12 12h.01M19 12h.01M5 12h.01').'<span class="sr-only">'.e($label).'</span>'),$class,$attrs);}
