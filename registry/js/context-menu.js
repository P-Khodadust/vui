// vui context-menu: opens at the pointer, or the trigger for the keyboard menu key. Stays on screen, follows dir.
addEventListener('contextmenu', e => {
  const t = e.target.closest?.('[data-slot=context-menu-trigger]'), m = t && document.getElementById(t.dataset.for)
  if (!m) return
  e.preventDefault()
  const r = t.getBoundingClientRect()
  let x = e.clientX || r.left, y = e.clientY || r.top
  const open = () => {
    m.style.cssText = 'position:fixed;inset:auto;margin:0'
    if (!m.matches(':popover-open')) m.showPopover()
    if (getComputedStyle(m).direction == 'rtl') x -= m.offsetWidth
    m.style.left = Math.max(8, Math.min(x, innerWidth - m.offsetWidth - 8)) + 'px'
    m.style.top = Math.max(8, Math.min(y, innerHeight - m.offsetHeight - 8)) + 'px'
  }
  // Linux, macOS and touch fire this while pressed: the release would light-dismiss a popover opened now.
  if (!e.buttons && e.pointerType != 'touch') return open()
  const after = () => { removeEventListener('pointerup', after); removeEventListener('pointercancel', after); setTimeout(open) }
  addEventListener('pointerup', after)
  addEventListener('pointercancel', after)
})
// iOS Safari never fires contextmenu, so a long press stands in for it.
let hold
addEventListener('touchstart', e => {
  const t = e.target.closest?.('[data-slot=context-menu-trigger]'), { clientX, clientY } = e.touches[0]
  if (t) hold = setTimeout(() => t.dispatchEvent(new MouseEvent('contextmenu', { bubbles: true, buttons: 1, clientX, clientY })), 600)
}, { passive: true })
for (const n of ['touchmove', 'touchend', 'touchcancel']) addEventListener(n, () => clearTimeout(hold), { passive: true })
