<?php
// Native <input list>: type to filter, free text allowed, popup styled by the browser.
function combobox($c='',$id='',$class='',$attrs=[]){return input($class,['id'=>$id,'list'=>"$id-list"]+$attrs).el('datalist',null,'',$c,'',['id'=>"$id-list"]);}
function combobox_option($value='',$c='',$attrs=[]){return el('option',null,'',$c,'',['value'=>$value]+$attrs);}
