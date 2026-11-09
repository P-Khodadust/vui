<?php
// Needs context-menu.js. Without it the browser's own menu shows.
function context_menu_trigger($c='',$for='',$class='',$attrs=[]){return el('div','context-menu-trigger','',$c,$class,['tabindex'=>0,'data-for'=>$for]+$attrs);}
function context_menu_content($c,$id,$class='',$attrs=[]){return dropdown_menu_content($c,$id,$class,$attrs);}
function context_menu_item($c='',$for='',$variant='default',$class='',$attrs=[]){return dropdown_menu_item($c,$for,$variant,$class,$attrs);}
function context_menu_label($c='',$class='',$attrs=[]){return dropdown_menu_label($c,$class,$attrs);}
function context_menu_separator($class='',$attrs=[]){return dropdown_menu_separator($class,$attrs);}
function context_menu_shortcut($c='',$class='',$attrs=[]){return dropdown_menu_shortcut($c,$class,$attrs);}
