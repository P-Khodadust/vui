// vui command: filter, Up/Down, Enter, listbox roles.
document.querySelectorAll('[data-slot=command]').forEach((c, n) => {
  const input = c.querySelector('[data-slot=command-input]'), list = c.querySelector('[data-slot=command-list]'), empty = c.querySelector('[data-slot=command-empty]')
  const items = [...c.querySelectorAll('[data-slot=command-item]')], shown = () => items.filter(i => !i.hidden && !i.disabled)
  const pick = i => {
    items.forEach(o => { o.dataset.selected = o == i; o.setAttribute('aria-selected', o == i) })
    i ? input.setAttribute('aria-activedescendant', i.id) : input.removeAttribute('aria-activedescendant')
  }
  list.id ||= `cmd${n}`
  list.role = 'listbox'
  input.role = 'combobox'
  input.setAttribute('aria-expanded', true)
  input.setAttribute('aria-controls', list.id)
  input.setAttribute('aria-autocomplete', 'list')
  items.forEach((i, k) => { i.role = 'option'; i.id ||= `cmd${n}-${k}`; i.tabIndex = -1 })
  input.addEventListener('input', () => {
    const q = input.value.toLowerCase()
    items.forEach(i => i.hidden = !i.textContent.toLowerCase().includes(q))
    c.querySelectorAll('[data-slot=command-group]').forEach(g => g.hidden = !g.querySelector('[data-slot=command-item]:not([hidden])'))
    c.querySelectorAll('[data-slot=separator]').forEach(s => s.hidden = !!q)
    if (empty) empty.hidden = shown().length > 0
    pick(shown()[0])
  })
  // not pointerover: keyboard scroll must not steal it
  list.addEventListener('pointermove', e => { const i = e.target.closest('[data-slot=command-item]'); if (shown().includes(i)) pick(i) })
  list.onmousedown = e => e.preventDefault()
  input.addEventListener('keydown', e => {
    const l = shown(), k = l.findIndex(i => i.dataset.selected == 'true'), to = { ArrowDown: k + 1, ArrowUp: k - 1 }[e.key]
    if (e.key == 'Enter') { e.preventDefault(); l[k]?.click() }
    if (to == null) return
    e.preventDefault()
    const t = l[(to + l.length) % l.length]
    pick(t)
    t?.scrollIntoView({ block: 'nearest' })
  })
  pick(shown()[0])
})
