<?php
// Builds the static docs site into docs/. Run: php docs/build.php
// CSS needs Tailwind once: npm i --no-save @tailwindcss/cli@4 tailwindcss@4  (without it the committed site.css is kept)
$root = dirname(__DIR__);
require "$root/registry/core/ui.php";
foreach (glob("$root/registry/components/*/*.php") as $f) if (!str_ends_with($f, '.blade.php')) require_once $f;
$examples = require __DIR__ . '/examples.php';
ksort($examples);
$items = array_column(json_decode(file_get_contents("$root/registry/registry.json"), true)['items'], null, 'name');

function render(string $php): string { ob_start(); eval($php); return ob_get_clean(); }

// Components an example calls, so its install line is complete.
$owner = [];
foreach (get_defined_functions()['user'] as $fn) if (preg_match('~/components/([^/]+)/~', (string) (new ReflectionFunction($fn))->getFileName(), $m)) $owner[$fn] = $m[1];
function used(string $php, string $self): array {
    global $owner;
    preg_match_all('/\b(\w+)\(/', $php, $m);
    return array_values(array_unique(array_diff(array_intersect_key($owner, array_flip(array_map('strtolower', $m[1]))), [$self])));
}
function kb(int $n): string { return number_format($n) . ' B'; }

// Code is always LTR, even on an RTL page.
function code(string $src, string $id, string $label): string {
    return '<div class="grid gap-2"><div class="flex items-center justify-between gap-2"><h3 class="text-sm font-medium">' . e($label) . '</h3>'
        . button('Copy', 'ghost', 'sm', attrs: ['data-copy' => $id])
        . '</div><pre dir="ltr" class="overflow-x-auto rounded-lg border bg-muted/50 p-4 text-start font-mono text-xs leading-relaxed"><code id="' . $id . '">' . e($src) . '</code></pre></div>';
}

function page(string $title, string $slug, string $body, bool $rtl = false): void {
    global $examples;
    $links = '';
    $navlink = fn($s, $t) => '<a href="' . $s . '.html" class="' . cn('rounded-md px-3 py-1.5 text-sm whitespace-nowrap text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground', $s == $slug ? 'bg-accent font-medium text-accent-foreground' : '') . '"' . ($s == $slug ? ' aria-current="page"' : '') . '>' . e($t) . '</a>';
    foreach ($examples as $n => $ex) $links .= $navlink($n, $ex['title']);
    $head = $rtl ? "document.documentElement.classList.toggle('dark',s.dark=='1'||(!s.dark&&matchMedia('(prefers-color-scheme:dark)').matches))"
        : "const d=s.dir||'ltr',h=document.documentElement;h.dir=d;h.lang=d=='rtl'?'fa':'en';h.classList.toggle('dark',s.dark=='1'||(!s.dark&&matchMedia('(prefers-color-scheme:dark)').matches))";
    $html = '<!doctype html><html lang="' . ($rtl ? 'fa' : 'en') . '" dir="' . ($rtl ? 'rtl' : 'ltr') . '"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
        . '<title>' . e($title) . ' · vui</title><meta name="description" content="Tiny shadcn/ui-style components for PHP and Laravel."><link rel="stylesheet" href="site.css">'
        . "<script>try{const s=localStorage;$head}catch(e){}</script></head><body class=\"antialiased\">"
        . '<header class="sticky top-0 z-40 flex h-14 items-center gap-2 border-b bg-background/95 px-4 backdrop-blur"><a href="index.html" class="me-auto font-semibold">vui</a><a href="https://github.com/P-Khodadust/vui" class="' . e(button_classes('ghost', 'sm')) . '">GitHub</a>'
        . ($rtl ? '' : button('RTL', 'ghost', 'sm', attrs: ['id' => 'dir-toggle', 'aria-label' => 'Toggle text direction']))
        . button('Dark', 'ghost', 'sm', attrs: ['id' => 'theme-toggle', 'aria-label' => 'Toggle dark mode'])
        . '</header><div class="mx-auto flex max-w-6xl flex-col md:flex-row"><nav aria-label="Components" dir="ltr" class="flex gap-1 overflow-x-auto border-b p-2 md:sticky md:top-14 md:h-[calc(100vh-3.5rem)] md:w-52 md:shrink-0 md:flex-col md:overflow-y-auto md:border-e md:border-b-0">'
        . $navlink('index', 'Introduction') . $navlink('rtl', 'فارسی (RTL)') . $links
        . '</nav><main class="grid min-w-0 flex-1 grid-cols-[minmax(0,1fr)] content-start gap-8 p-4 md:p-8">' . $body . '</main></div>'
        . implode('', array_map(fn($f) => '<script src="js/' . basename($f) . '" defer></script>', [...glob(dirname(__DIR__) . '/registry/js/*.js'), 'site.js'])) . '</body></html>';
    file_put_contents(__DIR__ . "/$slug.html", $html);
}

foreach ($examples as $n => $ex) {
    $sizes = ['PHP' => 0, 'Blade' => 0, 'JS' => 0];
    $sources = '';
    $first = '';
    foreach ($items[$n]['files'] as $i => $f) {
        $s = file_get_contents("$root/registry/$f");
        $sizes[str_ends_with($f, '.js') ? 'JS' : (str_ends_with($f, '.blade.php') ? 'Blade' : 'PHP')] += strlen($s);
        if ($f == "components/$n/$n.php") $first = "src-$i";
        $sources .= code($s, "src-$i", $f);
    }
    $meta = implode(' · ', array_map(fn($k, $v) => "$k " . kb($v), array_keys($sizes), $sizes));
    $deps = $items[$n]['dependsOn'] ? ' Depends on: ' . implode(', ', $items[$n]['dependsOn']) . '.' : '';
    $rows = '';
    foreach ($ex['props'] as [$name, $type, $def, $desc])
        $rows .= table_row(raw(table_cell(raw('<code dir="ltr" class="font-mono text-xs">' . e($name) . '</code>')) . table_cell(raw('<code dir="ltr" class="font-mono text-xs">' . e($type) . '</code>'), 'whitespace-normal') . table_cell(raw('<code dir="ltr" class="font-mono text-xs">' . e($def) . '</code>')) . table_cell($desc, 'whitespace-normal')));
    page($ex['title'], $n,
        '<div class="grid gap-2"><h1 class="text-3xl font-semibold tracking-tight rtl:tracking-normal">' . e($ex['title']) . '</h1><p class="text-muted-foreground">' . e($items[$n]['description']) . '.' . e($deps) . '</p>'
        . '<div class="flex flex-wrap items-center gap-2 text-sm text-muted-foreground"><span>' . e($meta) . '</span>' . ($first ? button('Copy source', 'outline', 'sm', attrs: ['data-copy' => $first]) : '') . '</div></div>'
        . '<section aria-label="Preview" class="flex min-h-40 flex-wrap items-center justify-center gap-3 rounded-lg border p-6 md:p-10">' . render($ex['php']) . '</section>'
        . code('php vui add ' . implode(' ', [$n, ...used($ex['php'], $n)]), 'cli', 'Install')
        . code($ex['php'], 'php', 'PHP')
        . code($ex['blade'], 'blade', 'Blade')
        . '<section class="grid gap-2"><h2 class="text-sm font-medium">Props</h2>' . table(raw(table_header(raw(table_row(raw(table_head('Name') . table_head('Type') . table_head('Default') . table_head('Notes'))))) . table_body(raw($rows)))) . '</section>'
        . (isset($ex['js']) ? alert(raw(alert_title('JavaScript') . alert_description($ex['js']))) : '')
        . '<section class="grid gap-4"><h2 class="text-sm font-medium">Source</h2>' . $sources . '</section>');
}

// Index
$rows = '';
foreach ($examples as $n => $ex) $rows .= table_row(raw(table_cell(raw('<a class="font-medium underline-offset-4 hover:underline" href="' . $n . '.html">' . e($ex['title']) . '</a>')) . table_cell(kb($items[$n]['size']), 'text-end') . table_cell($items[$n]['description'], 'whitespace-normal')));
page('Introduction', 'index',
    '<div class="grid gap-3"><h1 class="text-3xl font-semibold tracking-tight rtl:tracking-normal">vui</h1><p class="max-w-2xl text-lg text-muted-foreground">Tiny shadcn/ui-style components for pure PHP and Laravel. No runtime package: a small CLI copies source files into your project, and you carry only what you added.</p>'
    . '<div class="flex flex-wrap gap-2">' . badge('Core ' . kb($items['core']['size']), 'secondary') . badge('0 dependencies', 'secondary') . badge('0 KB JS by default', 'secondary') . badge('RTL first', 'secondary') . badge('Tailwind v4', 'secondary') . '</div></div>'
    . code("php vui init\nphp vui add button card dialog", 'install', 'Install')
    . code("<?php\nrequire __DIR__ . '/ui/ui.php';\n\necho card(raw(\n    card_header(raw(card_title('Hello') . card_description('From plain PHP.'))) .\n    card_content(raw(button('Save')))\n));", 'p', 'Pure PHP')
    . code("<x-ui.card>\n    <x-ui.card.header>\n        <x-ui.card.title>Hello</x-ui.card.title>\n        <x-ui.card.description>From Blade.</x-ui.card.description>\n    </x-ui.card.header>\n    <x-ui.card.content><x-ui.button>Save</x-ui.button></x-ui.card.content>\n</x-ui.card>", 'b', 'Laravel Blade')
    . '<section class="grid gap-2"><h2 class="text-sm font-medium">Components</h2>' . table(raw(table_header(raw(table_row(raw(table_head('Name') . table_head('Size', 'text-end!') . table_head('What it is'))))) . table_body(raw($rows)))) . '</section>');

// Persian page: every string is real Persian, forced RTL.
page('فارسی', 'rtl',
    '<div class="grid gap-2"><h1 class="text-3xl font-semibold">نمونه فارسی</h1><p class="text-muted-foreground">همه اجزا با جهت راست به چپ و متن فارسی.</p></div>'
    . breadcrumb(raw(breadcrumb_list(raw(breadcrumb_item(raw(breadcrumb_link('خانه', 'index.html'))) . breadcrumb_separator() . breadcrumb_item(raw(breadcrumb_link('حساب کاربری', '#'))) . breadcrumb_separator() . breadcrumb_item(raw(breadcrumb_page('تنظیمات')))))))
    . alert(raw(alert_title('توجه!') . alert_description('تغییرات شما پس از ذخیره اعمال می‌شود.')))
    . '<div class="grid gap-6 lg:grid-cols-2">'
    . card(raw(
        card_header(raw(card_title('ورود به حساب') . card_description('ایمیل و رمز عبور خود را وارد کنید.') . card_action(raw(badge('جدید', 'secondary'))))) .
        card_content(raw('<div class="grid gap-4">' . form_field('email', 'ایمیل', 'نادرست', 'ایمیل معتبر وارد کنید.', 'ایمیل شما محرمانه می‌ماند.', 'email') . form_field('bio', 'درباره شما', type: 'textarea') . '<div class="flex items-center gap-3">' . checkbox(attrs: ['id' => 'fa-terms', 'checked' => true]) . label('قوانین را می‌پذیرم', attrs: ['for' => 'fa-terms']) . '</div><div class="flex items-center gap-3">' . switch_(attrs: ['id' => 'fa-air', 'checked' => true]) . label('حالت هواپیما', attrs: ['for' => 'fa-air']) . '</div>' . select(raw(select_option('تهران', 'thr', true) . select_option('تبریز', 'tbz')), attrs: ['aria-label' => 'شهر']) . '</div>')) .
        card_footer(raw(button('ورود')), 'gap-2')
    ))
    . '<div class="grid content-start gap-6">'
    . tabs(raw(tabs_list(raw(tabs_trigger('حساب', 'fa-a', true) . tabs_trigger('رمز عبور', 'fa-b') . tabs_trigger('پرداخت', 'fa-c'))) . tabs_content('اطلاعات حساب خود را ویرایش کنید.', 'fa-a', true) . tabs_content('رمز عبور خود را تغییر دهید.', 'fa-b') . tabs_content('طرح خود را مدیریت کنید.', 'fa-c')))
    . '<div class="flex flex-wrap items-center gap-3">'
    . dialog_trigger('باز کردن پنجره', 'fa-d', button_classes('outline'))
    . dialog_content(raw(dialog_header(raw(dialog_title('ویرایش نمایه', 'fa-d') . dialog_description('تغییرات را اینجا انجام دهید.', 'fa-d'))) . dialog_footer(raw(dialog_close('انصراف', button_classes('outline')) . button('ذخیره')))), 'fa-d', 'بستن')
    . dropdown_menu_trigger('منو', 'fa-m', button_classes('outline'))
    . dropdown_menu_content(raw(dropdown_menu_label('حساب من') . dropdown_menu_separator() . dropdown_menu_item(raw('نمایه' . dropdown_menu_shortcut('⌘P')), 'fa-m') . dropdown_menu_item('خروج', 'fa-m', 'destructive')), 'fa-m', 'w-48')
    . popover_trigger('ابعاد', 'fa-p', button_classes('outline')) . popover_content('ابعاد لایه را تنظیم کنید.', 'fa-p')
    . tooltip(raw(tooltip_trigger('راهنما', 'fa-t', button_classes('outline')) . tooltip_content('افزودن به کتابخانه', 'fa-t')))
    . '</div>'
    . accordion(raw(accordion_item(raw(accordion_trigger('آیا این کتابخانه سبک است؟') . accordion_content('بله، فقط چیزی که اضافه کنید همراه شماست.')), 'fa', true) . accordion_item(raw(accordion_trigger('به جاوااسکریپت نیاز دارد؟') . accordion_content('در بیشتر موارد خیر.')), 'fa')))
    . '<div class="flex flex-wrap items-center gap-3">' . avatar(raw(avatar_fallback('پخ'))) . skeleton('h-4 w-40') . separator('vertical', class: 'h-6') . badge('پرداخت شده') . badge('لغو شده', 'destructive') . '</div>'
    . '</div></div>'
    . table(raw(table_caption('فهرست فاکتورهای اخیر') . table_header(raw(table_row(raw(table_head('فاکتور') . table_head('وضعیت') . table_head('مبلغ', 'text-end!'))))) . table_body(raw(table_row(raw(table_cell('۱۰۰۱') . table_cell('پرداخت شده') . table_cell('۲۵۰٬۰۰۰ تومان', 'text-end'))) . table_row(raw(table_cell('۱۰۰۲') . table_cell('در انتظار') . table_cell('۱۵۰٬۰۰۰ تومان', 'text-end')))))))
    . pagination(raw(pagination_content(raw(pagination_item(raw(pagination_previous('#', 'قبلی'))) . pagination_item(raw(pagination_link('۱', '#'))) . pagination_item(raw(pagination_link('۲', '#', true))) . pagination_item(raw(pagination_ellipsis('صفحات بیشتر'))) . pagination_item(raw(pagination_next('#', 'بعدی')))))))
    . '<div class="grid gap-6 lg:grid-cols-2">'
    . calendar('2025-02', '2025-02-14', '#{date}', '#{month}', 6, ['title' => 'فوریه ۲۰۲۵', 'weekdays' => 'ی د س چ پ ج ش', 'prev' => 'ماه قبل', 'next' => 'ماه بعد'], 'self-start border')
    . '<div class="grid content-start gap-4">'
    . radio_group(raw('<div class="flex items-center gap-3">' . radio_group_item('fa-plan', 'a', true, attrs: ['id' => 'fa-r1']) . label('رایگان', attrs: ['for' => 'fa-r1']) . '</div><div class="flex items-center gap-3">' . radio_group_item('fa-plan', 'b', attrs: ['id' => 'fa-r2']) . label('حرفه‌ای', attrs: ['for' => 'fa-r2']) . '</div>'), attrs: ['aria-label' => 'طرح'])
    . toggle_group(raw(toggle_group_item('راست', 'fa-align', 'r', true, variant: 'outline') . toggle_group_item('وسط', 'fa-align', 'c', variant: 'outline') . toggle_group_item('چپ', 'fa-align', 'l', variant: 'outline')), attrs: ['aria-label' => 'چینش'])
    . input_otp(6, attrs: ['aria-label' => 'کد یک‌بار مصرف'])
    . progress(60, attrs: ['aria-label' => 'پیشرفت'])
    . slider(40, attrs: ['aria-label' => 'صدا'])
    . '<div class="flex flex-wrap items-center gap-3">'
    . sheet_trigger('پنل کناری', 'fa-s', button_classes('outline'))
    . sheet_content(raw(sheet_header(raw(sheet_title('ویرایش نمایه', 'fa-s') . sheet_description('تغییرات را اینجا انجام دهید.', 'fa-s')))), 'fa-s', 'end', 'بستن')
    . drawer_trigger('کشو', 'fa-dr', button_classes('outline'))
    . drawer_content(raw(drawer_header(raw(drawer_title('هدف روزانه', 'fa-dr') . drawer_description('هدف فعالیت روزانه را تنظیم کنید.', 'fa-dr')))), 'fa-dr')
    . alert_dialog_trigger('حذف حساب', 'fa-ad', button_classes('destructive'))
    . alert_dialog_content(raw(alert_dialog_header(raw(alert_dialog_title('آیا مطمئن هستید؟', 'fa-ad') . alert_dialog_description('این کار قابل بازگشت نیست.', 'fa-ad'))) . alert_dialog_footer(raw(alert_dialog_cancel('انصراف') . alert_dialog_action('ادامه')))), 'fa-ad')
    . '</div>'
    . command(raw(command_input(attrs: ['placeholder' => 'جستجو', 'aria-label' => 'جستجو']) . command_list(raw(command_empty('چیزی پیدا نشد.') . command_group(raw(command_item('تقویم') . command_item('تنظیمات')), 'پیشنهادها')))), 'rounded-lg border')
    . menubar(raw(menubar_trigger('پرونده', 'fa-mb1') . menubar_content(raw(menubar_item('زبانه جدید', 'fa-mb1') . menubar_item('چاپ', 'fa-mb1')), 'fa-mb1') . menubar_trigger('ویرایش', 'fa-mb2') . menubar_content(raw(menubar_item('واگرد', 'fa-mb2')), 'fa-mb2')), 'w-fit')
    . '</div></div>'
    . toaster(close: 'بستن')
    . button('نمایش اعلان', 'secondary', attrs: ['onclick' => "toast('رویداد با موفقیت ثبت شد')", 'class' => 'w-fit']),
    true);

@mkdir(__DIR__ . '/js');
foreach (glob("$root/registry/js/*.js") as $f) copy($f, __DIR__ . '/js/' . basename($f));
file_put_contents(__DIR__ . '/js/site.js', <<<'JS'
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
JS);
touch(__DIR__ . '/.nojekyll');

$tw = "$root/node_modules/.bin/tailwindcss";
if (is_file($tw)) { passthru(escapeshellarg($tw) . ' -i ' . escapeshellarg(__DIR__ . '/site.src.css') . ' -o ' . escapeshellarg(__DIR__ . '/site.css') . ' --minify 2>&1', $code); if ($code) exit($code); }
else echo "tailwind not installed, keeping existing docs/site.css\n";
echo 'docs: ' . count(glob(__DIR__ . '/*.html')) . " pages\n";
