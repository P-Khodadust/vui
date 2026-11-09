<div align="center">

<img src="assets/hero.svg" alt="The vui banner: a headline and a dialog flip between an English left-to-right layout and a Persian right-to-left layout" width="100%">

### shadcn/ui for PHP and Laravel, copied into your project instead of installed.

<a href="https://github.com/P-Khodadust/vui/actions/workflows/tests.yml"><img alt="Tests" src="https://github.com/P-Khodadust/vui/actions/workflows/tests.yml/badge.svg"></a>
<img alt="PHP 8.1+" src="https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat-square&logo=php&logoColor=white">
<img alt="Laravel Blade" src="https://img.shields.io/badge/Laravel-Blade-FF2D20?style=flat-square&logo=laravel&logoColor=white">
<img alt="Tailwind v4" src="https://img.shields.io/badge/Tailwind-v4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white">
<img alt="54 components" src="https://img.shields.io/badge/components-54-12306B?style=flat-square">
<img alt="Core 1.2 KB" src="https://img.shields.io/badge/core-1.2%20KB-12306B?style=flat-square">
<img alt="0 dependencies" src="https://img.shields.io/badge/dependencies-0-35C6B8?style=flat-square">
<img alt="RTL first" src="https://img.shields.io/badge/RTL-first-F4B13E?style=flat-square">
<a href="LICENSE"><img alt="MIT license" src="https://img.shields.io/badge/license-MIT-blue?style=flat-square"></a>

**[Live demo](https://p-khodadust.github.io/vui/)** · [Components](#-components) · [Install](#-install) · [Usage](#-usage) · [Animations](#-animations) · [RTL](#-rtl-and-persian) · [Known limits](#%EF%B8%8F-known-limits) · [فارسی](#-فارسی)

</div>

---

<p align="center"><img src="assets/demo.gif" alt="A page built with vui: a menu, a dialog, a toast and a sheet animate, then the page flips to Persian right-to-left and to dark mode" width="100%"></p>

<p align="center"><sub>Not a mockup. This is vui's real components being used, including the switch to Persian and dark mode.</sub></p>

vui looks and is structured like shadcn/ui: same CSS variables, same variant names, same Tailwind class strings. It runs in plain PHP and in Laravel Blade from one copy of the logic, and Persian RTL works without extra setup.

## 💡 Philosophy

There is no runtime package. Like shadcn/ui, vui is a registry of source files and a small CLI that copies them into your project. You own the code and carry only what you added.

The priorities, in strict order:

1. **Tiny size.** It beats every other goal.
2. **Similarity to shadcn/ui.**
3. **Same behavior in pure PHP and Blade**, logic written once.
4. **First-class RTL.**

The platform does the work first: `<dialog>`, the `popover` attribute, `<details name>`, native inputs. **45 of 54 components ship no JavaScript file**, and the 9 scripts total 10.7 KB.

## 🧱 Components

54 components. Each one is a separate copy-in: you only carry what you `add`.

| | |
|---|---|
| **Forms** (no JS) | button, button-group, checkbox, combobox, field, form, input, input-group, input-otp, label, radio-group, select, slider, switch, textarea, toggle, toggle-group |
| **Layout and display** (no JS) | accordion, alert, aspect-ratio, avatar, badge, breadcrumb, calendar, card, collapsible, empty, item, kbd, pagination, progress, scroll-area, separator, sidebar, skeleton, spinner, table, typography |
| **Overlays** (no JS) | alert-dialog, dialog, drawer, hover-card, navigation-menu, popover, sheet |
| **Ship a small script** | carousel, command, context-menu, date-picker, dropdown-menu, menubar, tabs, toast, tooltip |

Of the scripts, `tabs.js` and `context-menu.js` are needed for the component's main job. The other seven add keyboard or convenience behavior on top of something that already works.

**Not included from shadcn:**

| Component | Why | Instead |
|---|---|---|
| `chart` | Upstream wraps Recharts, a JS charting library | Any chart library, or server-drawn SVG |
| `data-table` | Upstream wraps TanStack Table. It's a pattern, not a component | `table` with `pagination` and server-side sorting |
| `resizable` | Dragging needs real JS | CSS `resize-x` on a box |
| custom `select` | It's a large JS listbox | `select` is native, `combobox` adds search |

## 📦 Install

You need PHP 8.1+ and Tailwind CSS v4. Composer is not required for pure PHP. From your project root:

```bash
git clone https://github.com/P-Khodadust/vui
cd your-project
php ../vui/bin/vui init
php ../vui/bin/vui add button card dialog
```

`init` detects Laravel by the presence of `artisan` and prints the exact lines to add for your setup.

| | Pure PHP | Laravel |
|---|---|---|
| Core | `ui/ui.php` | `resources/ui/ui.php` |
| Theme | `ui/theme.css` | `resources/css/theme.css` |
| Components | `ui/components/*.php` | `resources/ui/components/*.php` |
| Blade wrappers | not copied | `resources/views/components/ui/` |
| JS | `ui/js/` | `public/js/ui/` |

### CLI

```text
php vui init            Copy core + theme, print the lines to add
php vui add <name...>   Copy components and dependencies (never overwrites without --force)
php vui list            Show components and byte sizes
php vui diff <name...>  Compare local files against the registry (exit 1 if they differ)
```

The registry is this repo's `registry/` folder by default. To skip the clone, pass `--registry=https://raw.githubusercontent.com/P-Khodadust/vui/main/registry/registry.json` or set `VUI_REGISTRY`. Remote registries must use HTTPS, and registry paths that would write outside your project are refused.

## 🧩 Usage

### One component, two environments

<table>
<tr><th>Pure PHP</th><th>Laravel Blade</th></tr>
<tr><td>

```php
<?php
require __DIR__ . '/ui/ui.php';

echo card(raw(
    card_header(raw(
        card_title('Hello')
    )) .
    card_content(raw(
        button('Save')
    ))
), 'max-w-sm');
```

</td><td>

```blade
<x-ui.card class="max-w-sm">
    <x-ui.card.header>
        <x-ui.card.title>
            Hello
        </x-ui.card.title>
    </x-ui.card.header>
    <x-ui.card.content>
        <x-ui.button>Save</x-ui.button>
    </x-ui.card.content>
</x-ui.card>
```

</td></tr>
</table>

`ui.php` loads every file in `ui/components/`, so that one `require` is all you need. In Laravel the wrappers are thin: they call the PHP function, so no class string is duplicated. `class` merges through `cn()` and every other attribute passes through. The tests render every example both ways and require the same HTML.

### Escaping and `raw()`

Plain strings are always escaped. Only `raw()` skips escaping.

```php
button('<script>');          // <button ...>&lt;script&gt;</button>
button(raw('<b>Save</b>'));  // <button ...><b>Save</b></button>
```

Component functions return plain strings, so **nesting needs `raw()` at each level**, as in the card above. Never pass user input to `raw()`. In Blade, `$slot` is already safe and `{{ }}` escapes as usual.

### Patterns that differ from React

- **Triggers point at an id.** There is no context, so `dialog_trigger('Open', 'x')` opens `dialog_content(..., 'x')`. Popover and dropdown work the same way.
- **Style a trigger with `button_classes()`** where shadcn would use `asChild`: `dialog_trigger('Open', 'x', button_classes('outline'))`. It needs the `button` component installed.
- **`switch_()` and `empty_()`**, because those are reserved words in PHP. In Blade they are still `<x-ui.switch>` and `<x-ui.empty>`.

Every component has a live preview, snippets, props and source in the [docs site](https://p-khodadust.github.io/vui/).

## 🎨 Theming

`theme.css` uses the exact shadcn variable names with the default neutral theme. Paste any theme from shadcn's themes page after the import and it works unchanged:

```css
@import "tailwindcss";
@import "./ui/theme.css";

:root { --primary: oklch(0.55 0.2 260); /* ...the rest of your pasted theme */ }
.dark { /* ... */ }
```

Dark mode is the `.dark` class on `<html>`, same as shadcn. Chart and sidebar variables are left out because no component uses them. Pasted ones do no harm.

## 🎬 Animations

Overlays, accordions, toasts and tabs animate through one shared file, `motion.css` (2.3 KB). Components that need it depend on it, so `php vui add dialog` copies it and prints the import line. A project with only buttons doesn't carry it. If the import is missing, everything still works, just without motion.

| Component | Motion |
|---|---|
| dialog, alert-dialog | Fades and zooms in and out, backdrop fades |
| popover, dropdown-menu, menubar, context-menu, navigation-menu | Fades and zooms in and out |
| sheet, drawer, sidebar on phones | Slides from its edge, mirrored in RTL |
| accordion, collapsible | Height and fade |
| toast | Slides and fades in and out |
| tabs | Panel fades when switching, not on page load |
| tooltip, hover-card | Fades, and the pointer can still reach it |
| checkbox, radio-group | The mark scales in and out |
| toggle, toggle-group | Background color |

Buttons, inputs, switch, carousel, skeleton and spinner already had shadcn's motion. Static components like card, badge and table have no state to animate.

Users with `prefers-reduced-motion` get no transitions, through one rule in `theme.css`. Spinner and skeleton keep moving, because they signal loading.

These are plain CSS transitions on native states, not `tw-animate-css` classes, so the class strings differ from shadcn here.

## 🌐 RTL and Persian

Set `<html dir="rtl" lang="fa">` and every component mirrors. There is nothing else to configure.

- Only logical utilities are used (`ms-*`, `ps-*`, `start-*`, `text-start`). A test fails the build if a physical one such as `ml-*` or `left-*` appears.
- Direction-implying chevrons flip, the switch thumb travels the right way, and tab arrow keys follow `dir`.
- Letter-spacing is reset in RTL (`rtl:tracking-normal`) where it would break Persian letter joining.
- User-facing strings can be translated: `dialog_content($c, 'x', 'بستن')`, `pagination_next('#', 'بعدی')`, `toaster(close: 'بستن')`.

For a Persian font stack with no bundled font files:

```bash
php vui add font-fa
```

```css
@import "./ui/font-fa.css";
```

The docs include a full [Persian demo page](https://p-khodadust.github.io/vui/rtl.html).

## 📏 Size policy

`php bin/size` prints every registry file and fails if a budget is exceeded. Budgets count PHP plus Blade wrappers; pure PHP projects carry less because wrappers aren't copied. Each JS file has its own 2 KB limit.

<details>
<summary><b>Size of every item</b></summary>

| Item | Bytes | | Item | Bytes | | Item | Bytes |
|---|---:|---|---|---:|---|---|---:|
| **core** | **1,216** | | drawer | 1,888 | | radio-group | 1,080 |
| accordion | 1,383 | | dropdown-menu ⚠️ | 2,526 | | scroll-area | 378 |
| alert | 1,181 | | empty | 1,794 | | select | 1,680 |
| alert-dialog | 1,709 | | field | 1,978 | | separator | 592 |
| aspect-ratio | 261 | | font-fa | 217 | | sheet | 1,875 |
| avatar | 1,066 | | form | 1,857 | | sidebar ⚠️ | 3,280 |
| badge | 1,334 | | hover-card | 1,273 | | sidebar-theme | 1,127 |
| breadcrumb | 1,878 | | icon | 333 | | skeleton | 182 |
| button | 1,747 | | input | 788 | | slider | 465 |
| button-group | 1,370 | | input-group | 1,920 | | spinner | 304 |
| calendar | 2,033 | | input-otp | 1,049 | | switch | 999 |
| card | 1,553 | | item | 1,996 | | table | 1,904 |
| carousel | 1,895 | | kbd | 704 | | tabs ⚠️ | 2,120 |
| checkbox | 817 | | label | 372 | | textarea | 624 |
| collapsible | 674 | | menubar | 1,675 | | theme | 2,706 |
| combobox | 557 | | motion ⚠️ | 2,262 | | toast | 1,279 |
| command | 1,992 | | navigation-menu | 1,981 | | toggle | 1,302 |
| context-menu | 1,354 | | pagination | 2,012 | | toggle-group | 1,070 |
| date-picker | 1,285 | | popover | 794 | | tooltip | 1,415 |
| dialog ⚠️ | 2,534 | | progress | 573 | | typography | 1,141 |

JS is separate: `command.js` 2,047, `date-picker.js` 2,047, `carousel.js` 1,136, `dropdown-menu.js` 1,101, `tabs.js` 1,092, `menubar.js` 1,062, `context-menu.js` 970, `toast.js` 761, `tooltip.js` 442.

</details>

⚠️ Allowed over 2 KB, and each is justified in `bin/size`. Dialog and dropdown-menu have 6-7 sub-components, so 6-7 Blade wrappers at about 80 B each; their PHP alone is about 2 KB. Tabs carries one long state-class string. Sidebar is the largest at 3.3 KB, because 12 sub-components mean 12 wrappers. The brief only allowed the first three to go over, so sidebar is a deliberate exception, kept instead of dropping shadcn's part names. Upstream's is 21 KB.

Dependencies are counted separately. For example `sheet` needs `dialog` and `icon`, and `navigation-menu` needs `button` and `icon`.

Registry PHP is written without formatting whitespace on purpose. Those spaces added roughly 10% with no benefit once each function is one line.

## 🧭 Browser support

Exact versions from MDN browser-compat-data. Tailwind v4 itself needs Chrome 111, Firefox 128 or Safari 16.4.

<details>
<summary><b>Feature support by browser</b></summary>

| Feature | Used by | Chrome | Firefox | Safari | If unsupported |
|---|---|---:|---:|---:|---|
| `<dialog>` | dialog | 37 | 98 | 15.4 | Needs a polyfill |
| `command`/`commandfor` | dialog trigger | 135 | 144 | 26.2 | Inline `onclick` calls `showModal()` |
| `closedby` | dialog backdrop click | 134 | 141 | not yet | Esc and the close button still work |
| `popover` | popover, dropdown-menu | 114 | 125 | 17 | Panel stays hidden, trigger does nothing |
| `position-area` | popover, dropdown-menu | 129 | 147 | 26 | Panel opens centered in the viewport |
| `<details name>` | accordion | 120 | 130 | 17.2 | Items open independently |
| `transition-behavior` | all of `motion.css` | 117 | 129 | 17.4 | No overlay, accordion, toast or tab animation |
| `@starting-style` | enter animations | 117 | 129 | 17.5 | No enter animation |
| `overlay` | keeps a closing overlay above the page | 117 | no | no | It fades out below the top layer |
| `::details-content` | accordion animation | 131 | 143 | 18.4 | No animation |
| `interpolate-size` | accordion height | 129 | no | no | Height snaps |
| `field-sizing` | textarea | 123 | 152 | 26.2 | Fixed height, no auto-grow |
| `accent-color` | slider | 93 | 92 | 15.4 | Browser-default slider color |
| `overflow: clip` | input-otp | 90 | 81 | 16 | Digits can shift out of their boxes when full |
| `anchor-scope` | tooltip and hover card positioning | 131 | 147 | 26 | No flip at screen edges, clipped by `overflow-hidden` |
| `<input list>` | combobox | 20 | 4 | 12.1 | Plain text input |
| `scrollbar-color` | scroll-area | 121 | 64 | 26.2 | Browser-default scrollbar |

</details>

**Actually tested on the current code:** Chromium 151 with 448 behavior and edge-case checks in both directions (animation, overlays, hover, click, forms, nesting, long content, 320 px phones, reduced motion), every docs page at phone and desktop width in both directions (224 renders), axe and html-validate on every component, and all 104 keyboard tab stops for a visible focus indicator. Stock Firefox 159 was checked for dialog, sheet and accordion animation, toast, check mark, sidebar, tooltip focus and edge positioning, carousel, input-group, command focus, menubar switching, dialogs inside forms, long menus and the date picker.

## ✂️ `cn()` limits

<details>
<summary><b>What cn() does and doesn't merge</b></summary>

`cn()` is a small class merger, not tailwind-merge. It keeps order and lets the last class win within the same utility group and the same variant prefix.

**Merged groups:** `h`, `w`, `size`, `p`, `px`, `py`, `pt/pr/pb/pl/ps/pe`, the same for `m` (negatives included), `gap`, `gap-x`, `gap-y`, `rounded`, `rounded-t/b/l/r/s/e`.

**Not merged:**

- **`text-*` and `border-*`**, because they are ambiguous between color and size. Colors like `bg-*` aren't merged either.
- **Overlapping groups:** `p-4 px-2` keeps both, and so does `size-4 h-6`.
- **Corner radii** like `rounded-tl-lg`, and `min-*`/`max-*`, which pass through.
- **Different variant order:** `md:hover:p-1` and `hover:md:p-1` count as different.

- **Custom classes that look like utilities:** `cn('p-4', 'p-card')` reads `p-card` as padding and drops `p-4`. Pick another name for your own classes.

**The override rule:** when a class doesn't win, add Tailwind v4's `!` suffix. This is the common case for colors and text alignment:

```php
button('Save', class: 'bg-red-500!');
table_head('Amount', 'text-end!');   // the base has text-start
```

`!` classes form their own group, so `p-2 p-4!` keeps both. The old `!p-4` prefix passes through unmerged.

</details>

## ⚠️ Known limits

**Security**
- `href`, ids and other attribute values are HTML-escaped but not validated. Check user-supplied URLs yourself, because `javascript:` is still a valid `href`.
- `attrs()` doesn't double-encode existing entities. It has to, because Blade has already escaped `:bound` attributes. The catch is that literal text `&amp;` in an attribute displays as `&`. This can't cause injection.
- A strict CSP blocks a few inline attributes. Dialog close buttons, the dialog fallback for old browsers, the toast close button, the avatar image's fallback and the input-group icon use inline handlers. Esc still closes a dialog. Popover and dropdown positioning use an inline `style`, so they fall back to centered.

**Behavior**
- **Some scripts bind at page load.** After Livewire, HTMX or Turbo swap in new markup, dropdown-menu, menubar, command, carousel and date-picker keep working natively but lose their script extras until a full load. Tabs, tooltip, toast and context-menu are unaffected.
- **Contrast:** shadcn's default theme is just under WCAG AA in two places, `muted-foreground` on `muted` (4.34:1) and `destructive` text (4.49:1). Kept for parity. Adjust those two tokens if you need AA.
- Functions are global and unprefixed, so `button()` can clash with your own code. You own the files, so rename if needed.
- **Tabs need `tabs.js`** to switch without a reload. For zero JS, give triggers `attrs: ['href' => '?tab=x']` and render the active tab on the server.
- **Dropdown keyboard:** without `dropdown-menu.js` it's Tab, Esc and click. Arrow keys and `role="menu"` come with the script, since those roles promise arrow keys.
- **Tooltip** is CSS only and always above the trigger. With `motion.css` and `anchor-scope` it shifts at screen edges and escapes `overflow-hidden` parents; without them it can be clipped. Esc to dismiss needs `tooltip.js`.
- **Dialog** doesn't lock page scroll. Add `class="has-[dialog:modal]:overflow-hidden"` to `<html>`.
- **Toasts** sit below an open modal dialog, which lives in the browser's top layer.
- **Accordion** has no arrow-key navigation.
- **Firefox skips exit animations.** Measured on Firefox 159: overlays animate in, then close instantly. Accordion height snaps there too.
- **Select and combobox** are native, so their open popups can't be styled.
- **Calendar and date-picker are Gregorian only.** There is no Jalali (Persian) calendar yet. Calendar accepts Persian labels and a Saturday week start, but the dates and digits stay Gregorian. Doing it without the `intl` extension means shipping a conversion routine.
- **Calendar** days are links that reload the page, and it has no range selection or arrow-key navigation.
- **Toggle** is a checkbox in a label, not `<button aria-pressed>`, so screen readers announce a checkbox. In exchange it needs no JS and submits with forms.
- **Slider** has one thumb, so no range selection. The filled track comes from the browser's `accent-color`.
- **Input OTP** is one input drawn as boxes, so there's no separate highlight for the active box.
- **Drawer** can't be dragged. Close it with Esc, an outside click or a button.
- **Navigation menu** opens on click, not hover.
- **Hover card** can't be dismissed with Esc. It flips at screen edges under the same conditions as tooltip.
- **Context menu** needs `context-menu.js`. Without it the browser's own menu shows.
- **Sidebar** has no collapse-to-icons mode, submenus or badges. It needs the `sidebar-theme` import, which `add` prints.

<details>
<summary><b>What was left out, and other differences from shadcn</b></summary>

**Left out of shadcn to save bytes.** Add them back if you need them:

| Left out | Saves (estimate) | Cheaper alternative |
|---|---:|---|
| Tabs `line` variant and vertical orientation | ~650 B | Style with `class` |
| Dropdown submenus, checkbox and radio items | ~1 KB | A nested popover, or your own input in an item |
| `dropdown_menu_group`, `inset` | ~200 B | `<div role="group">`, `class: 'ps-8'` |
| Popover header/title/description/anchor | ~500 B | Plain markup inside `popover_content` |
| Avatar badge, group, count | ~1 KB | `flex -space-x-2` on a wrapper |
| Tooltip `side` | ~180 B | Change `position-area` in `motion.css` |
| Item group, header, footer, separator, image media | ~450 B | Plain markup, `separator()` |
| Field content, separator, responsive orientation | ~400 B | Plain markup |
| Input group textarea, text, block alignment | ~600 B | Plain `<span>` in an addon |
| Navigation menu list, viewport, indicator | ~300 B | `navigation_menu()` renders the list itself |
| Command separator, shortcut, dialog variant | ~300 B | `separator()`, `kbd()`, wrap it in `dialog_content()` |
| `sheet_close` | ~150 B | `dialog_close()`, which comes with it |
| Context-only roots (`Dialog`, `Popover`, `DropdownMenu`, `Form`) | n/a | They render no DOM, so triggers use ids |

**Other differences from shadcn**
- `@container/card-header` is removed. It makes a `transition-all` button inside a card header flash browser-default styles for 150 ms on every page load in Chrome, and no component here queries it.
- Popover and dropdown align to the trigger's start edge rather than its center, because that stays on screen without collision JS.
- The checkbox mark is a CSS `clip-path`, so there's no `indeterminate` state.
- Pagination labels stay visible on phones.
- Every clickable element shows the hand cursor, through one rule in `theme.css`. Tailwind v4 and shadcn default buttons and menu items to the arrow.
- Tooltip and hover card hide with `scale-0` and `visibility` instead of Radix's portal, so a hidden one can't widen the page and the pointer can still reach it. `@container` is also removed from `field_group`, for the same flash reason as the card.
- `sheet` sides are logical: `start` and `end` instead of `left` and `right`.
- Toast matches Sonner's look but not its class strings, since Sonner uses its own CSS.

</details>

## 🧠 Assumptions

<details>
<summary><b>Every assumption made while building</b></summary>

Choices made where the brief left room:

- **Class strings and theme values** were fetched from `shadcn-ui/ui` `main` (`apps/v4/registry/new-york-v4/ui/`, `public/r/colors/neutral.json`) on 2026-11-07, not reproduced from memory.
- **Sizes:** only `default | sm | lg | icon` as the brief lists, not upstream's newer `xs` and `icon-*`.
- **`select`** is upstream's `native-select`, since the brief asks for a styled native select.
- **The `ui()` render helper is omitted**, which the brief allows. Core adds `el()` (builds a tag) and `h()` (escape unless raw) because every component needs them.
- **`icon` is its own registry item**, so components that draw no icon don't carry it.
- **The budget counts PHP + Blade together**, the stricter reading. JS is budgeted separately.
- **The added components** follow the same rules, and map to shadcn's names except where the README says otherwise. `typography` is one function with a style table, not 12 functions, which saves about 1 KB.
- **`attrs()` uses `htmlspecialchars()` with default flags**, which include `ENT_QUOTES` on PHP 8.1+ and also keep invalid UTF-8 from blanking the value. `double_encode` is off, as explained above.
- **`registry.json` `size`** is the total of all an item's files, including JS.
- **The CLI reads this repo's local `registry/` folder by default,** and takes a hosted one through `--registry`.

</details>

## 🧪 Develop & test

```bash
php tests/run.php             # units, XSS on every function, snapshots, RTL lint, CLI
php tests/run.php --update    # rewrite snapshots after an intentional change
php bin/size                  # budget check. --write syncs files and sizes into registry.json

# Optional: every Blade example against a real Laravel app (it installs into that app with --force)
VUI_LARAVEL=/path/to/throwaway/laravel php tests/blade.php
```

The suite has 990 tests. Verified on PHP 8.1.34 and 8.5.12, Laravel 13.40, Tailwind 4.3.5. The browser checks described above were run with a throwaway Playwright harness and aren't part of this repo, because the project has no dependencies by design.

### Docs site

```bash
npm i --no-save @tailwindcss/cli@4 tailwindcss@4   # only to recompile CSS
php docs/build.php
```

It generates static HTML into `docs/`. To deploy, go to GitHub **Settings → Pages → Deploy from a branch** and choose your branch and `/docs`.

## ➕ Add a component

1. Create `registry/components/<name>/<name>.php`. Use functions built on `el()`, with the signature `($c='', $class='', $attrs=[])`. Copy class strings from shadcn, swapping physical utilities for logical ones.
2. Add thin Blade wrappers: `<name>.blade.php` for the root and `<sub>.blade.php` for each part. They call the PHP function, with no copied classes:
   ```blade
   @props(['variant' => 'default'])
   {!! badge(raw($slot), $variant, attrs: $attributes->getAttributes()) !!}
   ```
3. Add it to `registry/registry.json` with `name`, `type`, `dependsOn`, `description`.
4. Add an example to `docs/examples.php`. That one entry drives the snapshot test, the Blade equivalence test and the docs page.
5. Run `php bin/size --write`, `php tests/run.php --update`, then check the snapshot diff and `php tests/run.php`.

Before adding code: can the platform do it? If a feature costs more than a few hundred bytes, propose the cheaper alternative. Optional JS goes in `registry/js/<name>.js`, vanilla, at most 2 KB, with no class strings.

The tests then automatically cover XSS on your function, the RTL lint, wrapper rules and `dependsOn` completeness.

<div dir="rtl">

## 🪞 فارسی

**vui** کتابخانه‌ی کامپوننت‌های سبک برای PHP و Laravel است، با ظاهر و ساختار shadcn/ui. پکیجی نصب نمی‌شود: یک ابزار خط فرمان فقط کامپوننت‌هایی را که می‌خواهید در پروژه‌تان کپی می‌کند و کد مال خودتان است.

راست‌به‌چپ از روز اول جزو طراحی بوده و بعداً به آن وصله نشده:

- کافی است مقدار `dir` صفحه را `rtl` بگذارید تا همه‌ی کامپوننت‌ها قرینه شوند.
- فقط از کلاس‌های منطقی مثل `ms` و `ps` استفاده شده و یک تست جلوی کلاس‌های فیزیکی را می‌گیرد.
- فلش‌ها، دستگیره‌ی سوییچ و کلیدهای جهت‌نمای تب‌ها هم با `dir` عوض می‌شوند.
- فاصله‌ی حروف در راست‌به‌چپ صفر می‌شود تا حروف فارسی از هم جدا نشوند.

[صفحه‌ی نمونه‌ی فارسی را ببینید](https://p-khodadust.github.io/vui/rtl.html). تقویم فعلاً فقط میلادی است و تقویم جلالی هنوز اضافه نشده.

</div>

## 📄 License

MIT. shadcn/ui, Lucide and Feather notices are in [LICENSE-THIRD-PARTY](LICENSE-THIRD-PARTY).
