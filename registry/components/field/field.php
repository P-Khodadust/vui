<?php
function field_set($c='',$class='',$attrs=[]){return el('fieldset','field-set',"flex flex-col gap-6",$c,$class,$attrs);}
function field_legend($c='',$variant='legend',$class='',$attrs=[]){return el('legend','field-legend',cn("mb-3 font-medium",$variant=='label'?'text-sm':'text-base'),$c,$class,$attrs);}
function field_group($c='',$class='',$attrs=[]){return el('div','field-group',"flex w-full flex-col gap-7",$c,$class,$attrs);}
function field($c='',$orientation='vertical',$invalid=false,$class='',$attrs=[]){return el('div','field',cn("group/field flex w-full gap-3 data-[invalid=true]:text-destructive",$orientation=='horizontal'?"flex-row items-center [&>[data-slot=label]]:flex-auto":"flex-col [&>*]:w-full [&>.sr-only]:w-auto"),$c,$class,['role'=>'group','data-orientation'=>$orientation,'data-invalid'=>$invalid?'true':null]+$attrs);}
function field_label($c='',$class='',$attrs=[]){return label($c,cn("flex w-fit gap-2 leading-snug group-data-[disabled=true]/field:opacity-50",$class),$attrs);}
function field_description($c='',$class='',$attrs=[]){return el('p','field-description',"text-sm leading-normal font-normal text-muted-foreground last:mt-0 nth-last-2:-mt-1",$c,$class,$attrs);}
function field_error($c='',$class='',$attrs=[]){return(string)$c===''?'':el('div','field-error',"text-sm font-normal text-destructive",$c,$class,['role'=>'alert']+$attrs);}
