{
  const h = document.documentElement, $ = id => document.getElementById(id), put = (k, v) => { try { localStorage[k] = v } catch (e) {} }
  const dir = $('dir-toggle'), dark = $('theme-toggle')
  const sync = () => { dir?.setAttribute('aria-pressed', h.dir == 'rtl'); dark.setAttribute('aria-pressed', h.classList.contains('dark')) }
  dir?.addEventListener('click', () => { h.dir = h.dir == 'rtl' ? 'ltr' : 'rtl'; h.lang = h.dir == 'rtl' ? 'fa' : 'en'; put('dir', h.dir); sync() })
  dark.addEventListener('click', () => { put('dark', h.classList.toggle('dark') ? '1' : '0'); sync() })
  sync()

  addEventListener('click', async e => {
    const b = e.target.closest('[data-copy]')
    if (!b) return
    const label = b.textContent
    try { await navigator.clipboard.writeText($(b.dataset.copy).textContent); b.textContent = 'Copied' } catch (err) { b.textContent = 'Copy failed' }
    setTimeout(() => b.textContent = label, 1500)
  })

  addEventListener('input', e => {
    const f = e.target.closest('[data-filter]')
    if (!f) return
    const q = f.value.trim().toLowerCase(), root = document.querySelector(f.dataset.filter)
    root.querySelectorAll('[data-name]').forEach(x => x.hidden = !x.dataset.name.includes(q))
    let any = false
    root.querySelectorAll('[data-group]').forEach(g => any = !(g.hidden = !g.querySelector('[data-name]:not([hidden])')) || any)
    const none = root.querySelector('[data-none]')
    if (none) none.hidden = any
  })

  // Pages reload on every click, so put the current link back in view.
  const nav = $('nav'), cur = nav?.querySelector('[aria-current]')
  if (cur) nav.scrollTop = cur.offsetTop - nav.clientHeight / 2

  // Light moving across the glazed wall.
  const wall = document.querySelector('.facade')
  if (wall && matchMedia('(hover:hover)').matches) wall.addEventListener('pointermove', e => {
    const r = wall.getBoundingClientRect()
    wall.style.setProperty('--mx', e.clientX - r.left + 'px')
    wall.style.setProperty('--my', e.clientY - r.top + 'px')
  })

  const data = $('reg'), boxes = [...document.querySelectorAll('[data-pick]')]
  if (data) {
    const reg = JSON.parse(data.textContent)
    const tally = () => {
      const on = boxes.filter(b => b.checked).map(b => b.value), all = new Set(['core', 'theme'])
      const add = n => { if (!all.has(n)) { all.add(n); reg[n].d.forEach(add) } }
      on.forEach(add)
      $('pick-sum').textContent = [...all].reduce((s, n) => s + reg[n].s, 0).toLocaleString('en') + ' bytes'
      $('pick-n').textContent = on.length + (on.length == 1 ? ' component' : ' components')
      $('pick-cmd').textContent = 'php vui init\n' + (on.length ? 'php vui add ' + on.join(' ') : '# Pick at least one component')
    }
    addEventListener('change', e => e.target.matches('[data-pick]') && tally())
    tally()
  }
}