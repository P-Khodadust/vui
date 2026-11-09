<?php
// $as: h1 h2 h3 h4 p blockquote ul code lead large small muted
function typography($as='p',$c='',$class='',$attrs=[]){
[$t,$b]=[
'h1'=>['h1',"scroll-m-20 text-4xl font-extrabold tracking-tight text-balance rtl:tracking-normal"],
'h2'=>['h2',"scroll-m-20 border-b pb-2 text-3xl font-semibold tracking-tight first:mt-0 rtl:tracking-normal"],
'h3'=>['h3',"scroll-m-20 text-2xl font-semibold tracking-tight rtl:tracking-normal"],
'h4'=>['h4',"scroll-m-20 text-xl font-semibold tracking-tight rtl:tracking-normal"],
'blockquote'=>['blockquote',"mt-6 border-s-2 ps-6 italic rtl:not-italic"],
'ul'=>['ul',"my-6 ms-6 list-disc [&>li]:mt-2"],
'code'=>['code',"relative rounded bg-muted px-[0.3rem] py-[0.2rem] font-mono text-sm font-semibold"],
'lead'=>['p',"text-xl text-muted-foreground"],
'large'=>['div',"text-lg font-semibold"],
'small'=>['small',"text-sm leading-none font-medium"],
'muted'=>['p',"text-sm text-muted-foreground"],
][(string)$as]??['p',"leading-7 [&:not(:first-child)]:mt-6"];
return el($t,'typography',$b,$c,$class,$attrs);
}
