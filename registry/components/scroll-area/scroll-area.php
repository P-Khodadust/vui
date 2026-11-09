<?php
function scroll_area($c='',$class='',$attrs=[]){return el('div','scroll-area',"overflow-auto rounded-[inherit] [scrollbar-color:var(--border)_transparent] [scrollbar-width:thin] outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1",$c,$class,['tabindex'=>0]+$attrs);}
