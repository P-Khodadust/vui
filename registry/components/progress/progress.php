<?php
// $value null renders an indeterminate bar.
function progress($value=null,$max=100,$class='',$attrs=[]){return el('progress','progress',"block h-2 w-full appearance-none overflow-hidden rounded-full bg-primary/20 [&::-webkit-progress-bar]:bg-transparent [&::-webkit-progress-value]:bg-primary [&::-webkit-progress-value]:transition-all [&::-moz-progress-bar]:bg-primary [&::-moz-progress-bar]:transition-all",'',$class,['value'=>$value,'max'=>$max]+$attrs);}
