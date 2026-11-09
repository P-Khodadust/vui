{
  const h = document.documentElement, $ = id => document.getElementById(id), put = (k, v) => { try { localStorage[k] = v } catch (e) {} }
  const dark = $('theme-toggle'), sync = () => dark.setAttribute('aria-pressed', h.classList.contains('dark'))
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

  // This site has no server to answer the preview links, so they must not jump to the top or reload.
  addEventListener('click', e => {
    const h = e.target.closest('main a[href]')?.getAttribute('href')
    if (h && /^[#?]/.test(h) && !$(h.slice(1))) e.preventDefault()
  })

  // Docs only: the component's days and arrows are links, here the month is redrawn in place.
  document.querySelectorAll('main [data-slot=calendar]:not([data-slot=date-picker] *)').forEach(cal => {
    const body = cal.querySelector('tbody'), [prev, next] = cal.querySelectorAll('div > a'), day = /\d{4}-\d\d-\d\d/
    if (!next) return
    const first = body.querySelector('a'), cell = first.parentNode.cloneNode(true), title = prev.nextSibling, iso = d => d.toLocaleDateString('sv')
    const month = new Date(first.href.match(day)[0] + 'T00:00'), start = (month.getDay() - body.rows[0].querySelectorAll('td:empty').length + 7) % 7
    const name = new Intl.DateTimeFormat((cal.closest('[lang]')?.lang || 'en') + '-u-ca-gregory', { month: 'long', year: 'numeric' })
    let picked = cal.querySelector('[aria-current]')?.href.match(day)[0], row
    const draw = () => {
      const y = month.getFullYear(), m = month.getMonth(), pad = (month.getDay() - start + 7) % 7, n = new Date(y, m + 1, 0).getDate()
      title.nodeValue = name.format(month)
      body.textContent = ''
      for (let i = 0; i < pad + n; i++) {
        if (i % 7 == 0) row = body.insertRow()
        if (i < pad) { row.insertCell(); continue }
        const td = cell.cloneNode(true), a = td.firstChild, id = iso(new Date(y, m, i - pad + 1))
        a.textContent = i - pad + 1
        a.setAttribute('href', '#' + id)
        if (id == picked) a.setAttribute('aria-current', 'date'); else a.removeAttribute('aria-current')
        a.toggleAttribute('data-today', id == iso(new Date))
        row.append(td)
      }
    }
    cal.addEventListener('click', e => {
      const a = e.target.closest('a')
      if (!a) return
      if (a == prev || a == next) { month.setMonth(month.getMonth() + (a == prev ? -1 : 1)); draw(); return }
      picked = a.href.match(day)[0]
      body.querySelector('[aria-current]')?.removeAttribute('aria-current')
      a.setAttribute('aria-current', 'date')
    })
  })

  // Docs only: page numbers are links in the component, here the highlight moves in place.
  document.querySelectorAll('main [data-slot=pagination]').forEach(nav => {
    const links = [...nav.querySelectorAll('a')], pages = links.slice(1, -1)
    nav.addEventListener('click', e => {
      const a = e.target.closest('a'), now = nav.querySelector('[aria-current]')
      if (!a || !now) return
      const to = a == links[0] ? pages[pages.indexOf(now) - 1] : a == links.at(-1) ? pages[pages.indexOf(now) + 1] : a
      if (!to || to == now) return
      ;[to.className, now.className] = [now.className, to.className]
      now.removeAttribute('aria-current')
      to.setAttribute('aria-current', 'page')
    })
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