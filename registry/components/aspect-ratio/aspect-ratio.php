<?php
function aspect_ratio($c='',$ratio='16/9',$class='',$attrs=[]){return el('div','aspect-ratio','',$c,$class,['style'=>"aspect-ratio:$ratio"]+$attrs);}
