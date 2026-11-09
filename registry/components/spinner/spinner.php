<?php
function spinner($class='',$label='Loading',$attrs=[]){return icon('M21 12a9 9 0 1 1-6.219-8.56',cn("size-4 animate-spin",$class),['role'=>'status','aria-label'=>$label,'aria-hidden'=>null]+$attrs);}
