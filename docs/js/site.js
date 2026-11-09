{
  const h = document.documentElement, put = (k, v) => { try { localStorage[k] = v } catch (e) {} }
  const dir = document.getElementById('dir-toggle'), dark = document.getElementById('theme-toggle')
  const sync = () => { if (dir) dir.textContent = h.dir == 'rtl' ? 'LTR' : 'RTL'; dark.textContent = h.classList.contains('dark') ? 'Light' : 'Dark' }
  dir?.addEventListener('click', () => { h.dir = h.dir == 'rtl' ? 'ltr' : 'rtl'; h.lang = h.dir == 'rtl' ? 'fa' : 'en'; put('dir', h.dir); sync() })
  dark.addEventListener('click', () => { put('dark', h.classList.toggle('dark') ? '1' : '0'); sync() })
  sync()
  addEventListener('click', async e => {
    const b = e.target.closest('[data-copy]')
    if (!b) return
    const label = b.textContent
    try { await navigator.clipboard.writeText(document.getElementById(b.dataset.copy).textContent); b.textContent = 'Copied' } catch (err) { b.textContent = 'Copy failed' }
    setTimeout(() => b.textContent = label, 1500)
  })
}