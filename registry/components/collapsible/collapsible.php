<?php
function collapsible($c='',$open=false,$class='',$attrs=[]){return el('details','collapsible','',$c,$class,['open'=>$open]+$attrs);}
function collapsible_trigger($c='',$class='',$attrs=[]){return el('summary','collapsible-trigger',"list-none [&::-webkit-details-marker]:hidden",$c,$class,$attrs);}
function collapsible_content($c='',$class='',$attrs=[]){return el('div','collapsible-content','',$c,$class,$attrs);}
