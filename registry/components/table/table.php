<?php
function table($c='',$class='',$attrs=[]){return'<div data-slot="table-container" class="w-full overflow-x-auto">'.el('table','table',"w-full caption-bottom text-sm",$c,$class,$attrs).'</div>';}
function table_header($c='',$class='',$attrs=[]){return el('thead','table-header',"[&_tr]:border-b",$c,$class,$attrs);}
function table_body($c='',$class='',$attrs=[]){return el('tbody','table-body',"[&_tr:last-child]:border-0",$c,$class,$attrs);}
function table_footer($c='',$class='',$attrs=[]){return el('tfoot','table-footer',"border-t bg-muted/50 font-medium [&>tr]:last:border-b-0",$c,$class,$attrs);}
function table_row($c='',$class='',$attrs=[]){return el('tr','table-row',"border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted",$c,$class,$attrs);}
function table_head($c='',$class='',$attrs=[]){return el('th','table-head',"h-10 px-2 text-start align-middle font-medium whitespace-nowrap text-foreground [&:has([type=checkbox])]:pe-0 [&>[type=checkbox]]:translate-y-[2px]",$c,$class,$attrs);}
function table_cell($c='',$class='',$attrs=[]){return el('td','table-cell',"p-2 align-middle whitespace-nowrap [&:has([type=checkbox])]:pe-0 [&>[type=checkbox]]:translate-y-[2px]",$c,$class,$attrs);}
function table_caption($c='',$class='',$attrs=[]){return el('caption','table-caption',"mt-4 text-sm text-muted-foreground",$c,$class,$attrs);}
