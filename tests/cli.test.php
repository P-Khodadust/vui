<?php
function vui(string $cwd, string $args): array {
    global $root;
    $cmd = 'cd ' . escapeshellarg($cwd) . ' && php ' . escapeshellarg("$root/bin/vui") . " $args 2>&1";
    exec($cmd, $out, $code);
    return [implode("\n", $out), $code];
}
function tmpdir(): string { $d = sys_get_temp_dir() . '/vui-test-' . bin2hex(random_bytes(6)); mkdir($d); return $d; }
function rmtree(string $d): void {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($d, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $f) $f->isDir() ? rmdir($f) : unlink($f);
    rmdir($d);
}
function in_tmp(callable $f): void { $d = tmpdir(); try { $f($d); } finally { rmtree($d); } }

t('cli: add before init fails', fn() => in_tmp(function ($d) {
    [$out, $code] = vui($d, 'add button');
    eq($code, 1);
    ok(str_contains($out, 'init'));
}));
t('cli: pure init + add renders', fn() => in_tmp(function ($d) {
    [$out, $code] = vui($d, 'init');
    eq($code, 0);
    ok(str_contains($out, "require __DIR__ . '/ui/ui.php';") && str_contains($out, '@source') && str_contains($out, '@import'), $out);
    ok(is_file("$d/ui/ui.php") && is_file("$d/ui/theme.css"));
    eq(vui($d, 'add button card')[1], 0);
    ok(is_file("$d/ui/components/button.php") && is_file("$d/ui/components/card.php"));
    ok(!glob("$d/ui/components/*.blade.php"), 'no blade files in pure PHP');
    ok(!is_file("$d/ui/components/input.php"), 'only what was added');
    file_put_contents("$d/page.php", '<?php require __DIR__ . "/ui/ui.php"; echo card(raw(button("Hi")));');
    exec('php ' . escapeshellarg("$d/page.php"), $o);
    ok(str_contains(implode($o), 'data-slot="button"'), implode($o));
}));
t('cli: laravel layout', fn() => in_tmp(function ($d) {
    touch("$d/artisan");
    [$out] = vui($d, 'init');
    ok(str_contains($out, 'Laravel') && str_contains($out, 'resources/ui/ui.php'), $out);
    vui($d, 'add card');
    ok(is_file("$d/resources/ui/ui.php") && is_file("$d/resources/css/theme.css"));
    ok(is_file("$d/resources/ui/components/card.php"));
    ok(is_file("$d/resources/views/components/ui/card.blade.php"));
    ok(is_file("$d/resources/views/components/ui/card/header.blade.php"));
}));
t('cli: never overwrites without --force', fn() => in_tmp(function ($d) {
    vui($d, 'init');
    vui($d, 'add button');
    file_put_contents("$d/ui/components/button.php", "<?php // mine\n");
    [$out] = vui($d, 'add button');
    ok(str_contains($out, 'skip') && str_contains($out, '+ <?php // mine'), $out);
    eq(file_get_contents("$d/ui/components/button.php"), "<?php // mine\n");
    vui($d, 'add button --force');
    ok(str_contains(file_get_contents("$d/ui/components/button.php"), 'button_classes'));
}));
t('cli: diff', fn() => in_tmp(function ($d) {
    vui($d, 'init');
    vui($d, 'add button');
    [$out, $code] = vui($d, 'diff button');
    eq($code, 0);
    ok(str_contains($out, 'same'), $out);
    file_put_contents("$d/ui/components/button.php", "<?php // mine\n");
    [$out, $code] = vui($d, 'diff button');
    eq($code, 1);
    ok(str_contains($out, '+ <?php // mine'), $out);
}));
t('cli: add prints the CSS import for style items', fn() => in_tmp(function ($d) {
    vui($d, 'init');
    [$out] = vui($d, 'add sidebar');
    ok(str_contains($out, '@import "./ui/sidebar-theme.css";') && is_file("$d/ui/sidebar-theme.css"), $out);
}));
t('cli: list shows sizes', function () use ($root) {
    [$out, $code] = vui($root, 'list');
    eq($code, 0);
    ok(preg_match('/^button\s+\d+ B/m', $out), $out);
});
t('cli: unknown component fails', fn() => in_tmp(function ($d) {
    vui($d, 'init');
    [$out, $code] = vui($d, 'add nope');
    eq($code, 1);
    ok(str_contains($out, 'unknown component'), $out);
}));
t('cli: http registry refused', fn() => in_tmp(function ($d) {
    [$out, $code] = vui($d, 'init --registry=http://example.com/registry.json');
    eq($code, 1);
    ok(str_contains($out, 'https'), $out);
}));
t('cli: registry paths cannot escape the project', fn() => in_tmp(function ($d) {
    $r = "$d/reg";
    mkdir("$r/core", 0777, true);
    file_put_contents("$r/core/ui.php", '<?php');
    file_put_contents("$r/registry.json", json_encode(['items' => [['name' => 'core', 'files' => ['core/ui.php']], ['name' => 'theme', 'files' => ['../../evil.php']]]]));
    mkdir("$d/p");
    [$out, $code] = vui("$d/p", '--registry=' . escapeshellarg("$r/registry.json") . ' init');
    eq($code, 1);
    ok(str_contains($out, 'unsafe'), $out);
    ok(!is_file("$d/evil.php"));
}));
t('cli: dependencies resolve recursively, cycles terminate', fn() => in_tmp(function ($d) {
    $r = "$d/reg";
    foreach (['core', 'components/a', 'components/b'] as $s) mkdir("$r/$s", 0777, true);
    foreach (['core/ui.php', 'components/a/a.php', 'components/b/b.php'] as $s) file_put_contents("$r/$s", '<?php');
    file_put_contents("$r/registry.json", json_encode(['items' => [
        ['name' => 'core', 'files' => ['core/ui.php']], ['name' => 'theme', 'files' => []],
        ['name' => 'a', 'files' => ['components/a/a.php'], 'dependsOn' => ['b']],
        ['name' => 'b', 'files' => ['components/b/b.php'], 'dependsOn' => ['a']],
    ]]));
    mkdir("$d/p");
    vui("$d/p", "--registry=$r init");
    eq(vui("$d/p", "--registry=$r add a")[1], 0);
    ok(is_file("$d/p/ui/components/b.php"));
}));
