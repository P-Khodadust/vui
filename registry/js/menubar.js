// vui menubar (optional): menubar roles, Left/Right move between menus and follow dir. Load with dropdown-menu.js.
document.querySelectorAll('[data-slot=menubar]').forEach(b => {
  const ts = [...b.querySelectorAll('[data-slot=dropdown-menu-trigger]')], menu = t => document.getElementById(t.getAttribute('popovertarget'))
  b.role = 'menubar'
  ts.forEach(t => t.role = 'menuitem')
  b.addEventListener('pointerover', e => {
    const t = e.target.closest('[data-slot=dropdown-menu-trigger]'), open = b.querySelector(':popover-open')
    if (t && open && menu(t) != open) { open.hidePopover(); menu(t).showPopover() }
  })
  b.addEventListener('keydown', e => {
    const d = { ArrowRight: 1, ArrowLeft: -1 }[e.key], i = ts.findIndex(t => t == e.target || menu(t).contains(e.target))
    if (!d || i < 0) return
    const n = ts[(i + d * (getComputedStyle(b).direction == 'rtl' ? -1 : 1) + ts.length) % ts.length], open = b.querySelector(':popover-open')
    e.preventDefault()
    open?.hidePopover()
    n.focus()
    if (open) menu(n).showPopover()
  })
})
