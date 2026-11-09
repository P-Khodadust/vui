<?php
// Blade pre-escapes :attrs
class Raw implements Stringable{function __construct(public string $h){}function __toString():string{return $this->h;}}
function raw(string $h):Raw{return new Raw($h);}
if(!function_exists('e')){function e($v):string{return htmlspecialchars((string)$v);}}
function h($c):string{return $c instanceof Raw?"$c":e($c);}
function cn(?string ...$p):string{
$o=[];
foreach(preg_split('/\s+/',implode(' ',$p),0,PREG_SPLIT_NO_EMPTY) as $c){
$k=preg_match('/^(.*:)?-?(rounded(?!-([tb][lr]|[se][se])\b)(-[tblrse]\b)?(?=-|!?$)|([pm][xytrblse]?|[hw]|size|gap(-[xy])?)(?=-))/',$c,$m)?"$m[1]$m[2]:".($c[-1]=='!'):$c;
unset($o[$k]);
$o[$k]=$c;
}
return implode(' ',$o);
}
function attrs(array $a):string{
$s='';
foreach($a as $k=>$v){
$k=preg_replace('/[^\w:@.-]/','',$k);
if($k!=''&&$v!==false&&$v!==null)$s.=$v===true?" $k":" $k=\"".htmlspecialchars((string)$v,double_encode:false).'"';
}
return $s;
}
function el(string $t,?string $s,?string $b,$c,?string $class='',array $a=[]):string{
return"<$t".attrs(['data-slot'=>$s,'class'=>cn($b,$class,$a['class']??'')?:null]+$a).'>'.($t=='input'||$t=='img'?'':h($c)."</$t>");
}
array_map(fn($f)=>require_once $f,glob(__DIR__.'/components/*.php')?:[]);
