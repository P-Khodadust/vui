<?php
function alert_dialog_trigger($c,$for,$class='',$attrs=[]){return dialog_trigger($c,$for,$class,$attrs);}
// No X and no outside click: the user must choose. Esc cancels.
function alert_dialog_content($c,$id,$class='',$attrs=[]){return dialog_content($c,$id,false,$class,$attrs+['role'=>'alertdialog','closedby'=>'closerequest']);}
function alert_dialog_header($c='',$class='',$attrs=[]){return dialog_header($c,$class,$attrs);}
function alert_dialog_footer($c='',$class='',$attrs=[]){return dialog_footer($c,$class,$attrs);}
function alert_dialog_title($c,$for='',$class='',$attrs=[]){return dialog_title($c,$for,$class,$attrs);}
function alert_dialog_description($c,$for='',$class='',$attrs=[]){return dialog_description($c,$for,$class,$attrs);}
function alert_dialog_action($c='',$class='',$attrs=[]){return dialog_close($c,cn(button_classes(),$class),$attrs);}
function alert_dialog_cancel($c='',$class='',$attrs=[]){return dialog_close($c,cn(button_classes('outline'),$class),$attrs);}
