// vui dropdown-menu (optional): menu roles, arrow keys, Home/End. Without it Tab, Esc and click still work.
document.querySelectorAll('[data-slot=dropdown-menu-content]').forEach(m => {
  const t = document.querySelector(`[data-slot=dropdown-menu-trigger][popovertarget="${m.id}"]`), items = () => [...m.querySelectorAll('[data-slot=dropdown-menu-item]:not(:disabled)')]
  m.role = 'menu'
  items().forEach(i => i.role = 'menuitem')
  t?.setAttribute('aria-haspopup', 'menu')
  t?.addEventListener('keydown', e => { if (e.key == 'ArrowDown') { e.preventDefault(); m.showPopover() } })
  m.addEventListener('toggle', e => { if (e.newState == 'open') items()[0]?.focus() })
  m.addEventListener('click', e => { if (e.target.closest('[data-slot=dropdown-menu-item]')) m.hidePopover() })
  m.addEventListener('keydown', e => {
    const l = items(), i = l.indexOf(document.activeElement), n = { ArrowDown: i + 1, ArrowUp: i - 1, Home: 0, End: l.length - 1 }[e.key]
    if (e.key == 'Tab') m.hidePopover()
    if (n == null) return
    e.preventDefault()
    l[(n + l.length) % l.length]?.focus()
  })
})
