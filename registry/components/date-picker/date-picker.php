<?php
function date_picker($class='',$attrs=[]){return input(cn("w-fit [&::-webkit-calendar-picker-indicator]:cursor-pointer",$class),['type'=>'date']+$attrs);}
