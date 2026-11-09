// vui toast: window.toast(message, variant, ms). Also auto-dismisses server-rendered toasts.
{
  const arm = (n, ms = 4000) => {
    let t
    const go = () => t = setTimeout(() => { n.dataset.out = 1; setTimeout(() => n.remove(), 200) }, ms)
    n.onpointerenter = () => clearTimeout(t)
    n.onpointerleave = go
    go()
  }
  window.toast = (m, v = 'default', ms) => {
    const box = document.querySelector('[data-slot=toaster]'), n = box.querySelector('template').content.firstChild.cloneNode(true)
    n.querySelector('[data-slot=toast-title]').textContent = m
    n.dataset.variant = v
    if (v == 'destructive') n.role = 'alert'
    box.append(n)
    arm(n, ms)
    return n
  }
  document.querySelectorAll('[data-slot=toast]').forEach(n => arm(n))
}
