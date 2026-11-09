<?php
// Plain test runner: php tests/run.php [--update]  (--update rewrites snapshots)
$update = in_array('--update', $argv);
$root = dirname(__DIR__);
require "$root/registry/core/ui.php";
foreach (glob("$root/registry/components/*/*.php") as $f) if (!str_ends_with($f, '.blade.php')) require_once $f;
$examples = require "$root/docs/examples.php";
$pass = $fail = 0;
function t(string $name, callable $f): void {
    global $pass, $fail;
    try { $f(); $pass++; } catch (Throwable $e) { $fail++; echo "FAIL $name\n  {$e->getMessage()}\n"; }
}
function eq($got, $want): void { if ($got !== $want) throw new Exception("got:  " . var_export($got, true) . "\n  want: " . var_export($want, true)); }
function ok($v, string $why = 'expected true'): void { if (!$v) throw new Exception($why); }
function render(string $php): string { ob_start(); eval($php); return ob_get_clean(); }
foreach (glob(__DIR__ . '/*.test.php') as $f) require $f;
echo "$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
