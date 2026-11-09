// vui tabs: click, arrow keys, Home/End. Arrow direction follows dir=rtl.
{
  const panel = t => document.getElementById(t.getAttribute('aria-controls'))
  const pick = t => {
    t.closest('[role=tablist]').querySelectorAll('[role=tab]').forEach(o => {
      const on = o == t, p = panel(o)
      o.setAttribute('aria-selected', on)
      o.dataset.state = on ? 'active' : 'inactive'
      o.tabIndex = on ? 0 : -1
      if (p) { p.hidden = !on; p.toggleAttribute('data-on', on) }
    })
    t.focus()
  }
  addEventListener('click', e => { const t = e.target.closest('[role=tab]'); if (t && panel(t)) { e.preventDefault(); pick(t) } })
  addEventListener('keydown', e => {
    const t = e.target.closest?.('[role=tab]')
    if (!t) return
    const l = [...t.closest('[role=tablist]').querySelectorAll('[role=tab]:not(:disabled)')], i = l.indexOf(t), d = getComputedStyle(t).direction == 'rtl' ? -1 : 1
    const n = { ArrowRight: i + d, ArrowLeft: i - d, Home: 0, End: l.length - 1 }[e.key]
    if (n == null) return
    e.preventDefault()
    l[(n + l.length) % l.length].click()
  })
}
