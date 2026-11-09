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

// Sidebar and gallery order. Every example must appear here (checked just below).
$groups = [
    'Forms' => ['button', 'button-group', 'calendar', 'checkbox', 'combobox', 'date-picker', 'field', 'form', 'input', 'input-group', 'input-otp', 'label', 'radio-group', 'select', 'slider', 'switch', 'textarea', 'toggle', 'toggle-group'],
    'Overlays' => ['alert-dialog', 'command', 'context-menu', 'dialog', 'drawer', 'dropdown-menu', 'hover-card', 'menubar', 'popover', 'sheet', 'tooltip'],
    'Navigation' => ['breadcrumb', 'navigation-menu', 'pagination', 'sidebar', 'tabs'],
    'Layout and content' => ['accordion', 'aspect-ratio', 'avatar', 'badge', 'card', 'carousel', 'collapsible', 'empty', 'item', 'kbd', 'scroll-area', 'separator', 'table', 'typography'],
    'Feedback' => ['alert', 'progress', 'skeleton', 'spinner', 'toast'],
];
if ($lost = array_diff(array_keys($examples), array_merge(...array_values($groups)))) exit('docs: no group for ' . implode(', ', $lost) . "\n");

// Highlighting escapes everything first. Copy buttons read textContent, so the spans never reach the clipboard.
function hl_php(string $s): string {
    $bare = !str_contains($s, '<?php');
    $toks = token_get_all($bare ? "<?php $s" : $s);
    $out = '';
    foreach ($toks as $i => $t) {
        if ($bare && !$i) continue;
        [$id, $txt] = is_array($t) ? [$t[0], $t[1]] : [0, $t];
        $next = $toks[$i + 1] ?? '';
        $cls = match (true) {
            in_array($id, [T_COMMENT, T_DOC_COMMENT]) => 'c',
            in_array($id, [T_CONSTANT_ENCAPSED_STRING, T_ENCAPSED_AND_WHITESPACE]) => 's',
            $id == T_VARIABLE, $id == T_STRING && $next === ':' => 'v',
            $id == T_STRING && $next === '(' => 'f',
            in_array($id, [T_OPEN_TAG, T_ECHO, T_REQUIRE, T_REQUIRE_ONCE, T_FUNCTION, T_RETURN, T_FOREACH, T_AS, T_IF, T_ELSE, T_NEW, T_FN, T_ARRAY, T_DIR]) => 'k',
            default => '',
        };
        $out .= $cls ? "<b class=\"$cls\">" . e($txt) . '</b>' : e($txt);
    }
    return $out;
}
function hl_blade(string $s): string {
    $h = preg_replace('~(&lt;/?)([\w.:-]+)~', '$1<b class="k">$2</b>', e($s));
    return preg_replace('~([:@\w.-]+)=(&quot;.*?&quot;)~', '<b class="v">$1</b>=<b class="s">$2</b>', $h);
}

// Code is always LTR, even on an RTL page.
function code(string $src, string $id, string $label, string $lang = ''): string {
    $body = match ($lang) { 'php' => hl_php($src), 'blade' => hl_blade($src), default => e($src) };
    return '<div class="code dark lapis"><div class="code-bar"><span>' . e($label) . '</span>' . button('Copy', 'ghost', 'sm', attrs: ['data-copy' => $id])
        . '</div><pre dir="ltr" tabindex="0"><code id="' . $id . '">' . $body . '</code></pre></div>';
}

function mark(): string {
    return '<svg class="mark" viewBox="-13 -60 284 173" role="img" aria-label="vui"><g fill="none" stroke="currentColor" stroke-width="26" stroke-linecap="round" stroke-linejoin="round"><path d="M0 0 42 100 84 0"/><path d="M128 0v58a42 42 0 0 0 84 0V0"/><path d="M258 0v100"/></g><circle cx="258" cy="-44" r="16" fill="#F4B13E"/></svg>';
}
const STAR = 'M40 0 51.72 11.72H68.28V28.28L80 40 68.28 51.72V68.28H51.72L40 80 28.28 68.28H11.72V51.72L0 40 11.72 28.28V11.72H28.28Z';

function page(string $title, string $slug, string $body, array $o = []): void {
    global $examples, $groups;
    $rtl = $o['rtl'] ?? false;
    $home = $slug == 'index';
    $link = fn($s, $t, $x = '') => '<a href="' . $s . '.html" data-name="' . e(strtolower($t)) . '"' . ($s == $slug ? ' aria-current="page"' : '') . "$x>" . e($t) . '</a>';
    $nav = '<nav id="nav" popover aria-label="Components"><div class="nav-filter">' . input(attrs: ['type' => 'search', 'placeholder' => 'Filter', 'aria-label' => 'Filter components', 'data-filter' => '#nav']) . '</div>'
        . '<div data-group>' . $link('index', 'Introduction') . $link('components', 'All components') . $link('rtl', 'فارسی', ' lang="fa"') . '</div>';
    foreach ($groups as $g => $names) $nav .= '<div data-group><p>' . e($g) . '</p>' . implode('', array_map(fn($n) => $link($n, $examples[$n]['title']), $names)) . '</div>';
    $nav .= '</nav>';
    $svg = fn($inner, $x = '') => '<svg class="size-[18px]' . $x . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
    $head = $rtl ? "document.documentElement.classList.toggle('dark',s.dark=='1'||(!s.dark&&matchMedia('(prefers-color-scheme:dark)').matches))"
        : "const d=s.dir||'ltr',h=document.documentElement;h.dir=d;h.lang=d=='rtl'?'fa':'en';h.classList.toggle('dark',s.dark=='1'||(!s.dark&&matchMedia('(prefers-color-scheme:dark)').matches))";
    $top = '<header class="top' . ($home ? ' top-home dark lapis' : '') . '"><a class="brand" href="index.html">' . mark() . '</a>'
        . '<a class="top-link" href="components.html">Components</a><a class="top-link" href="index.html#install">Install</a><span class="flex-1"></span>'
        . ($home ? '' : '<button class="' . e(button_classes('outline', 'sm')) . ' md:hidden" popovertarget="nav">Menu</button>')
        . ($rtl ? '' : button(raw($svg('<path d="M12 3v18M8 8l-5 4 5 4zM16 8l5 4-5 4z"/>')), 'ghost', 'icon', attrs: ['id' => 'dir-toggle', 'aria-label' => 'Toggle text direction', 'title' => 'Flip text direction']))
        . button(raw($svg('<path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"/>', ' dark:hidden') . $svg('<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>', ' hidden dark:block')), 'ghost', 'icon', attrs: ['id' => 'theme-toggle', 'aria-label' => 'Toggle dark mode', 'title' => 'Toggle dark mode'])
        . '<a class="' . e(button_classes('ghost', 'icon')) . '" href="https://github.com/P-Khodadust/vui" aria-label="vui on GitHub" title="GitHub"><svg class="size-[18px]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .5a11.5 11.5 0 0 0-3.64 22.41c.58.1.79-.25.79-.56v-2c-3.2.7-3.88-1.37-3.88-1.37-.53-1.33-1.28-1.69-1.28-1.69-1.05-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.7 1.26 3.36.96.1-.75.4-1.26.73-1.55-2.55-.29-5.24-1.28-5.24-5.68 0-1.26.45-2.28 1.19-3.09-.12-.29-.52-1.46.11-3.05 0 0 .97-.31 3.17 1.18a11 11 0 0 1 5.78 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.76.11 3.05.74.81 1.19 1.83 1.19 3.09 0 4.41-2.69 5.38-5.25 5.67.41.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.56A11.5 11.5 0 0 0 12 .5"/></svg></a></header>';
    $main = $home ? "<main>$body</main>" : "<div class=\"shell\">$nav<main class=\"doc\">$body</main></div>";
    $js = ($o['js'] ?? true) ? glob(dirname(__DIR__) . '/registry/js/*.js') : [];
    $html = '<!doctype html><html lang="' . ($rtl ? 'fa' : 'en') . '" dir="' . ($rtl ? 'rtl' : 'ltr') . '"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
        . '<title>' . e($home ? 'vui: tiny components for PHP and Laravel' : "$title - vui") . '</title><meta name="description" content="Tiny shadcn/ui-style components for PHP and Laravel, with first-class RTL."><link rel="icon" href="icon.svg"><link rel="stylesheet" href="site.css">'
        . ($home ? '<link rel="preload" as="font" type="font/woff2" crossorigin href="fonts/lalezar-latin.woff2"><link rel="preload" as="font" type="font/woff2" crossorigin href="fonts/lalezar-fa.woff2">' : '')
        . "<script>try{const s=localStorage;$head}catch(e){}</script></head><body class=\"antialiased\">$top$main"
        . '<footer class="foot">' . mark() . '<p>MIT licensed. Styled after <a href="https://ui.shadcn.com">shadcn/ui</a>.</p><a href="https://github.com/P-Khodadust/vui">Source on GitHub</a></footer>'
        . implode('', array_map(fn($f) => '<script src="js/' . basename($f) . '" defer></script>', [...$js, 'site.js'])) . '</body></html>';
    file_put_contents(__DIR__ . "/$slug.html", $html);
}

foreach ($examples as $n => $ex) {
    $sizes = ['PHP' => 0, 'Blade' => 0, 'JS' => 0];
    $sources = '';
    $first = '';
    foreach ($items[$n]['files'] as $i => $f) {
        $s = file_get_contents("$root/registry/$f");
        $blade = str_ends_with($f, '.blade.php');
        $sizes[str_ends_with($f, '.js') ? 'JS' : ($blade ? 'Blade' : 'PHP')] += strlen($s);
        if ($f == "components/$n/$n.php") $first = "src-$i";
        $sources .= code($s, "src-$i", $f, $blade ? 'blade' : (str_ends_with($f, '.php') ? 'php' : ''));
    }
    $facts = implode('', array_map(fn($k, $v) => "<div><dt>$k</dt><dd>" . kb($v) . '</dd></div>', array_keys($sizes), $sizes));
    $needs = array_map(fn($d) => isset($examples[$d]) ? '<a href="' . $d . '.html">' . e($examples[$d]['title']) . '</a>' : e($d), $items[$n]['dependsOn']);
    $rows = '';
    foreach ($ex['props'] as [$name, $type, $def, $desc])
        $rows .= table_row(raw(table_cell(raw('<code dir="ltr" class="font-mono text-xs">' . e($name) . '</code>'), 'whitespace-normal') . table_cell(raw('<code dir="ltr" class="font-mono text-xs">' . e($type) . '</code>'), 'whitespace-normal') . table_cell(raw('<code dir="ltr" class="font-mono text-xs">' . e($def) . '</code>'), 'whitespace-normal') . table_cell($desc, 'whitespace-normal')));
    page($ex['title'], $n,
        '<header class="head"><h1 class="display">' . e($ex['title']) . '</h1><p class="lede">' . e($items[$n]['description']) . '.</p>'
        . '<div class="facts"><dl>' . $facts . '</dl>' . ($first ? button('Copy source', 'outline', 'sm', attrs: ['data-copy' => $first]) : '') . '</div>'
        . ($needs ? '<p class="needs">Needs ' . implode(', ', $needs) . '.</p>' : '') . '</header>'
        . '<section aria-label="Preview" class="stage flex min-h-56 flex-wrap items-center justify-center gap-3 p-6 md:p-12">' . render($ex['php']) . '</section>'
        . '<div class="slab">' . code('php vui add ' . implode(' ', [$n, ...used($ex['php'], $n)]), 'cli', 'Install') . code($ex['php'], 'php', 'PHP', 'php') . code($ex['blade'], 'blade', 'Blade', 'blade') . '</div>'
        . '<section class="grid gap-3"><h2 class="sub">Props</h2>' . str_replace('<div data-slot="table-container"', '<div tabindex="0" data-slot="table-container"', table(raw(table_header(raw(table_row(raw(table_head('Name') . table_head('Type') . table_head('Default') . table_head('Notes'))))) . table_body(raw($rows))))) . '</section>'
        . (isset($ex['js']) ? alert(raw(alert_title('JavaScript') . alert_description($ex['js']))) : '')
        . '<details class="src"><summary><h2 class="sub">Source</h2></summary><div class="slab">' . $sources . '</div></details>');
}

// All components: real output, frozen open. No ids and no component scripts, since these tiles only show the surface.
function frozen(string $h): string {
    $h = preg_replace('/\s(id|for|name|closedby|popovertarget|popovertargetaction|command|commandfor|aria-(controls|labelledby|describedby|owns|expanded|haspopup|label))="[^"]*"/', '', $h);
    $h = preg_replace(['~<dialog\b~', '~</dialog>~', '~\spopover(="[^"]*")?(?=[\s>])~'], ['<div data-open', '</div>', ' data-open'], $h);
    $h = preg_replace(['~<(main|nav|aside)\b~', '~</(main|nav|aside)>~'], ['<div', '</div>'], $h);
    return preg_replace_callback('~ data-slot="(tooltip|hover-card)-content" class="([^"]*)"~', fn($m) => ' data-open class="' . implode(' ', array_filter(explode(' ', $m[2]), fn($c) => !preg_match('~^(invisible|absolute|scale-0|opacity-0|-translate-x-1/2|rtl:translate-x-1/2|start-|bottom-full|top-full|m[bt]-\d)~', $c))) . '"', $h);
}
$gallery = '<header class="head"><h1 class="display">Components</h1><p class="lede">Each page shows the component running, the PHP and Blade that draw it, its props and its size in bytes.</p>'
    . input('max-w-sm', ['type' => 'search', 'placeholder' => 'Filter components', 'aria-label' => 'Filter components', 'data-filter' => '#all']) . '</header><div id="all" class="grid gap-12">';
foreach ($groups as $g => $names) {
    $gallery .= '<section data-group class="grid gap-4"><h2 class="sub">' . e($g) . '</h2><div class="mosaic mosaic-even">';
    foreach ($names as $n) {
        $mini = frozen(render($examples[$n]['php']));
        $gallery .= '<article class="tile tile-mini" data-name="' . e(strtolower($examples[$n]['title'])) . '"><div class="mini" inert aria-hidden="true"><div>' . $mini . '</div></div>'
            . '<h3><a href="' . $n . '.html">' . e($examples[$n]['title']) . '</a></h3><p>' . e($items[$n]['description']) . '</p></article>';
    }
    $gallery .= '</div></section>';
}
$gallery .= '<p data-none hidden class="lede">No component matches that filter.</p></div>';
page('Components', 'components', $gallery, ['js' => false]);

// Home. One function draws both halves of the façade; only the words and the direction differ.
$invoice = fn(array $t, string $id) => card(raw(
    card_header(raw(card_title($t['title']) . card_description($t['desc']) . card_action(raw(badge($t['badge'], 'secondary'))))) .
    card_content(raw('<div class="grid gap-4">' . form_field("$id-customer", $t['customer'], $t['name'])
        . '<div class="grid gap-2">' . label($t['amount'], attrs: ['for' => "$id-amount"]) . input_group(raw(input_group_input(attrs: ['id' => "$id-amount", 'value' => $t['sum'], 'inputmode' => 'decimal']) . input_group_addon($t['unit'], 'inline-end'))) . '</div>'
        . '<div class="flex items-center justify-between gap-3">' . label($t['copy'], attrs: ['for' => "$id-copy"]) . switch_(attrs: ['id' => "$id-copy", 'checked' => true]) . '</div></div>')) .
    card_footer(raw(button($t['send'], class: 'flex-1', attrs: ['onclick' => "toast('{$t['sent']}')"]) . button($t['save'], 'outline')), 'gap-2')
));
$side = fn(string $lang, string $dir, array $t) => '<div class="side" dir="' . $dir . '" lang="' . $lang . '"><div class="pitch dark lapis">'
    . ($lang == 'en' ? "<h1 class=\"display\">{$t['h']}</h1>" : "<p class=\"display\">{$t['h']}</p>")
    . "<p class=\"pitch-sub\">{$t['p']}</p><div class=\"flex flex-wrap gap-3\"><a class=\"" . e(button_classes('default', 'lg')) . "\" href=\"components.html\">{$t['browse']}</a><a class=\"" . e(button_classes('outline', 'lg')) . "\" href=\"#install\">{$t['install']}</a></div></div>"
    . "<div class=\"plate glaze\"><code class=\"tag\" dir=\"ltr\">dir=\"$dir\"</code>" . $invoice($t, "h-$lang") . '</div></div>';
$facade = '<section class="facade"><div class="wall wall-base" aria-hidden="true"></div><div class="wall wall-light" aria-hidden="true"></div><div class="facade-in" dir="ltr">'
    . $side('en', 'ltr', ['h' => 'Tiny components for PHP and Laravel', 'p' => 'A small CLI copies the source into your project. There is no runtime package, and you carry only what you add.', 'browse' => 'Browse components', 'install' => 'Install', 'title' => 'New invoice', 'desc' => 'Send it now or keep a draft.', 'badge' => 'Draft', 'customer' => 'Customer', 'name' => 'Sara Ahmadi', 'amount' => 'Amount', 'sum' => '1,250.00', 'unit' => 'USD', 'copy' => 'Email a copy to the customer', 'send' => 'Send invoice', 'save' => 'Save draft', 'sent' => 'Invoice sent'])
    . '<div class="axis" aria-hidden="true"><svg viewBox="0 0 80 80"><path d="' . STAR . '"/></svg></div>'
    . $side('fa', 'rtl', ['h' => 'کامپوننت‌های کوچک برای PHP و لاراول', 'p' => 'یک ابزار خط فرمان کوچک، سورس را در پروژه‌ات کپی می‌کند. هیچ پکیجی در زمان اجرا لازم نیست و فقط همان چیزی را که اضافه کنی همراهت داری.', 'browse' => 'مشاهده کامپوننت‌ها', 'install' => 'نصب', 'title' => 'فاکتور جدید', 'desc' => 'همین حالا بفرست یا پیش‌نویس نگه دار.', 'badge' => 'پیش‌نویس', 'customer' => 'مشتری', 'name' => 'سارا احمدی', 'amount' => 'مبلغ', 'sum' => '۱۲۵٬۰۰۰٬۰۰۰', 'unit' => 'تومان', 'copy' => 'یک نسخه برای مشتری ایمیل شود', 'send' => 'ارسال فاکتور', 'save' => 'ذخیره پیش‌نویس', 'sent' => 'فاکتور ارسال شد'])
    . '</div><p class="facade-note">The two sides are one PHP function, called twice. Only the words and <code dir="ltr">dir</code> change.</p></section>';

$tile = fn(string $slug, string $title, string $html, string $class = '') => '<div class="tile ' . $class . '"><div class="tile-body">' . $html . '</div><a class="tile-cap" href="' . $slug . '.html">' . e($title) . '</a></div>';
$row = fn(string $id, string $text, bool $on = false) => '<div class="flex items-center justify-between gap-4">' . label($text, attrs: ['for' => $id]) . switch_(attrs: ['id' => $id, 'checked' => $on]) . '</div>';
$mosaic = '<div class="mosaic">'
    . $tile('calendar', 'Calendar', calendar('2026-11', '2026-11-09', '#{date}', '#{month}', class: 'mx-auto bg-transparent'), 'dark lapis lg:col-span-4 lg:row-span-2 md:col-span-6')
    . $tile('table', 'Table', table(raw(table_header(raw(table_row(raw(table_head('Invoice') . table_head('Status') . table_head('Amount', 'text-end!'))))) . table_body(raw(
        table_row(raw(table_cell('INV-1001') . table_cell(raw(badge('Paid', 'secondary'))) . table_cell('$250.00', 'text-end'))) .
        table_row(raw(table_cell('INV-1002') . table_cell(raw(badge('Pending', 'outline'))) . table_cell('$150.00', 'text-end'))) .
        table_row(raw(table_cell('INV-1003') . table_cell(raw(badge('Overdue', 'destructive'))) . table_cell('$350.00', 'text-end'))))))), 'lg:col-span-5 md:col-span-6')
    . $tile('switch', 'Switch', '<div class="grid gap-4">' . $row('w-a', 'New comments', true) . $row('w-b', 'Weekly summary') . $row('w-c', 'Product news', true) . '</div>', 'lg:col-span-3 md:col-span-6')
    . $tile('command', 'Command', command(raw(command_input(attrs: ['placeholder' => 'Search commands', 'aria-label' => 'Search commands']) . command_list(raw(command_empty('Nothing found.') . command_group(raw(command_item('New invoice') . command_item('Add customer') . command_item('Export to CSV')), 'Suggestions')))), 'rounded-lg border'), 'lg:col-span-4 md:col-span-6')
    . $tile('input-otp', 'Input OTP', '<div class="grid justify-items-center gap-3 text-center"><p class="text-sm">Enter the code we sent you</p>' . input_otp(6, attrs: ['aria-label' => 'One-time code']) . '</div>', 'tile-saffron lg:col-span-4 md:col-span-6')
    . $tile('tabs', 'Tabs', tabs(raw(tabs_list(raw(tabs_trigger('Account', 'w-t1', true) . tabs_trigger('Password', 'w-t2') . tabs_trigger('Billing', 'w-t3'))) . tabs_content('Change your name and email here.', 'w-t1', true, 'text-sm') . tabs_content('Choose a new password.', 'w-t2', false, 'text-sm') . tabs_content('Manage your plan and invoices.', 'w-t3', false, 'text-sm'))), 'lg:col-span-4 md:col-span-6')
    . $tile('dialog', 'Dialog, menu and popover', '<div class="flex flex-wrap items-center justify-center gap-3">' . dialog_trigger('Edit profile', 'w-d', button_classes('default'))
        . dialog_content(raw(dialog_header(raw(dialog_title('Edit profile', 'w-d') . dialog_description('Change your details, then save.', 'w-d'))) . dialog_footer(raw(dialog_close('Cancel', button_classes('outline')) . dialog_close('Save', button_classes('default'))))), 'w-d')
        . dropdown_menu_trigger('Menu', 'w-m', button_classes('outline')) . dropdown_menu_content(raw(dropdown_menu_label('My account') . dropdown_menu_separator() . dropdown_menu_item('Profile', 'w-m') . dropdown_menu_item('Settings', 'w-m') . dropdown_menu_item('Sign out', 'w-m', 'destructive')), 'w-m', 'w-44')
        . popover_trigger('Popover', 'w-p', button_classes('outline')) . popover_content('Native popover, no JavaScript.', 'w-p', class: 'w-56 text-sm') . '</div>', 'dark lapis lg:col-span-5 md:col-span-6')
    . $tile('progress', 'Progress and slider', '<div class="grid gap-5">' . progress(68, attrs: ['aria-label' => 'Upload progress']) . slider(40, attrs: ['aria-label' => 'Volume']) . toggle_group(raw(toggle_group_item('Day', 'w-range', 'd', true, variant: 'outline') . toggle_group_item('Week', 'w-range', 'w', variant: 'outline') . toggle_group_item('Month', 'w-range', 'm', variant: 'outline')), attrs: ['aria-label' => 'Range']) . '</div>', 'lg:col-span-3 md:col-span-6')
    . $tile('accordion', 'Accordion', accordion(raw(accordion_item(raw(accordion_trigger('Do I need a build step?') . accordion_content('Only Tailwind, for the CSS. The PHP runs as it is.')), 'w-ac', true) . accordion_item(raw(accordion_trigger('Can I change the code?') . accordion_content('Yes. It is your code once it is copied.')), 'w-ac'))), 'tile-firoozeh lg:col-span-5 md:col-span-6')
    . $tile('avatar', 'Avatar and badge', '<div class="grid justify-items-center gap-4"><div class="flex gap-2">' . avatar(raw(avatar_fallback('SA'))) . avatar(raw(avatar_fallback('MR'))) . avatar(raw(avatar_fallback('PK'))) . '</div><div class="flex flex-wrap justify-center gap-2">' . badge('New') . badge('Beta', 'secondary') . badge('Archived', 'outline') . '</div></div>', 'lg:col-span-3 md:col-span-6')
    . $tile('pagination', 'Breadcrumb and pagination', '<div class="grid justify-items-center gap-4">' . breadcrumb(raw(breadcrumb_list(raw(breadcrumb_item(raw(breadcrumb_link('Home', '#'))) . breadcrumb_separator() . breadcrumb_item(raw(breadcrumb_page('Invoices'))))))) . pagination(raw(pagination_content(raw(pagination_item(raw(pagination_previous('#', 'Previous'))) . pagination_item(raw(pagination_link('1', '#'))) . pagination_item(raw(pagination_link('2', '#', true))) . pagination_item(raw(pagination_link('3', '#'))) . pagination_item(raw(pagination_next('#', 'Next'))))))) . '</div>', 'lg:col-span-4 md:col-span-6')
    . '</div>';

// The picker works on registry data, so its total matches what bin/vui would copy.
$reg = [];
foreach ($items as $n => $it) $reg[$n] = ['s' => $it['size'], 'd' => $it['dependsOn']];
$picks = '';
foreach ($examples as $n => $ex) $picks .= '<label class="pick"><input type="checkbox" value="' . $n . '" data-pick' . (in_array($n, ['button', 'card', 'dialog', 'input']) ? ' checked' : '') . '><span>' . e($ex['title']) . '</span><small>' . kb($items[$n]['size']) . '</small></label>';
$scripted = count(array_filter(array_keys($examples), fn($n) => array_filter($items[$n]['files'], fn($f) => str_ends_with($f, '.js'))));
$picker = '<div class="picker"><fieldset class="picks"><legend class="sr-only">Components to add</legend>' . $picks . '</fieldset>'
    . '<aside class="tally"><p class="tally-sum" aria-live="polite"><strong id="pick-sum">0 bytes</strong> for <span id="pick-n">0 components</span> and everything they need.</p>'
    . '<div class="slab">' . code("php vui init\nphp vui add", 'pick-cmd', 'Run this in your project') . '</div>'
    . '<p class="tally-note">Counts PHP, Blade, CSS and JavaScript. Pure PHP projects skip the Blade files, so they carry less.</p><p class="tally-note">Needs PHP 8.1 and Tailwind v4, nothing from Composer. ' . $scripted . ' of the ' . count($examples) . ' components use JavaScript, at most 2 KB per file and no libraries.</p></aside></div>';

$band = fn(string $id, string $h, string $p, string $inner) => '<section class="band"><h2 id="' . $id . '" class="display">' . $h . '</h2><p class="lede">' . $p . '</p>' . $inner . '</section>';
page('Introduction', 'index', $facade . '<div class="tabriz">'
    . $band('try', 'Try them here', 'Every tile runs the same code you copy in. Only the theme colors changed.', $mosaic)
    . '</div>'
    . $band('install', 'Add only what you use', 'Pick components to see what lands in your project and the command that puts it there.', $picker)
    . $band('write', 'Write PHP or Blade', 'The Blade wrappers call the same PHP functions, so both render the same HTML.', '<div class="pair">'
        . code("<?php\nrequire __DIR__ . '/ui/ui.php';\n\necho card(raw(\n    card_header(raw(card_title('Hello') . card_description('From plain PHP.'))) .\n    card_content(raw(button('Save')))\n));", 'p', 'Pure PHP', 'php')
        . code("<x-ui.card>\n    <x-ui.card.header>\n        <x-ui.card.title>Hello</x-ui.card.title>\n        <x-ui.card.description>From Blade.</x-ui.card.description>\n    </x-ui.card.header>\n    <x-ui.card.content><x-ui.button>Save</x-ui.button></x-ui.card.content>\n</x-ui.card>", 'b', 'Laravel Blade', 'blade') . '</div>')
    . '<script type="application/json" id="reg">' . json_encode($reg) . '</script>' . toaster());

// Persian page: every string is real Persian, forced RTL.
page('فارسی', 'rtl',
    '<header class="head"><h1 class="display">نمونه فارسی</h1><p class="lede">همه اجزا با جهت راست به چپ و متن فارسی.</p></header>'
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
    . date_picker(attrs: ['name' => 'fa-date', 'value' => '2026-11-09', 'aria-label' => 'تاریخ'], placeholder: 'انتخاب تاریخ', week_start: 6, labels: ['weekdays' => 'ی د س چ پ ج ش', 'prev' => 'ماه قبل', 'next' => 'ماه بعد'])
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
    ['rtl' => true]);

@mkdir(__DIR__ . '/js');
foreach (glob("$root/registry/js/*.js") as $f) copy($f, __DIR__ . '/js/' . basename($f));
file_put_contents(__DIR__ . '/js/site.js', <<<'JS'
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
JS);
touch(__DIR__ . '/.nojekyll');

// The wall is mirrored around its center column, and the same random glaze lands on both sides.
mt_srand(1465);
$u = 80; $cols = 18; $rows = 14; $mid = $half = '';
for ($y = 0; $y < $rows; $y++) for ($x = 0; $x <= $cols; $x++) {
    $r = mt_rand(0, 99);
    $tone = $r < 6 ? ' fill="#35C6B8" fill-opacity=".17"' : ($r < 8 ? ' fill="#F4B13E" fill-opacity=".2"' : ' fill-opacity=".' . mt_rand(20, 54) . '"');
    $cell = '<use href="#s" x="' . ($x * $u - $u / 2) . '" y="' . ($y * $u) . '"' . $tone . '/>';
    if ($x) $half .= $cell; else $mid .= $cell;
}
$w = ($cols * $u + $u / 2) * 2;
file_put_contents(__DIR__ . '/wall.svg', '<svg xmlns="http://www.w3.org/2000/svg" width="' . $w . '" height="' . ($rows * $u) . '" viewBox="' . (-$w / 2) . ' 0 ' . $w . ' ' . ($rows * $u) . '"><defs><path id="s" d="' . STAR . '" fill="#1F4A9E" stroke="#0A1C44" stroke-width="1"/></defs><rect x="' . (-$w / 2) . '" width="' . $w . '" height="' . ($rows * $u) . '" fill="#0E2556"/>' . $mid . '<g id="h">' . $half . '</g><use href="#h" transform="scale(-1 1)"/></svg>');
file_put_contents(__DIR__ . '/star.svg', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="' . STAR . '" fill="none" stroke="#000" stroke-width="2"/></svg>');
file_put_contents(__DIR__ . '/icon.svg', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#12306B"/><path d="M18 22l11 25 11-25" fill="none" stroke="#fff" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/><circle cx="48" cy="16" r="5" fill="#F4B13E"/></svg>');

$tw = "$root/node_modules/.bin/tailwindcss";
if (is_file($tw)) { passthru(escapeshellarg($tw) . ' -i ' . escapeshellarg(__DIR__ . '/site.src.css') . ' -o ' . escapeshellarg(__DIR__ . '/site.css') . ' --minify 2>&1', $code); if ($code) exit($code); }
else echo "tailwind not installed, keeping existing docs/site.css\n";
echo 'docs: ' . count(glob(__DIR__ . '/*.html')) . " pages\n";
