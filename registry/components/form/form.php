<?php
function form_item($c='',$class='',$attrs=[]){return el('div','form-item',"grid gap-2",$c,$class,$attrs);}
function form_label($c='',$error=false,$class='',$attrs=[]){return label($c,cn("data-[error=true]:text-destructive",$class),['data-error'=>$error?'true':null]+$attrs);}
function form_description($c='',$class='',$attrs=[]){return el('p','form-description',"text-sm text-muted-foreground",$c,$class,$attrs);}
function form_message($c='',$class='',$attrs=[]){return(string)$c===''?'':el('p','form-message',"text-sm text-destructive",$c,$class,$attrs);}
function form_field($name,$label,$value='',$error='',$description='',$type='text',$class='',$attrs=[]){
$id=$attrs['id']??$name;
$d=($description!=''?"$id-description ":'').($error!=''?"$id-message":'');
$a=['id'=>$id,'name'=>$name,'aria-invalid'=>$error!=''?'true':null,'aria-describedby'=>trim($d)?:null]+$attrs;
return form_item(raw(
form_label($label,$error!='',attrs:['for'=>$id]).
($type=='textarea'?textarea($value,'',$a):input('',['type'=>$type,'value'=>$value]+$a)).
($description!=''?form_description($description,attrs:['id'=>"$id-description"]):'').
form_message($error,attrs:['id'=>"$id-message"])
),$class);
}
