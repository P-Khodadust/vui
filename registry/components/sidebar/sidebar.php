<?php
function sidebar_provider($c='',$class='',$attrs=[]){return el('div','sidebar-wrapper',"flex min-h-svh w-full",$c,$class,$attrs);}
function sidebar($c='',$id='sidebar',$class='',$attrs=[]){return el('aside','sidebar',"h-svh w-64 flex-col border-e bg-sidebar text-sidebar-foreground open:flex md:sticky md:top-0 md:flex md:shrink-0",$c,$class,['id'=>$id,'popover'=>true]+$attrs);}
function sidebar_trigger($label='Toggle Sidebar',$for='sidebar',$class='',$attrs=[]){return el('button','sidebar-trigger',cn(button_classes('ghost','icon'),"size-7 md:hidden"),raw(icon('M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zM9 3v18','size-4 rtl:rotate-180').'<span class="sr-only">'.e($label).'</span>'),$class,['type'=>'button','popovertarget'=>$for]+$attrs);}
function sidebar_inset($c='',$class='',$attrs=[]){return el('main','sidebar-inset',"flex w-full min-w-0 flex-1 flex-col bg-background",$c,$class,$attrs);}
function sidebar_header($c='',$class='',$attrs=[]){return el('div','sidebar-header',"flex flex-col gap-2 p-2",$c,$class,$attrs);}
function sidebar_footer($c='',$class='',$attrs=[]){return el('div','sidebar-footer',"flex flex-col gap-2 p-2",$c,$class,$attrs);}
function sidebar_content($c='',$class='',$attrs=[]){return el('div','sidebar-content',"flex min-h-0 flex-1 flex-col gap-2 overflow-auto",$c,$class,$attrs);}
function sidebar_group($c='',$class='',$attrs=[]){return el('div','sidebar-group',"flex w-full min-w-0 flex-col p-2",$c,$class,$attrs);}
function sidebar_group_label($c='',$class='',$attrs=[]){return el('div','sidebar-group-label',"flex h-8 shrink-0 items-center rounded-md px-2 text-xs font-medium text-sidebar-foreground/70",$c,$class,$attrs);}
function sidebar_menu($c='',$class='',$attrs=[]){return el('ul','sidebar-menu',"flex w-full min-w-0 flex-col gap-1",$c,$class,$attrs);}
function sidebar_menu_button($c='',$href='#',$active=false,$class='',$attrs=[]){return '<li>'.el('a','sidebar-menu-button',"flex h-8 w-full items-center gap-2 rounded-md p-2 text-start text-sm ring-sidebar-ring outline-hidden hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 aria-[current=page]:bg-sidebar-accent aria-[current=page]:font-medium aria-[current=page]:text-sidebar-accent-foreground",$c,$class,['href'=>$href,'aria-current'=>$active?'page':null]+$attrs).'</li>';}
