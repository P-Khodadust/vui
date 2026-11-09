<?php
function kbd($c='',$class='',$attrs=[]){return el('kbd','kbd',"pointer-events-none inline-flex h-5 w-fit min-w-5 items-center justify-center gap-1 rounded-sm bg-muted px-1 font-sans text-xs font-medium text-muted-foreground select-none [&_svg:not([class*='size-'])]:size-3 [[data-slot=tooltip-content]_&]:bg-background/20 [[data-slot=tooltip-content]_&]:text-background dark:[[data-slot=tooltip-content]_&]:bg-background/10",$c,$class,$attrs);}
function kbd_group($c='',$class='',$attrs=[]){return el('kbd','kbd-group',"inline-flex items-center gap-1",$c,$class,$attrs);}
