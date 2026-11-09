<?php
$reg = json_decode(file_get_contents("$root/registry/registry.json"), true);
$items = array_column($reg['items'], null, 'name');

t('registry: files and sizes match disk', function () use ($items, $root) {
    foreach ($items as $n => $i) {
        $s = 0;
        foreach ($i['files'] as $f) { ok(is_file("$root/registry/$f"), "$n lists missing $f"); $s += filesize("$root/registry/$f"); }
        eq($i['size'] ?? null, $s);
        foreach ($i['dependsOn'] as $d) ok(isset($items[$d]), "$n depends on unknown $d");
    }
});
t('registry: no orphan files', function () use ($items, $root) {
    $listed = array_merge(...array_column($items, 'files'));
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root/registry", FilesystemIterator::SKIP_DOTS)) as $f) {
        $rel = substr($f, strlen("$root/registry/"));
        if ($rel != 'registry.json') ok(in_array($rel, $listed), "orphan $rel");
    }
});
t('registry: every component has an example', function () use ($items, $examples) {
    foreach ($items as $n => $i) if ($i['type'] == 'component') ok(isset($examples[$n]), "no example for $n");
});
t('size budget passes', function () use ($root) {
    exec('php ' . escapeshellarg("$root/bin/size"), $out, $code);
    eq($code, 0);
});

foreach (glob("$root/registry/components/*/*.blade.php") as $f) {
    $rel = substr($f, strlen("$root/registry/components/"));
    t("blade: $rel is a thin wrapper", function () use ($f) {
        $s = file_get_contents($f);
        ok(preg_match('/\{!! (\w+)\(/', $s, $m), 'must call a PHP function');
        ok(function_exists($m[1]), "unknown function {$m[1]}");
        ok(str_contains($s, '->getAttributes()'), 'must pass attributes through');
        ok(!preg_match('/class\s*=|merge\(/', $s), 'no copied classes, no Blade merge');
    });
}

// RTL: physical direction utilities are banned from shipped code.
foreach (array_merge(glob("$root/registry/components/*/*.php"), glob("$root/registry/js/*.js")) as $f) {
    $rel = substr($f, strlen("$root/registry/"));
    t("rtl: $rel uses logical utilities", function () use ($f) {
        $bad = '/(?<![\w-])-?(ml|mr|pl|pr|left|right|border-l|border-r|rounded-l|rounded-r|rounded-tl|rounded-tr|rounded-bl|rounded-br|text-left|text-right|float-left|float-right|scroll-ml|scroll-mr)(-|\b)/';
        preg_match_all('/"[^"]*"/', file_get_contents($f), $strs);
        foreach ($strs[0] as $s) ok(!preg_match($bad, $s), "banned physical utility in $s");
    });
}

// dependsOn must cover every function a component calls from another component.
t('registry: dependsOn is complete', function () use ($items, $root) {
    $owner = [];
    foreach (get_defined_functions()['user'] as $fn) if (preg_match('~/components/([^/]+)/~', (string) (new ReflectionFunction($fn))->getFileName(), $m)) $owner[$fn] = $m[1];
    $closure = function (string $n, array &$seen) use (&$closure, $items) { foreach ($items[$n]['dependsOn'] as $d) if (!isset($seen[$d])) { $seen[$d] = 1; $closure($d, $seen); } };
    foreach ($items as $n => $i) {
        if ($i['type'] == 'core' || $i['type'] == 'style') continue;
        $seen = [$n => 1];
        $closure($n, $seen);
        foreach (token_get_all(file_get_contents("$root/registry/components/$n/$n.php")) as $tok)
            if (is_array($tok) && $tok[0] === T_STRING && isset($owner[strtolower($tok[1])]))
                ok(isset($seen[$owner[strtolower($tok[1])]]), "$n calls {$tok[1]}() but does not depend on {$owner[strtolower($tok[1])]}");
    }
});

t('theme: every shadcn variable is defined and mapped', function () use ($root) {
    $css = file_get_contents("$root/registry/css/theme.css");
    preg_match('/:root \{(.*?)\}/s', $css, $light);
    preg_match('/\.dark \{(.*?)\}/s', $css, $dark);
    foreach (explode(' ', 'background foreground card card-foreground popover popover-foreground primary primary-foreground secondary secondary-foreground muted muted-foreground accent accent-foreground destructive border input ring') as $v) {
        ok(str_contains($light[1], "--$v:"), "--$v missing in :root");
        ok(str_contains($dark[1], "--$v:"), "--$v missing in .dark");
        ok(str_contains($css, "--color-$v: var(--$v);"), "--$v not mapped in @theme inline");
    }
    ok(str_contains($light[1], '--radius:'), '--radius missing');
    ok(str_contains($css, '@apply border-border outline-ring/50') && str_contains($css, '@apply bg-background text-foreground'), 'base layer');
});
t('no v3 directives or banned dependencies in shipped code', function () use ($root) {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root/registry", FilesystemIterator::SKIP_DOTS)) as $f) {
        $s = file_get_contents($f);
        ok(!str_contains($s, '@tailwind '), "v3 directive in $f");
        ok(!preg_match('/\b(Alpine|x-data|lit-html|tailwind-merge|import .* from|require\([\'"])/', $s), "dependency in $f");
    }
});

t('motion: every native overlay and details depends on it', function () use ($items, $root) {
    $closure = function (string $n, array &$seen) use (&$closure, $items) { foreach ($items[$n]['dependsOn'] as $d) if (!isset($seen[$d])) { $seen[$d] = 1; $closure($d, $seen); } };
    foreach ($items as $n => $i) {
        if ($i['type'] != 'component' || !preg_match("/el\('(dialog|details)'|'popover'=>true/", file_get_contents("$root/registry/components/$n/$n.php"))) continue;
        $seen = [];
        $closure($n, $seen);
        ok(isset($seen['motion']), "$n renders an overlay but does not depend on motion");
    }
});
t('motion: reduced-motion switches transitions off', function () use ($root) {
    ok(preg_match('/prefers-reduced-motion: reduce.*transition-duration: 0s !important/s', file_get_contents("$root/registry/css/theme.css")) === 1);
});

t('cursor: clickable things show the hand', function () use ($root) {
    ok(preg_match('/:is\(button, summary.*?cursor: pointer/s', file_get_contents("$root/registry/css/theme.css")) === 1, 'theme rule missing');
    foreach (glob("$root/registry/components/*/*.php") as $f) ok(!str_contains(file_get_contents($f), 'cursor-default'), "$f forces the arrow cursor");
});
t('highlight: mouse focus must not leave a second item lit', function () use ($root) {
    foreach (glob("$root/registry/components/*/*.php") as $f) ok(!preg_match('/\bfocus:/', file_get_contents($f)), "$f styles on focus:, use focus-visible:");
});
