<?php
// Optional: renders every Blade example in a real Laravel app and compares it with the PHP snapshot.
// Usage: VUI_LARAVEL=/path/to/fresh/laravel php tests/blade.php   (skips if unset)
// It installs vui into that app with --force, so point it at a throwaway project.
$app = getenv('VUI_LARAVEL');
if (!$app || !is_file("$app/artisan")) { echo "skip: set VUI_LARAVEL to a Laravel app\n"; exit(0); }
$root = dirname(__DIR__);
$examples = require "$root/docs/examples.php";
$reg = json_decode(file_get_contents("$root/registry/registry.json"), true);
$names = array_diff(array_column($reg['items'], 'name'), ['core', 'theme']);
chdir($app);
exec('php ' . escapeshellarg("$root/bin/vui") . ' init --force', $o, $c1);
exec('php ' . escapeshellarg("$root/bin/vui") . ' add --force ' . implode(' ', array_map('escapeshellarg', $names)), $o, $c2);
if ($c1 || $c2) { echo implode("\n", $o), "\ninstall failed\n"; exit(1); }

require "$app/vendor/autoload.php";
require_once "$app/resources/ui/ui.php"; // for apps that have not added it to composer autoload yet
$laravel = require "$app/bootstrap/app.php";
$laravel->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Only whitespace between tags and at the ends may differ.
$norm = fn(string $h) => trim(preg_replace(['/\s+/', '/>\s/', '/\s</'], [' ', '>', '<'], $h));
$fail = 0;
foreach ($examples as $name => $ex) {
    $want = $norm(file_get_contents("$root/tests/snapshots/$name.html"));
    try { $got = $norm(Illuminate\Support\Facades\Blade::render($ex['blade'])); }
    catch (Throwable $e) { $got = 'EXCEPTION: ' . $e->getMessage(); }
    if ($got === $want) { echo "ok   $name\n"; continue; }
    $fail++;
    $i = strspn($got ^ $want, "\0");
    echo "FAIL $name\n  got:  ...", substr($got, max(0, $i - 80), 240), "\n  want: ...", substr($want, max(0, $i - 80), 240), "\n";
}

// Wrapper contract checks that the shared examples can't express.
$render = fn(string $t, array $d = []) => $norm(Illuminate\Support\Facades\Blade::render($t, $d));
$check = function (string $name, bool $v, string $got = '') use (&$fail) { echo ($v ? 'ok   ' : 'FAIL '), $name, $v ? '' : "\n  $got", "\n"; $fail += !$v; };
$h = $render('<x-ui.button class="h-20" data-x="1" wire:click="go">Hi</x-ui.button>');
$check('class merges through cn(), not appended', !str_contains($h, 'h-9') && str_contains($h, 'h-20'), $h);
$check('other attributes pass through', str_contains($h, 'data-x="1"') && str_contains($h, 'wire:click="go"'), $h);
$h = $render('<x-ui.button>{{ $v }}</x-ui.button>', ['v' => '<script>alert(1)</script>']);
$check('{{ }} in a slot stays escaped', !str_contains($h, '<script') && str_contains($h, '&lt;script'), $h);
$h = $render('<x-ui.button>{!! $v !!}</x-ui.button>', ['v' => '<b>x</b>']);
$check('slot HTML stays HTML', str_contains($h, '<b>x</b>'), $h);
$h = $render('<x-ui.button :data-x="$v">a</x-ui.button>', ['v' => '"><script>']);
$check('attribute values are escaped', !str_contains($h, '<script'), $h);
$h = $render('<x-ui.input :value="$v" />', ['v' => "O'Brien & <b>"]);
$check('bound attributes are escaped exactly once', str_contains($h, 'value="O&#039;Brien &amp; &lt;b&gt;"'), $h);
$h = $render('<x-ui.button :class="button_classes(\'outline\')">a</x-ui.button>');
$check('bound class is not double-escaped', !str_contains($h, '&amp;amp;') && str_contains($h, '[&amp;_svg]'), $h);
$h = $render('<x-ui.input :disabled="false" :required="true" />');
$check('boolean attributes', !str_contains($h, 'disabled=') && !preg_match('/\sdisabled(\s|>)/', $h) && preg_match('/\srequired(\s|>)/', $h) === 1, $h);
$h = $render('<x-ui.form.field name="n" label="<b>L</b>" />');
$check('form_field escapes the label', str_contains($h, '&lt;b&gt;L'), $h);
$e = new Illuminate\Support\ViewErrorBag;
$e->put('default', new Illuminate\Support\MessageBag(['n' => ['Required']]));
Illuminate\Support\Facades\View::share('errors', $e);
$h = $render('<x-ui.form.field name="n" label="L" />');
$check('form field reads $errors', str_contains($h, 'Required') && str_contains($h, 'aria-invalid="true"'), $h);
$check('Laravel e() is used, not redefined', (new ReflectionFunction('e'))->getFileName() !== realpath("$app/resources/ui/ui.php"));

echo $fail ? "$fail failed\n" : "blade: all equal\n";
exit($fail ? 1 : 0);
