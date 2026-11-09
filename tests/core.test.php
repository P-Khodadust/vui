<?php
// cn(): [input, expected]
foreach ([
    'last wins' => ['p-2 p-4', 'p-4'],
    'separate axes' => ['px-2 py-2 px-4', 'py-2 px-4'],
    'p and px are separate groups' => ['p-2 px-4', 'p-2 px-4'],
    'keeps order, winner moves last' => ['p-2 flex p-4', 'flex p-4'],
    'logical padding' => ['ps-1 pe-1 ps-2', 'pe-1 ps-2'],
    'logical margin' => ['ms-1 me-1 me-2', 'ms-1 me-2'],
    'negative margin merges' => ['mx-2 -mx-1', '-mx-1'],
    'same variant merges' => ['hover:p-2 p-4 hover:p-6', 'p-4 hover:p-6'],
    'different variants kept' => ['md:h-1 lg:h-2 dark:h-3', 'md:h-1 lg:h-2 dark:h-3'],
    'stacked variants' => ['md:hover:w-1 md:hover:w-2 hover:w-3', 'md:hover:w-2 hover:w-3'],
    'rounded base' => ['rounded rounded-lg', 'rounded-lg'],
    'rounded-sm is base, not side s' => ['rounded-sm rounded-s-md rounded-md', 'rounded-s-md rounded-md'],
    'rounded and side are separate' => ['rounded-md rounded-t-lg', 'rounded-md rounded-t-lg'],
    'rounded same side' => ['rounded-t rounded-t-lg', 'rounded-t-lg'],
    'rounded logical sides' => ['rounded-s-md rounded-e-md rounded-e-lg', 'rounded-s-md rounded-e-lg'],
    'rounded corners never merge' => ['rounded-tl-lg rounded-md rounded-ss-sm', 'rounded-tl-lg rounded-md rounded-ss-sm'],
    'rounded arbitrary' => ['rounded-[4px] rounded-full', 'rounded-full'],
    'text never merges' => ['text-sm text-red-500', 'text-sm text-red-500'],
    'border never merges' => ['border border-2 border-red-500', 'border border-2 border-red-500'],
    'color not merged, ! suffix passes' => ['bg-red-500 bg-blue-500!', 'bg-red-500 bg-blue-500!'],
    'important is its own group' => ['p-2 p-4!', 'p-2 p-4!'],
    'important merges with important' => ['p-2! p-4!', 'p-4!'],
    'gap axes' => ['gap-2 gap-x-1 gap-y-2 gap-4', 'gap-x-1 gap-y-2 gap-4'],
    'gap-x last wins' => ['gap-x-1 gap-x-2', 'gap-x-2'],
    'size group' => ['size-4 size-6 h-1', 'size-6 h-1'],
    'arbitrary value' => ['h-[calc(100%-1px)] h-9', 'h-9'],
    'fraction' => ['w-1/2 w-full', 'w-full'],
    'arbitrary variant stays separate' => ["[&_svg:not([class*='size-'])]:size-4 size-6", "[&_svg:not([class*='size-'])]:size-4 size-6"],
    'arbitrary variants differ' => ['[&_a]:p-1 [&_b]:p-2', '[&_a]:p-1 [&_b]:p-2'],
    'same arbitrary variant merges' => ['[&_a]:p-1 [&_a]:p-2', '[&_a]:p-2'],
    'data variant' => ['data-[size=sm]:h-1 data-[size=lg]:h-2', 'data-[size=sm]:h-1 data-[size=lg]:h-2'],
    'near misses untouched' => ['pointer-events-none peer place-items-center prose min-w-0 w-0 max-h-1 h-1 whitespace-nowrap hidden mask-none m-1', 'pointer-events-none peer place-items-center prose min-w-0 w-0 max-h-1 h-1 whitespace-nowrap hidden mask-none m-1'],
    'colon in arbitrary value still merges' => ['h-[a:b] h-4', 'h-4'],
    'colon in arbitrary value with variant' => ['hover:p-[a:b] hover:p-1', 'hover:p-1'],
    'url value untouched' => ['bg-[url(http://x)] p-1', 'bg-[url(http://x)] p-1'],
    'exact duplicate' => ['flex flex', 'flex'],
    'whitespace' => [" a \n\t b ", 'a b'],
] as $name => [$in, $want]) t("cn: $name", fn() => eq(cn($in), $want));
t('cn: nulls and empties', fn() => eq(cn(null, '', 'a', null, 'b'), 'a b'));
t('cn: empty', fn() => eq(cn(), ''));
t('cn: merges across args', fn() => eq(cn('h-9 px-4', 'h-8'), 'px-4 h-8'));

t('attrs: true is bare', fn() => eq(attrs(['disabled' => true]), ' disabled'));
t('attrs: false and null skip', fn() => eq(attrs(['a' => false, 'b' => null]), ''));
t('attrs: zero and empty render', fn() => eq(attrs(['a' => 0, 'b' => '']), ' a="0" b=""'));
t('attrs: escapes double and single quotes', fn() => eq(attrs(['t' => '"\'<>&']), ' t="&quot;&#039;&lt;&gt;&amp;"'));
t('attrs: does not double-encode (Blade :bound values arrive escaped)', fn() => eq(attrs(['a' => 'O&#039;B &amp; &lt;b&gt;']), ' a="O&#039;B &amp; &lt;b&gt;"'));
t('attrs: bare ampersand is still escaped', fn() => eq(attrs(['a' => 'x=1&y=2']), ' a="x=1&amp;y=2"'));
t('attrs: an encoded quote cannot break out', fn() => ok(!str_contains(attrs(['a' => '&quot;><script>']), '<script')));
t('attrs: a name that sanitizes to nothing is dropped', fn() => eq(attrs(['<>' => 'x']), ''));
t('attrs: data and aria pass', fn() => eq(attrs(['data-x' => 1, 'aria-label' => 'y']), ' data-x="1" aria-label="y"'));
t('attrs: framework names pass', fn() => eq(attrs(['@click' => 'a', 'wire:model.live' => 'b', 'x-on:click' => 'c']), ' @click="a" wire:model.live="b" x-on:click="c"'));
t('attrs: hostile names are stripped', fn() => eq(attrs(['x onload=alert(1)><script>' => 'y']), ' xonloadalert1script="y"'));
t('attrs: raw is still escaped in values', fn() => eq(attrs(['a' => raw('<b>')]), ' a="&lt;b&gt;"'));

t('raw: stringable', fn() => ok(raw('x') instanceof Stringable));
t('raw: casts back', fn() => eq((string) raw('<b>'), '<b>'));
t('e: escapes quotes', fn() => eq(e('<a href="x">\''), '&lt;a href=&quot;x&quot;&gt;&#039;'));
t('e: invalid utf-8 is substituted, not dropped', fn() => ok(e("ok\xff") !== ''));
t('el: text is escaped', fn() => eq(el('b', null, '', '<i>'), '<b>&lt;i&gt;</b>'));
t('el: other Stringables are still escaped', fn() => eq(el('b', null, '', new class implements Stringable { function __toString(): string { return '<i>'; } }), '<b>&lt;i&gt;</b>'));
t('el: raw is not escaped', fn() => eq(el('b', null, '', raw('<i>')), '<b><i></b>'));
t('el: input and img are void', fn() => eq(el('input', null, '', null) . el('img', null, '', null), '<input><img>'));
t('el: null content still closes the tag', fn() => eq(el('div', null, '', null), '<div></div>'));
t('el: null class is fine', fn() => eq(el('b', null, null, 'x', null), '<b>x</b>'));
t('el: empty content still closes', fn() => eq(el('b', null, '', ''), '<b></b>'));
t('el: class arg and attrs class both merge', fn() => eq(el('b', 's', 'p-1', '', 'p-2', ['class' => 'p-3']), '<b data-slot="s" class="p-3"></b>'));
t('el: user attrs cannot replace data-slot', fn() => eq(el('b', 's', '', '', '', ['data-slot' => 'x']), '<b data-slot="s"></b>'));

// XSS: call every component function with hostile strings in every parameter.
$payload = '"><script>alert(1)</script>\'';
foreach (get_defined_functions()['user'] as $fn) {
    $r = new ReflectionFunction($fn);
    if (!str_contains($r->getFileName(), '/registry/components/')) continue;
    t("xss: {$r->getName()}", function () use ($r, $payload) {
        $args = [];
        foreach ($r->getParameters() as $p) {
            $d = $p->isDefaultValueAvailable() ? $p->getDefaultValue() : '';
            $args[] = match (true) {
                is_array($d) => ['title' => $payload, $payload => 'x'],
                is_bool($d) => false,
                is_int($d) => 1,
                default => $payload,
            };
        }
        $html = $r->invokeArgs($args);
        ok(!str_contains($html, '<script'), "unescaped <script> in: $html");
        ok(!preg_match('/"\s*><script|\'>/', $html), "attribute breakout in: $html");
    });
}
t('xss: raw content is the only way through', fn() => ok(str_contains(button(raw('<b>x</b>')), '<b>x</b>')));

// calendar: date logic that snapshots of one fixed month can't cover.
t('calendar: week_start moves the blanks', function () {
    eq(substr_count(calendar('2025-02'), '<td></td>'), 6); // 1 Feb 2025 is a Saturday
    eq(substr_count(calendar('2025-02', week_start: 6), '<td></td>'), 0);
    ok(str_contains(calendar('2025-02', week_start: 6), '>Sa</th>'));
});
t('calendar: month lengths', function () {
    eq(substr_count(calendar('2025-01', href: '?d={date}'), '<a'), 31);
    eq(substr_count(calendar('2024-02', href: '?d={date}'), '<a'), 29); // leap year
});
t('calendar: nav crosses years', function () {
    $h = calendar('2025-12', nav: '?m={month}');
    ok(str_contains($h, '?m=2025-11') && str_contains($h, '?m=2026-01'), $h);
    $h = calendar('2025-01', nav: '?m={month}');
    ok(str_contains($h, '?m=2024-12'), $h);
});
t('calendar: today is marked only in its own month', function () {
    ok(str_contains(calendar(date('Y-m')), ' bg-accent text-accent-foreground'));
    ok(!str_contains(calendar('2001-01'), ' bg-accent text-accent-foreground'));
});
t('calendar: no href renders text, no nav hides the arrows', function () {
    ok(!str_contains(calendar('2025-02'), '<a'));
});
t('calendar: a bad month falls back to the current one', fn() => ok(str_contains(calendar('nope'), date('F Y'))));

// Every component must stay well-formed when content is null, and never nest a <form>.
foreach (get_defined_functions()['user'] as $fn) {
    $r = new ReflectionFunction($fn);
    if (!str_contains($r->getFileName(), '/registry/components/')) continue;
    t("null content stays well-formed: {$r->getName()}", function () use ($r) {
        $args = [];
        foreach ($r->getParameters() as $p) {
            $nullable = !$p->hasType() || $p->getType()->allowsNull();
            $args[] = in_array($p->getName(), ['c', 'content']) && $nullable ? null : ($p->isDefaultValueAvailable() ? $p->getDefaultValue() : 'x');
        }
        preg_match_all('~<(/?)([a-z0-9]+)(?:\s[^<>]*?)?(/?)>~i', $r->invokeArgs($args), $m, PREG_SET_ORDER);
        $open = [];
        foreach ($m as [, $close, $tag, $self]) {
            if ($self || in_array(strtolower($tag), ['input', 'img'])) continue;
            if ($close) ok(array_pop($open) === $tag, "stray </$tag>"); else $open[] = $tag;
        }
        eq($open, []);
    });
}
t('dialog_close nests no form, so a dialog can live inside one', fn() => ok(!str_contains(dialog_close('x'), '<form')));
t('select: class goes on the wrapper', function () {
    foreach ([select('', class: 'w-full'), select('', attrs: ['class' => 'w-full'])] as $h) ok(preg_match('~select-wrapper" class="[^"]*\bw-full"~', $h) === 1 && !str_contains($h, 'w-fit'), $h);
});
t('calendar: odd week_start values are safe', fn() => ok(is_string(calendar('2025-02', week_start: '') . calendar('2025-02', week_start: 8))));
