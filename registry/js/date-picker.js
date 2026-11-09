document.querySelectorAll('[data-slot=date-picker]').forEach(r=>{
let w=new Date
const Q=(e,q)=>e.querySelector(q),i=Q(r,'input'),t=Q(r,'button'),p=Q(r,'[popover]'),b=Q(p,'tbody'),[v,x]=p.querySelectorAll('div>a'),c=Q(b,'a').parentNode.cloneNode(1),L=(document.documentElement.lang||'en')+'-u-ca-gregory',f=(o,d)=>new Intl.DateTimeFormat(L,o).format(d),s=d=>d.toLocaleDateString('sv'),P=a=>new Date(a.slice(-10)+'T00:00'),F=d=>Q(b,`[href="#${s(d)}"]`)?.focus(),
l=()=>{const a=i.value,o=a?f({dateStyle:'medium'},P(a)):t.dataset.placeholder,m=i.ariaLabel||i.labels?.[0]?.textContent
t.lastChild.textContent=o
t.toggleAttribute('data-empty',!a)
t.ariaLabel=m?m+': '+o:null},
g=()=>{const y=w.getFullYear(),m=w.getMonth(),h=(new Date(y,m).getDay()-r.dataset.start+7)%7,k=new Date(y,m+1,0).getDate()
v.nextSibling.nodeValue=f({month:'long',year:'numeric'},w)
b.textContent=''
let e
for(let j=0;j<h+k;j++){if(j%7==0)e=b.insertRow()
if(j<h){e.insertCell();continue}
const d=c.cloneNode(1),a=d.firstChild,D=new Date(y,m,j-h+1),u=s(D)
a.textContent=f({day:'numeric'},D)
a.href='#'+u
a.ariaCurrent=u==i.value?'date':null
a.toggleAttribute('data-today',u==s(new Date))
a.ariaDisabled=u<i.min||i.max&&u>i.max?'true':null
e.append(d)}},
N=d=>e=>{e.preventDefault();w.setMonth(w.getMonth()+d,1);g()}
v.onclick=N(-1);x.onclick=N(1)
b.onclick=e=>{const a=e.target.closest('a');if(!a)return
e.preventDefault()
if(a.ariaDisabled)return
i.value=a.hash.slice(1);l()
for(const z of['input','change'])i.dispatchEvent(new Event(z,{bubbles:!0}))
p.hidePopover()}
b.onkeydown=e=>{const R=getComputedStyle(r).direction=='rtl'?-1:1,d={ArrowLeft:-R,ArrowRight:R,ArrowUp:-7,ArrowDown:7}[e.key]
if(!d)return
e.preventDefault()
const z=P(e.target.hash);z.setDate(z.getDate()+d)
if(z.getMonth()!=w.getMonth()){w=z;g()}
F(z)}
p.addEventListener('toggle',e=>{if(e.newState=='open'){w=i.value?P(i.value):new Date;g();F(w)}})
r.closest('form')?.addEventListener('reset',()=>setTimeout(l))
i.dataset.on='';i.tabIndex=-1;i.ariaHidden='true';i.onfocus=()=>t.focus();t.hidden=!1;l()
})
