<?php
// The native input is the no-JS fallback. date-picker.js swaps in the calendar. $attrs go on the input, $class on the wrapper.
function date_picker($class='',$attrs=[],$placeholder='Pick a date',$week_start=0,$labels=[]){
$v=(string)($attrs['value']??'');
$id=($attrs['id']??$attrs['name']??'date').'-picker';
return el('div','date-picker',"relative w-fit",raw(
input("w-56 data-[on]:sr-only [&::-webkit-calendar-picker-indicator]:cursor-pointer",['type'=>'date']+$attrs).
popover_trigger(raw(icon('M8 2v4M16 2v4M3 8h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z').'<span>'.e($placeholder).'</span>'),$id,button_classes('outline')." w-56 justify-start! font-normal data-[empty]:text-muted-foreground",['hidden'=>true,'data-placeholder'=>$placeholder,'disabled'=>$attrs['disabled']??false]).
popover_content(raw(calendar(substr($v,0,7)?:date('Y-m'),$v,'#{date}','#{month}',$week_start,$labels,"[&_[aria-disabled=true]]:pointer-events-none [&_[aria-disabled=true]]:opacity-50")),$id,"w-auto p-0")
),$class,['data-start'=>(int)$week_start%7]);
}
