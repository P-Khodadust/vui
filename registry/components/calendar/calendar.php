<?php
// {date}, {month} fill $href, $nav. $labels: title weekdays prev next.
function calendar($month,$selected='',$href='',$nav='',$week_start=0,$labels=[],$class='',$attrs=[]){
$t=strtotime("$month-01")?:time();
$week_start=(int)$week_start%7;
$labels+=['title'=>date('F Y',$t),'weekdays'=>'Su Mo Tu We Th Fr Sa','prev'=>'Previous month','next'=>'Next month'];
$w=explode(' ',$labels['weekdays']);
$ym=date('Y-m',$t);
$n=date('t',$t);
$pad=(date('w',$t)-$week_start+7)%7;
$head='';
for($i=0;$i<7;$i++)$head.=el('th',null,"size-8 text-[0.8rem] font-normal text-muted-foreground",$w[($i+$week_start)%7]??'','',['scope'=>'col']);
$rows=str_repeat('<td></td>',$pad);
for($d=1;$d<=$n;$d++){
$date=sprintf('%s-%02d',$ym,$d);
$rows.='<td class="p-0">'.el($href?'a':'span',null,cn(button_classes('ghost','icon'),"size-8 font-normal data-[today]:bg-accent data-[today]:text-accent-foreground aria-[current=date]:bg-primary! aria-[current=date]:text-primary-foreground!"),$d,'',['href'=>$href?str_replace('{date}',$date,$href):null,'aria-current'=>$date==$selected?'date':null,'data-today'=>$date==date('Y-m-d')]).'</td>';
if(($pad+$d)%7==0&&$d<$n)$rows.='</tr><tr>';
}
$go=fn($m,$d,$side,$label)=>$nav?el('a',null,cn(button_classes('ghost','icon'),"absolute top-0 size-8 $side"),raw(icon($d,'size-4 rtl:rotate-180')),'',['href'=>str_replace('{month}',date('Y-m',strtotime($m,$t)),$nav),'aria-label'=>$label]):'';
return el('div','calendar',"w-fit rounded-md bg-background p-3",raw('<div class="relative flex h-8 items-center justify-center text-sm font-medium">'.$go('-1 month','m15 18-6-6 6-6','start-0',$labels['prev']).e($labels['title']).$go('+1 month','m9 18 6-6-6-6','end-0',$labels['next']).'</div><table class="mt-4 border-collapse"><thead><tr>'.$head.'</tr></thead><tbody><tr>'.$rows.'</tr></tbody></table>'),$class,$attrs);
}
