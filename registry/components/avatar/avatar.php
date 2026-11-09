<?php
function avatar($c='',$size='default',$class='',$attrs=[]){return el('span','avatar',"group/avatar relative flex size-8 shrink-0 overflow-hidden rounded-full select-none data-[size=lg]:size-10 data-[size=sm]:size-6",$c,$class,['data-size'=>$size]+$attrs);}
// Sits over the fallback; a failed load removes it so the fallback shows.
function avatar_image($src,$alt='',$class='',$attrs=[]){return el('img','avatar-image',"absolute inset-0 aspect-square size-full object-cover",null,$class,['src'=>$src,'alt'=>$alt,'onerror'=>'this.remove()']+$attrs);}
function avatar_fallback($c='',$class='',$attrs=[]){return el('span','avatar-fallback',"flex size-full items-center justify-center rounded-full bg-muted text-sm text-muted-foreground group-data-[size=sm]/avatar:text-xs",$c,$class,$attrs);}
