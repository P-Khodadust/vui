<?php
function toast($c='',$variant='default',$close='Close',$class='',$attrs=[]){
return el('div','toast',"flex w-full items-center justify-between gap-2 rounded-md border bg-popover p-4 text-sm text-popover-foreground shadow-lg data-[variant=destructive]:text-destructive",raw('<span data-slot="toast-title">'.h($c).'</span>'.el('button','toast-close',"shrink-0 rounded-xs opacity-70 hover:opacity-100",raw(icon('M18 6 6 18M6 6l12 12')),'',['type'=>'button','aria-label'=>$close,'onclick'=>'this.parentNode.dataset.out=1;setTimeout(()=>this.parentNode.remove(),200)'])),$class,['data-variant'=>$variant,'role'=>$variant=='destructive'?'alert':null]+$attrs);
}
// The template lets toast.js add toasts without any class strings in JS.
function toaster($c='',$close='Close',$class='',$attrs=[]){
return el('section','toaster',"fixed end-4 bottom-4 z-50 flex w-[calc(100%-2rem)] max-w-[356px] flex-col gap-2",raw(h($c).'<template>'.toast('','default',$close).'</template>'),$class,$attrs+['aria-label'=>'Notifications','aria-live'=>'polite']);
}
