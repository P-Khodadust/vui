// vui tooltip (optional): Esc dismisses (WCAG 1.4.13). CSS alone can't do this.
addEventListener('keydown', e => { if (e.key == 'Escape') document.querySelectorAll('[role=tooltip]').forEach(t => { if (getComputedStyle(t).scale != '0') t.hidden = true }) })
for (const n of ['pointerout', 'focusout']) addEventListener(n, e => { const t = e.target.closest?.('[data-slot=tooltip]')?.querySelector('[role=tooltip]'); if (t) t.hidden = false })
