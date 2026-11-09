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
  // Linux and macOS fire this on mouse down: the mouse up would light-dismiss a popover opened now.
  e.buttons ? addEventListener('pointerup', () => setTimeout(open), { once: true }) : open()
})
