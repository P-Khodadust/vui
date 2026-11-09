// vui carousel (optional): shows the arrows, moves one slide, disables them at the ends. Follows dir.
document.querySelectorAll('[data-slot=carousel]').forEach(c => {
  const s = c.querySelector('[data-slot=carousel-content]'), items = [...s.children], at = e => e.offsetLeft - items[0].offsetLeft
  const prev = c.querySelector('[data-slot=carousel-previous]'), next = c.querySelector('[data-slot=carousel-next]')
  let i = 0, t
  const sync = () => {
    const x = Math.abs(s.scrollLeft)
    i = items.findIndex(e => Math.abs(at(e)) >= x - 1)
    if (prev) prev.disabled = x < 1
    if (next) next.disabled = x + s.clientWidth >= s.scrollWidth - 1
  }
  // Moves from a target index, so quick clicks add up instead of restarting mid-scroll.
  const go = d => { i = Math.max(0, Math.min(items.length - 1, i + d)); s.scrollTo({ left: at(items[i]) }) }
  // sync first: disabling an already visible arrow would fade it on every page load
  sync()
  for (const [b, d] of [[prev, -1], [next, 1]]) if (b) { b.hidden = false; b.onclick = () => go(d) }
  s.addEventListener('scroll', () => { clearTimeout(t); t = setTimeout(sync, 120) })
})
