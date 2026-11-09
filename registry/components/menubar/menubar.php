<?php
function menubar($c='',$class='',$attrs=[]){return el('div','menubar',"flex h-9 items-center gap-1 rounded-md border bg-background p-1 shadow-xs",$c,$class,$attrs);}
function menubar_trigger($c,$for,$class='',$attrs=[]){return dropdown_menu_trigger($c,$for,cn("flex items-center rounded-sm px-2 py-1 text-sm font-medium outline-hidden select-none hover:bg-accent focus-visible:bg-accent focus-visible:text-accent-foreground [&:has(+:popover-open)]:bg-accent [&:has(+:popover-open)]:text-accent-foreground",$class),$attrs);}
function menubar_content($c,$id,$class='',$attrs=[]){return dropdown_menu_content($c,$id,cn("min-w-[12rem]!",$class),$attrs);}
function menubar_item($c='',$for='',$variant='default',$class='',$attrs=[]){return dropdown_menu_item($c,$for,$variant,$class,$attrs);}
function menubar_label($c='',$class='',$attrs=[]){return dropdown_menu_label($c,$class,$attrs);}
function menubar_separator($class='',$attrs=[]){return dropdown_menu_separator($class,$attrs);}
function menubar_shortcut($c='',$class='',$attrs=[]){return dropdown_menu_shortcut($c,$class,$attrs);}
