<?php
// Single source for snapshot tests, the Blade equivalence test and the docs site.
// 'php' is eval'd for previews and snapshots; 'blade' must render to the same HTML.
$common = [['class', 'string', "''", 'Merged through cn()'], ['attrs', 'array', '[]', 'Any other HTML attributes']];
return [
    'button' => [
        'title' => 'Button',
        'props' => [['content', 'string|Stringable', 'required', 'Escaped unless raw()'], ['variant', 'default|secondary|destructive|outline|ghost|link', "'default'", ''], ['size', 'default|sm|lg|icon', "'default'", ''], ...$common],
        'php' => <<<'X'
            echo button('Default');
            echo button('Secondary', 'secondary');
            echo button('Destructive', 'destructive');
            echo button('Outline', 'outline');
            echo button('Ghost', 'ghost');
            echo button('Link', 'link');
            echo button('Small', size: 'sm');
            echo button('Large', size: 'lg', attrs: ['type' => 'submit']);
            echo '<a href="#" class="' . e(button_classes('outline')) . '">Styled link</a>';
            X,
        'blade' => <<<'X'
            <x-ui.button>Default</x-ui.button>
            <x-ui.button variant="secondary">Secondary</x-ui.button>
            <x-ui.button variant="destructive">Destructive</x-ui.button>
            <x-ui.button variant="outline">Outline</x-ui.button>
            <x-ui.button variant="ghost">Ghost</x-ui.button>
            <x-ui.button variant="link">Link</x-ui.button>
            <x-ui.button size="sm">Small</x-ui.button>
            <x-ui.button size="lg" type="submit">Large</x-ui.button>
            <a href="#" class="{{ button_classes('outline') }}">Styled link</a>
            X,
    ],
    'input' => [
        'title' => 'Input',
        'props' => $common,
        'php' => <<<'X'
            echo input('max-w-sm', ['type' => 'email', 'name' => 'email', 'placeholder' => 'Email']);
            X,
        'blade' => <<<'X'
            <x-ui.input class="max-w-sm" type="email" name="email" placeholder="Email" />
            X,
    ],
    'label' => [
        'title' => 'Label',
        'props' => [['c', 'string|Stringable', "''", 'Escaped unless raw()'], ...$common],
        'php' => <<<'X'
            echo label('Your email', attrs: ['for' => 'email']);
            X,
        'blade' => <<<'X'
            <x-ui.label for="email">Your email</x-ui.label>
            X,
    ],
    'textarea' => [
        'title' => 'Textarea',
        'props' => [['c', 'string|Stringable', "''", 'The value. Escaped unless raw()'], ...$common],
        'php' => <<<'X'
            echo textarea('', 'max-w-sm', ['placeholder' => 'Type your message here.']);
            X,
        'blade' => <<<'X'
            <x-ui.textarea class="max-w-sm" placeholder="Type your message here."></x-ui.textarea>
            X,
    ],
    'card' => [
        'title' => 'Card',
        'props' => [['c', 'string|Stringable', "''", 'Escaped unless raw(). Every sub-component takes the same three arguments'], ...$common],
        'php' => <<<'X'
            echo card(raw(
                card_header(raw(
                    card_title('Create project') .
                    card_description('Deploy your new project in one click.') .
                    card_action(raw(button('Skip', 'link')))
                )) .
                card_content('Your project will be created with the default settings.') .
                card_footer(raw(button('Deploy')))
            ), 'max-w-sm');
            X,
        'blade' => <<<'X'
            <x-ui.card class="max-w-sm">
                <x-ui.card.header>
                    <x-ui.card.title>Create project</x-ui.card.title>
                    <x-ui.card.description>Deploy your new project in one click.</x-ui.card.description>
                    <x-ui.card.action><x-ui.button variant="link">Skip</x-ui.button></x-ui.card.action>
                </x-ui.card.header>
                <x-ui.card.content>Your project will be created with the default settings.</x-ui.card.content>
                <x-ui.card.footer><x-ui.button>Deploy</x-ui.button></x-ui.card.footer>
            </x-ui.card>
            X,
    ],
    'badge' => [
        'title' => 'Badge',
        'props' => [['c', 'string|Stringable', "''", 'Escaped unless raw()'], ['variant', 'default|secondary|destructive|outline|ghost|link', "'default'", ''], ...$common],
        'php' => <<<'X'
            echo badge('Default');
            echo badge('Secondary', 'secondary');
            echo badge('Destructive', 'destructive');
            echo badge('Outline', 'outline');
            X,
        'blade' => <<<'X'
            <x-ui.badge>Default</x-ui.badge>
            <x-ui.badge variant="secondary">Secondary</x-ui.badge>
            <x-ui.badge variant="destructive">Destructive</x-ui.badge>
            <x-ui.badge variant="outline">Outline</x-ui.badge>
            X,
    ],
    'separator' => [
        'title' => 'Separator',
        'props' => [['orientation', 'horizontal|vertical', "'horizontal'", ''], ['decorative', 'bool', 'true', 'false exposes role="separator"'], ...$common],
        'php' => <<<'X'
            echo separator();
            echo '<div class="flex h-5 items-center gap-4">Blog' . separator('vertical') . 'Docs' . separator('vertical', false) . 'Source</div>';
            X,
        'blade' => <<<'X'
            <x-ui.separator />
            <div class="flex h-5 items-center gap-4">Blog<x-ui.separator orientation="vertical" />Docs<x-ui.separator orientation="vertical" :decorative="false" />Source</div>
            X,
    ],
    'alert' => [
        'title' => 'Alert',
        'props' => [['c', 'string|Stringable', "''", 'Escaped unless raw()'], ['variant', 'default|destructive', "'default'", ''], ...$common],
        'php' => <<<'X'
            echo alert(raw(alert_title('Heads up!') . alert_description('You can add components using the CLI.')));
            echo alert(raw(alert_title('Error') . alert_description('Your session has expired.')), 'destructive');
            X,
        'blade' => <<<'X'
            <x-ui.alert>
                <x-ui.alert.title>Heads up!</x-ui.alert.title>
                <x-ui.alert.description>You can add components using the CLI.</x-ui.alert.description>
            </x-ui.alert>
            <x-ui.alert variant="destructive">
                <x-ui.alert.title>Error</x-ui.alert.title>
                <x-ui.alert.description>Your session has expired.</x-ui.alert.description>
            </x-ui.alert>
            X,
    ],
    'dialog' => [
        'title' => 'Dialog',
        'js' => 'No script file. Uses <dialog> and command/commandfor. Close buttons use a one-line inline handler, so a dialog can sit inside a form.',
        'props' => [['dialog_trigger($c, $for)', 'string', 'required', '$for is the dialog id'], ['dialog_content($c, $id, $close)', 'string|false', "'Close'", 'Screen-reader label of the X button, false hides it'], ['dialog_title / dialog_description ($c, $for)', 'string', "''", 'Pass the dialog id to wire aria-labelledby / aria-describedby'], ...$common],
        'php' => <<<'X'
            echo dialog_trigger('Edit profile', 'profile', button_classes('outline'));
            echo dialog_content(raw(
                dialog_header(raw(
                    dialog_title('Edit profile', 'profile') .
                    dialog_description('Make changes to your profile here.', 'profile')
                )) .
                '<input autofocus class="h-9 rounded-md border px-3" aria-label="Name">' .
                dialog_footer(raw(
                    dialog_close('Cancel', button_classes('outline')) .
                    button('Save changes')
                ))
            ), 'profile');
            X,
        'blade' => <<<'X'
            <x-ui.dialog.trigger for="profile" :class="button_classes('outline')">Edit profile</x-ui.dialog.trigger>
            <x-ui.dialog.content id="profile">
                <x-ui.dialog.header>
                    <x-ui.dialog.title for="profile">Edit profile</x-ui.dialog.title>
                    <x-ui.dialog.description for="profile">Make changes to your profile here.</x-ui.dialog.description>
                </x-ui.dialog.header>
                <input autofocus class="h-9 rounded-md border px-3" aria-label="Name">
                <x-ui.dialog.footer>
                    <x-ui.dialog.close :class="button_classes('outline')">Cancel</x-ui.dialog.close>
                    <x-ui.button>Save changes</x-ui.button>
                </x-ui.dialog.footer>
            </x-ui.dialog.content>
            X,
    ],
    'accordion' => [
        'title' => 'Accordion',
        'js' => 'None. Uses <details name>.',
        'props' => [['accordion_item($c, $name, $open)', 'string, bool', "'', false", 'Same $name = one open at a time'], ...$common],
        'php' => <<<'X'
            echo accordion(raw(
                accordion_item(raw(accordion_trigger('Is it accessible?') . accordion_content('Yes. It is built on native details and summary.')), 'faq', true) .
                accordion_item(raw(accordion_trigger('Does it need JavaScript?') . accordion_content('No.')), 'faq')
            ), 'w-full max-w-md');
            X,
        'blade' => <<<'X'
            <x-ui.accordion class="w-full max-w-md">
                <x-ui.accordion.item name="faq" :open="true">
                    <x-ui.accordion.trigger>Is it accessible?</x-ui.accordion.trigger>
                    <x-ui.accordion.content>Yes. It is built on native details and summary.</x-ui.accordion.content>
                </x-ui.accordion.item>
                <x-ui.accordion.item name="faq">
                    <x-ui.accordion.trigger>Does it need JavaScript?</x-ui.accordion.trigger>
                    <x-ui.accordion.content>No.</x-ui.accordion.content>
                </x-ui.accordion.item>
            </x-ui.accordion>
            X,
    ],
    'dropdown-menu' => [
        'title' => 'Dropdown Menu',
        'js' => 'Optional dropdown-menu.js adds role=menu, arrow keys, Home/End. Tab, Esc and click work without it.',
        'props' => [['dropdown_menu_trigger($c, $for)', 'string', 'required', '$for is the menu id'], ['dropdown_menu_content($c, $id)', 'string', 'required', ''], ['dropdown_menu_item($c, $for, $variant)', "string, string, 'default'|'destructive'", "'', 'default'", '$for closes the menu on click without JS; attrs href makes it a link. Use class ps-8 for inset'], ...$common],
        'php' => <<<'X'
            echo dropdown_menu_trigger('Open', 'menu', button_classes('outline'));
            echo dropdown_menu_content(raw(
                dropdown_menu_label('My Account') .
                dropdown_menu_separator() .
                dropdown_menu_item(raw('Profile' . dropdown_menu_shortcut('⇧⌘P')), 'menu') .
                dropdown_menu_item('Billing', 'menu') .
                dropdown_menu_item('Docs', attrs: ['href' => '#']) .
                dropdown_menu_separator() .
                dropdown_menu_item('Log out', 'menu', 'destructive')
            ), 'menu', 'w-56');
            X,
        'blade' => <<<'X'
            <x-ui.dropdown-menu.trigger for="menu" :class="button_classes('outline')">Open</x-ui.dropdown-menu.trigger>
            <x-ui.dropdown-menu.content id="menu" class="w-56">
                <x-ui.dropdown-menu.label>My Account</x-ui.dropdown-menu.label>
                <x-ui.dropdown-menu.separator />
                <x-ui.dropdown-menu.item for="menu">Profile<x-ui.dropdown-menu.shortcut>⇧⌘P</x-ui.dropdown-menu.shortcut></x-ui.dropdown-menu.item>
                <x-ui.dropdown-menu.item for="menu">Billing</x-ui.dropdown-menu.item>
                <x-ui.dropdown-menu.item href="#">Docs</x-ui.dropdown-menu.item>
                <x-ui.dropdown-menu.separator />
                <x-ui.dropdown-menu.item for="menu" variant="destructive">Log out</x-ui.dropdown-menu.item>
            </x-ui.dropdown-menu.content>
            X,
    ],
    'popover' => [
        'title' => 'Popover',
        'js' => 'None. Uses the popover attribute and CSS anchor positioning, centered where unsupported.',
        'props' => [['popover_trigger($c, $for)', 'string', 'required', '$for is the popover id'], ['popover_content($c, $id)', 'string', 'required', ''], ...$common],
        'php' => <<<'X'
            echo popover_trigger('Dimensions', 'dims', button_classes('outline'));
            echo popover_content('Set the dimensions for the layer.', 'dims');
            X,
        'blade' => <<<'X'
            <x-ui.popover.trigger for="dims" :class="button_classes('outline')">Dimensions</x-ui.popover.trigger>
            <x-ui.popover.content id="dims">Set the dimensions for the layer.</x-ui.popover.content>
            X,
    ],
    'tooltip' => [
        'title' => 'Tooltip',
        'js' => 'Optional tooltip.js adds Esc to dismiss. Hover and keyboard focus work without it.',
        'props' => [['tooltip_trigger($c, $for)', 'string', "''", '$for is the tooltip id, wires aria-describedby'], ['tooltip_content($c, $id)', 'string', "''", ''], ...$common],
        'php' => <<<'X'
            echo tooltip(raw(
                tooltip_trigger('Hover', 'tip', button_classes('outline')) .
                tooltip_content('Add to library', 'tip')
            ));
            X,
        'blade' => <<<'X'
            <x-ui.tooltip>
                <x-ui.tooltip.trigger for="tip" :class="button_classes('outline')">Hover</x-ui.tooltip.trigger>
                <x-ui.tooltip.content id="tip">Add to library</x-ui.tooltip.content>
            </x-ui.tooltip>
            X,
    ],
    'switch' => [
        'title' => 'Switch',
        'js' => 'None. A native checkbox with role=switch.',
        'props' => [['size', 'default|sm', "'default'", ''], ...$common],
        'php' => <<<'X'
            echo switch_(attrs: ['id' => 'air', 'name' => 'air', 'aria-label' => 'Airplane mode']);
            echo switch_(attrs: ['checked' => true, 'aria-label' => 'Wifi']);
            echo switch_('sm', attrs: ['checked' => true, 'aria-label' => 'Small']);
            echo switch_(attrs: ['disabled' => true, 'aria-label' => 'Disabled']);
            X,
        'blade' => <<<'X'
            <x-ui.switch id="air" name="air" aria-label="Airplane mode" />
            <x-ui.switch :checked="true" aria-label="Wifi" />
            <x-ui.switch size="sm" :checked="true" aria-label="Small" />
            <x-ui.switch :disabled="true" aria-label="Disabled" />
            X,
    ],
    'checkbox' => [
        'title' => 'Checkbox',
        'js' => 'None. A styled native checkbox.',
        'props' => $common,
        'php' => <<<'X'
            echo '<div class="flex items-center gap-3">' . checkbox(attrs: ['id' => 'terms', 'name' => 'terms']) . label('Accept terms', attrs: ['for' => 'terms']) . '</div>';
            echo checkbox(attrs: ['checked' => true, 'aria-label' => 'Checked']);
            echo checkbox(attrs: ['disabled' => true, 'aria-label' => 'Disabled']);
            X,
        'blade' => <<<'X'
            <div class="flex items-center gap-3"><x-ui.checkbox id="terms" name="terms" /><x-ui.label for="terms">Accept terms</x-ui.label></div>
            <x-ui.checkbox :checked="true" aria-label="Checked" />
            <x-ui.checkbox :disabled="true" aria-label="Disabled" />
            X,
    ],
    'select' => [
        'title' => 'Select',
        'js' => 'None. A styled native select.',
        'props' => [['class', 'string', "''", 'Goes on the wrapper, so w-full works'], ['size', 'default|sm', "'default'", ''], ['select_option($c, $value, $selected)', 'string, ?string, bool', "'', null, false", ''], ['select_optgroup($c, $label)', 'string', "''", ''], ...$common],
        'php' => <<<'X'
            echo select(raw(
                select_option('Select a fruit', '') .
                select_optgroup(raw(select_option('Apple', 'apple', true) . select_option('Banana', 'banana')), 'Fruits')
            ), attrs: ['name' => 'fruit', 'aria-label' => 'Fruit']);
            X,
        'blade' => <<<'X'
            <x-ui.select name="fruit" aria-label="Fruit">
                <x-ui.select.option value="">Select a fruit</x-ui.select.option>
                <x-ui.select.optgroup label="Fruits">
                    <x-ui.select.option value="apple" :selected="true">Apple</x-ui.select.option>
                    <x-ui.select.option value="banana">Banana</x-ui.select.option>
                </x-ui.select.optgroup>
            </x-ui.select>
            X,
    ],
    'tabs' => [
        'title' => 'Tabs',
        'js' => 'Needs tabs.js to switch without reloading. For zero JS pass attrs href on triggers and render the active tab on the server.',
        'props' => [['tabs_trigger($c, $value, $active)', 'string, string, bool', "required, required, false", '$value must be unique on the page'], ['tabs_content($c, $value, $active)', 'string, string, bool', "required, required, false", ''], ...$common],
        'php' => <<<'X'
            echo tabs(raw(
                tabs_list(raw(
                    tabs_trigger('Account', 'account', true) .
                    tabs_trigger('Password', 'password') .
                    tabs_trigger('Billing', 'billing')
                )) .
                tabs_content('Make changes to your account here.', 'account', true) .
                tabs_content('Change your password here.', 'password') .
                tabs_content('Manage your plan here.', 'billing')
            ), 'w-full max-w-96');
            X,
        'blade' => <<<'X'
            <x-ui.tabs class="w-full max-w-96">
                <x-ui.tabs.list>
                    <x-ui.tabs.trigger value="account" :active="true">Account</x-ui.tabs.trigger>
                    <x-ui.tabs.trigger value="password">Password</x-ui.tabs.trigger>
                    <x-ui.tabs.trigger value="billing">Billing</x-ui.tabs.trigger>
                </x-ui.tabs.list>
                <x-ui.tabs.content value="account" :active="true">Make changes to your account here.</x-ui.tabs.content>
                <x-ui.tabs.content value="password">Change your password here.</x-ui.tabs.content>
                <x-ui.tabs.content value="billing">Manage your plan here.</x-ui.tabs.content>
            </x-ui.tabs>
            X,
    ],
    'table' => [
        'title' => 'Table',
        'props' => [['c', 'string|Stringable', "''", 'Escaped unless raw(). Every sub-component takes the same three arguments'], ...$common],
        'php' => <<<'X'
            echo table(raw(
                table_caption('A list of your recent invoices.') .
                table_header(raw(table_row(raw(table_head('Invoice') . table_head('Status') . table_head('Amount', 'text-end!'))))) .
                table_body(raw(
                    table_row(raw(table_cell('INV001') . table_cell('Paid') . table_cell('$250.00', 'text-end'))) .
                    table_row(raw(table_cell('INV002') . table_cell('Pending') . table_cell('$150.00', 'text-end')))
                )) .
                table_footer(raw(table_row(raw(table_cell('Total', attrs: ['colspan' => 2]) . table_cell('$400.00', 'text-end')))))
            ));
            X,
        'blade' => <<<'X'
            <x-ui.table>
                <x-ui.table.caption>A list of your recent invoices.</x-ui.table.caption>
                <x-ui.table.header><x-ui.table.row><x-ui.table.head>Invoice</x-ui.table.head><x-ui.table.head>Status</x-ui.table.head><x-ui.table.head class="text-end!">Amount</x-ui.table.head></x-ui.table.row></x-ui.table.header>
                <x-ui.table.body>
                    <x-ui.table.row><x-ui.table.cell>INV001</x-ui.table.cell><x-ui.table.cell>Paid</x-ui.table.cell><x-ui.table.cell class="text-end">$250.00</x-ui.table.cell></x-ui.table.row>
                    <x-ui.table.row><x-ui.table.cell>INV002</x-ui.table.cell><x-ui.table.cell>Pending</x-ui.table.cell><x-ui.table.cell class="text-end">$150.00</x-ui.table.cell></x-ui.table.row>
                </x-ui.table.body>
                <x-ui.table.footer><x-ui.table.row><x-ui.table.cell colspan="2">Total</x-ui.table.cell><x-ui.table.cell class="text-end">$400.00</x-ui.table.cell></x-ui.table.row></x-ui.table.footer>
            </x-ui.table>
            X,
    ],
    'toast' => [
        'title' => 'Toast',
        'js' => 'Optional toast.js adds window.toast(message, variant, ms) and auto-dismiss. Server-rendered toasts and their close button work without it.',
        'props' => [['toast($c, $variant, $close)', "string, 'default'|'destructive', string", "'', 'default', 'Close'", '$close is the close button label'], ['toaster($c, $close)', 'string, string', "'', 'Close'", 'Put one on the page. $c holds server-rendered toasts'], ...$common],
        'php' => <<<'X'
            echo button('Show toast', 'outline', attrs: ['onclick' => "toast('Event has been created')"]);
            echo toaster();
            X,
        'blade' => <<<'X'
            <x-ui.button variant="outline" onclick="toast('Event has been created')">Show toast</x-ui.button>
            <x-ui.toast.toaster />
            X,
    ],
    'pagination' => [
        'title' => 'Pagination',
        'js' => 'None. Page numbers are plain links your server answers. This site has no server, so a docs-only script moves the highlight here.',
        'props' => [['pagination_link($c, $href, $active)', 'string, string, bool', "'', '#', false", ''], ['pagination_previous / pagination_next ($href, $label)', 'string', "'#', 'Previous' / 'Next'", 'Translate the label here'], ['pagination_ellipsis($label)', 'string', "'More pages'", ''], ...$common],
        'php' => <<<'X'
            echo pagination(raw(pagination_content(raw(
                pagination_item(raw(pagination_previous('?page=1'))) .
                pagination_item(raw(pagination_link('1', '?page=1'))) .
                pagination_item(raw(pagination_link('2', '?page=2', true))) .
                pagination_item(raw(pagination_ellipsis())) .
                pagination_item(raw(pagination_next('?page=3')))
            ))));
            X,
        'blade' => <<<'X'
            <x-ui.pagination>
                <x-ui.pagination.content>
                    <x-ui.pagination.item><x-ui.pagination.previous href="?page=1" /></x-ui.pagination.item>
                    <x-ui.pagination.item><x-ui.pagination.link href="?page=1">1</x-ui.pagination.link></x-ui.pagination.item>
                    <x-ui.pagination.item><x-ui.pagination.link href="?page=2" :active="true">2</x-ui.pagination.link></x-ui.pagination.item>
                    <x-ui.pagination.item><x-ui.pagination.ellipsis /></x-ui.pagination.item>
                    <x-ui.pagination.item><x-ui.pagination.next href="?page=3" /></x-ui.pagination.item>
                </x-ui.pagination.content>
            </x-ui.pagination>
            X,
    ],
    'skeleton' => [
        'title' => 'Skeleton',
        'props' => $common,
        'php' => <<<'X'
            echo skeleton('size-12 rounded-full');
            echo skeleton('h-4 w-64');
            X,
        'blade' => <<<'X'
            <x-ui.skeleton class="size-12 rounded-full" />
            <x-ui.skeleton class="h-4 w-64" />
            X,
    ],
    'avatar' => [
        'title' => 'Avatar',
        'props' => [['avatar($c, $size)', "string, 'default'|'sm'|'lg'", "'', 'default'", ''], ['avatar_image($src, $alt)', 'string', "required, ''", 'A failed load removes it, so the fallback shows'], ['avatar_fallback($c)', 'string', "''", ''], ...$common],
        'php' => <<<'X'
            echo avatar(raw(avatar_fallback('CN') . avatar_image('/missing.png', 'Portrait')));
            echo avatar(raw(avatar_fallback('LG')), 'lg');
            echo avatar(raw(avatar_fallback('SM')), 'sm');
            X,
        'blade' => <<<'X'
            <x-ui.avatar><x-ui.avatar.fallback>CN</x-ui.avatar.fallback><x-ui.avatar.image src="/missing.png" alt="Portrait" /></x-ui.avatar>
            <x-ui.avatar size="lg"><x-ui.avatar.fallback>LG</x-ui.avatar.fallback></x-ui.avatar>
            <x-ui.avatar size="sm"><x-ui.avatar.fallback>SM</x-ui.avatar.fallback></x-ui.avatar>
            X,
    ],
    'breadcrumb' => [
        'title' => 'Breadcrumb',
        'props' => [['breadcrumb_link($c, $href)', 'string', "'', '#'", ''], ['breadcrumb_separator($c)', '?string', 'null', 'null draws a chevron that flips in RTL'], ['breadcrumb_ellipsis($label)', 'string', "'More'", ''], ...$common],
        'php' => <<<'X'
            echo breadcrumb(raw(breadcrumb_list(raw(
                breadcrumb_item(raw(breadcrumb_link('Home', '/'))) .
                breadcrumb_separator() .
                breadcrumb_item(raw(breadcrumb_ellipsis())) .
                breadcrumb_separator() .
                breadcrumb_item(raw(breadcrumb_page('Breadcrumb')))
            ))));
            X,
        'blade' => <<<'X'
            <x-ui.breadcrumb>
                <x-ui.breadcrumb.list>
                    <x-ui.breadcrumb.item><x-ui.breadcrumb.link href="/">Home</x-ui.breadcrumb.link></x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.separator />
                    <x-ui.breadcrumb.item><x-ui.breadcrumb.ellipsis /></x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.separator />
                    <x-ui.breadcrumb.item><x-ui.breadcrumb.page>Breadcrumb</x-ui.breadcrumb.page></x-ui.breadcrumb.item>
                </x-ui.breadcrumb.list>
            </x-ui.breadcrumb>
            X,
    ],
    'form' => [
        'title' => 'Form',
        'props' => [['form_field($name, $label, $value, $error, $description, $type)', 'string', "required, required, '', '', '', 'text'", "Blade fills value from old() and error from \$errors. \$type 'textarea' renders a textarea"], ['form_item / form_label / form_description / form_message', 'string', "''", 'Build your own layout. form_message renders nothing when empty'], ...$common],
        'php' => <<<'X'
            echo form_field('email', 'Email', 'a@b', 'Enter a valid email.', 'We never share it.', 'email', 'w-full max-w-sm');
            echo form_field('bio', 'Bio', type: 'textarea', class: 'w-full max-w-sm');
            X,
        'blade' => <<<'X'
            <x-ui.form.field name="email" label="Email" value="a@b" error="Enter a valid email." description="We never share it." type="email" class="w-full max-w-sm" />
            <x-ui.form.field name="bio" label="Bio" type="textarea" class="w-full max-w-sm" />
            X,
    ],
    'aspect-ratio' => [
        'title' => 'Aspect Ratio',
        'props' => [['c', 'string|Stringable', "''", ''], ['ratio', 'string', "'16/9'", 'Any CSS aspect-ratio value'], ...$common],
        'php' => <<<'X'
            echo aspect_ratio(raw('<div class="flex size-full items-center justify-center rounded-lg bg-muted">16 / 9</div>'), '16/9', 'w-full max-w-sm');
            X,
        'blade' => <<<'X'
            <x-ui.aspect-ratio ratio="16/9" class="w-full max-w-sm"><div class="flex size-full items-center justify-center rounded-lg bg-muted">16 / 9</div></x-ui.aspect-ratio>
            X,
    ],
    'kbd' => [
        'title' => 'Kbd',
        'props' => [['c', 'string|Stringable', "''", ''], ...$common],
        'php' => <<<'X'
            echo kbd('⌘') . kbd('K');
            echo kbd_group(raw(kbd('Ctrl') . kbd('B')));
            X,
        'blade' => <<<'X'
            <x-ui.kbd>⌘</x-ui.kbd><x-ui.kbd>K</x-ui.kbd>
            <x-ui.kbd.group><x-ui.kbd>Ctrl</x-ui.kbd><x-ui.kbd>B</x-ui.kbd></x-ui.kbd.group>
            X,
    ],
    'spinner' => [
        'title' => 'Spinner',
        'props' => [['class', 'string', "''", 'Set the size here, for example size-8'], ['label', 'string', "'Loading'", 'Accessible name, translate it here'], ['attrs', 'array', '[]', '']],
        'php' => <<<'X'
            echo spinner();
            echo spinner('size-8');
            echo button(raw(spinner() . 'Saving'), attrs: ['disabled' => true]);
            X,
        'blade' => <<<'X'
            <x-ui.spinner />
            <x-ui.spinner class="size-8" />
            <x-ui.button :disabled="true"><x-ui.spinner />Saving</x-ui.button>
            X,
    ],
    'progress' => [
        'title' => 'Progress',
        'props' => [['value', '?int', 'null', 'null shows an indeterminate bar'], ['max', 'int', '100', ''], ...$common],
        'php' => <<<'X'
            echo progress(60, class: 'max-w-sm', attrs: ['aria-label' => 'Upload']);
            X,
        'blade' => <<<'X'
            <x-ui.progress value="60" class="max-w-sm" aria-label="Upload" />
            X,
    ],
    'slider' => [
        'title' => 'Slider',
        'props' => [['value', '?int', 'null', ''], ['min, max, step', 'int', '0, 100, 1', ''], ...$common],
        'php' => <<<'X'
            echo slider(33, class: 'max-w-sm', attrs: ['name' => 'volume', 'aria-label' => 'Volume']);
            X,
        'blade' => <<<'X'
            <x-ui.slider value="33" class="max-w-sm" name="volume" aria-label="Volume" />
            X,
    ],
    'radio-group' => [
        'title' => 'Radio Group',
        'props' => [['radio_group_item($name, $value, $checked)', 'string, string, bool', "required, '', false", 'Same $name = one choice. Arrow keys work natively'], ...$common],
        'php' => <<<'X'
            echo radio_group(raw(
                '<div class="flex items-center gap-3">' . radio_group_item('plan', 'free', true, attrs: ['id' => 'r1']) . label('Free', attrs: ['for' => 'r1']) . '</div>' .
                '<div class="flex items-center gap-3">' . radio_group_item('plan', 'pro', attrs: ['id' => 'r2']) . label('Pro', attrs: ['for' => 'r2']) . '</div>'
            ), attrs: ['aria-label' => 'Plan']);
            X,
        'blade' => <<<'X'
            <x-ui.radio-group aria-label="Plan">
                <div class="flex items-center gap-3"><x-ui.radio-group.item name="plan" value="free" :checked="true" id="r1" /><x-ui.label for="r1">Free</x-ui.label></div>
                <div class="flex items-center gap-3"><x-ui.radio-group.item name="plan" value="pro" id="r2" /><x-ui.label for="r2">Pro</x-ui.label></div>
            </x-ui.radio-group>
            X,
    ],
    'collapsible' => [
        'title' => 'Collapsible',
        'js' => 'None. Uses <details>.',
        'props' => [['collapsible($c, $open)', 'string, bool', "'', false", ''], ...$common],
        'php' => <<<'X'
            echo collapsible(raw(
                collapsible_trigger('Show details', button_classes('outline')) .
                collapsible_content('Everything inside stays in the page for search and screen readers.', 'mt-2 text-sm')
            ), class: 'w-full max-w-sm');
            X,
        'blade' => <<<'X'
            <x-ui.collapsible class="w-full max-w-sm">
                <x-ui.collapsible.trigger :class="button_classes('outline')">Show details</x-ui.collapsible.trigger>
                <x-ui.collapsible.content class="mt-2 text-sm">Everything inside stays in the page for search and screen readers.</x-ui.collapsible.content>
            </x-ui.collapsible>
            X,
    ],
    'scroll-area' => [
        'title' => 'Scroll Area',
        'props' => $common,
        'php' => <<<'X'
            echo scroll_area(raw(implode('', array_map(fn($i) => "<p class=\"py-1 text-sm\">Tag $i</p>", range(1, 20)))), 'h-48 w-48 rounded-md border p-4', ['aria-label' => 'Tags']);
            X,
        'blade' => <<<'X'
            <x-ui.scroll-area class="h-48 w-48 rounded-md border p-4" aria-label="Tags">@foreach (range(1, 20) as $i)<p class="py-1 text-sm">Tag {{ $i }}</p>@endforeach</x-ui.scroll-area>
            X,
    ],
    'toggle' => [
        'title' => 'Toggle',
        'js' => 'None. A native checkbox in a label, so it also submits with forms.',
        'props' => [['pressed', 'bool', 'false', ''], ['variant', 'default|outline', "'default'", ''], ['size', 'default|sm|lg', "'default'", ''], ['class', 'string', "''", 'Goes on the label'], ['attrs', 'array', '[]', 'Goes on the input: name, value, aria-label']],
        'php' => <<<'X'
            echo toggle('B', attrs: ['name' => 'bold', 'aria-label' => 'Bold']);
            echo toggle('I', true, 'outline', attrs: ['name' => 'italic', 'aria-label' => 'Italic']);
            echo toggle('U', size: 'sm', attrs: ['name' => 'underline', 'aria-label' => 'Underline']);
            X,
        'blade' => <<<'X'
            <x-ui.toggle name="bold" aria-label="Bold">B</x-ui.toggle>
            <x-ui.toggle :pressed="true" variant="outline" name="italic" aria-label="Italic">I</x-ui.toggle>
            <x-ui.toggle size="sm" name="underline" aria-label="Underline">U</x-ui.toggle>
            X,
    ],
    'toggle-group' => [
        'title' => 'Toggle Group',
        'js' => 'None. Native radios or checkboxes, so arrow keys work with radios.',
        'props' => [['toggle_group_item($c, $name, $value, $checked, $type, $variant, $size)', "string, string, string, bool, 'radio'|'checkbox', string, string", "required, required, '', false, 'radio', 'default', 'default'", "'radio' = one on, 'checkbox' = many on"], ...$common],
        'php' => <<<'X'
            echo toggle_group(raw(
                toggle_group_item('Left', 'align', 'left', true, variant: 'outline') .
                toggle_group_item('Center', 'align', 'center', variant: 'outline') .
                toggle_group_item('Right', 'align', 'right', variant: 'outline')
            ), attrs: ['aria-label' => 'Align']);
            X,
        'blade' => <<<'X'
            <x-ui.toggle-group aria-label="Align">
                <x-ui.toggle-group.item name="align" value="left" :checked="true" variant="outline">Left</x-ui.toggle-group.item>
                <x-ui.toggle-group.item name="align" value="center" variant="outline">Center</x-ui.toggle-group.item>
                <x-ui.toggle-group.item name="align" value="right" variant="outline">Right</x-ui.toggle-group.item>
            </x-ui.toggle-group>
            X,
    ],
    'button-group' => [
        'title' => 'Button Group',
        'props' => [['orientation', 'horizontal|vertical', "'horizontal'", ''], ...$common],
        'php' => <<<'X'
            echo button_group(raw(button('Archive', 'outline') . button('Report', 'outline') . button('Snooze', 'outline')));
            echo button_group(raw(button('Copy', 'secondary') . button_group_separator() . button('Paste', 'secondary')));
            echo button_group(raw(button_group_text('https://') . input('', ['aria-label' => 'Address'])));
            X,
        'blade' => <<<'X'
            <x-ui.button-group><x-ui.button variant="outline">Archive</x-ui.button><x-ui.button variant="outline">Report</x-ui.button><x-ui.button variant="outline">Snooze</x-ui.button></x-ui.button-group>
            <x-ui.button-group><x-ui.button variant="secondary">Copy</x-ui.button><x-ui.button-group.separator /><x-ui.button variant="secondary">Paste</x-ui.button></x-ui.button-group>
            <x-ui.button-group><x-ui.button-group.text>https://</x-ui.button-group.text><x-ui.input aria-label="Address" /></x-ui.button-group>
            X,
    ],
    'empty' => [
        'title' => 'Empty',
        'props' => [['empty_media($c, $variant)', "string, 'default'|'icon'", "'', 'default'", ''], ...$common],
        'php' => <<<'X'
            echo empty_(raw(
                empty_header(raw(
                    empty_media(raw(icon('M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z')), 'icon') .
                    empty_title('No projects yet') .
                    empty_description('Create your first project to get started.')
                )) .
                empty_content(raw(button('Create project')))
            ), 'border');
            X,
        'blade' => <<<'X'
            <x-ui.empty class="border">
                <x-ui.empty.header>
                    <x-ui.empty.media variant="icon">{!! icon('M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z') !!}</x-ui.empty.media>
                    <x-ui.empty.title>No projects yet</x-ui.empty.title>
                    <x-ui.empty.description>Create your first project to get started.</x-ui.empty.description>
                </x-ui.empty.header>
                <x-ui.empty.content><x-ui.button>Create project</x-ui.button></x-ui.empty.content>
            </x-ui.empty>
            X,
    ],
    'item' => [
        'title' => 'Item',
        'props' => [['item($c, $variant, $size)', "string, 'default'|'outline'|'muted', 'default'|'sm'", "'', 'default', 'default'", 'attrs href renders a link'], ['item_media($c, $variant)', "string, 'default'|'icon'|'image'", "'', 'default'", ''], ...$common],
        'php' => <<<'X'
            echo item(raw(
                item_media(raw(icon('M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0')), 'icon') .
                item_content(raw(item_title('Security alert') . item_description('New login from an unknown device.'))) .
                item_actions(raw(button('Review', 'outline', 'sm')))
            ), 'outline', class: 'w-full max-w-md');
            echo item('A plain link row', 'muted', 'sm', 'w-full max-w-md', ['href' => '#']);
            X,
        'blade' => <<<'X'
            <x-ui.item variant="outline" class="w-full max-w-md">
                <x-ui.item.media variant="icon">{!! icon('M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0') !!}</x-ui.item.media>
                <x-ui.item.content><x-ui.item.title>Security alert</x-ui.item.title><x-ui.item.description>New login from an unknown device.</x-ui.item.description></x-ui.item.content>
                <x-ui.item.actions><x-ui.button variant="outline" size="sm">Review</x-ui.button></x-ui.item.actions>
            </x-ui.item>
            <x-ui.item variant="muted" size="sm" class="w-full max-w-md" href="#">A plain link row</x-ui.item>
            X,
    ],
    'typography' => [
        'title' => 'Typography',
        'props' => [['as', 'h1|h2|h3|h4|p|blockquote|ul|code|lead|large|small|muted', "'p'", 'One function, one table of styles'], ['c', 'string|Stringable', "''", ''], ...$common],
        'php' => <<<'X'
            echo '<div class="w-full">';
            echo typography('h1', 'The Joke Tax');
            echo typography('lead', 'A modal dialog that interrupts the user.');
            echo typography('p', 'The king thought long and hard, and finally came up with a brilliant plan.');
            echo typography('blockquote', 'After all, everyone enjoys a good joke.');
            echo typography('ul', raw('<li>First level</li><li>Second level</li>'));
            echo typography('code', 'php vui add typography');
            echo '</div>';
            X,
        'blade' => <<<'X'
            <div class="w-full">
                <x-ui.typography as="h1">The Joke Tax</x-ui.typography>
                <x-ui.typography as="lead">A modal dialog that interrupts the user.</x-ui.typography>
                <x-ui.typography as="p">The king thought long and hard, and finally came up with a brilliant plan.</x-ui.typography>
                <x-ui.typography as="blockquote">After all, everyone enjoys a good joke.</x-ui.typography>
                <x-ui.typography as="ul"><li>First level</li><li>Second level</li></x-ui.typography>
                <x-ui.typography as="code">php vui add typography</x-ui.typography>
            </div>
            X,
    ],
    'field' => [
        'title' => 'Field',
        'props' => [['field($c, $orientation, $invalid)', "string, 'vertical'|'horizontal', bool", "'', 'vertical', false", ''], ['field_legend($c, $variant)', "string, 'legend'|'label'", "'', 'legend'", ''], ['field_error($c)', 'string', "''", 'Renders nothing when empty'], ...$common],
        'php' => <<<'X'
            echo field_set(raw(
                field_legend('Profile') .
                field_group(raw(
                    field(raw(field_label('Name', attrs: ['for' => 'f-name']) . input('', ['id' => 'f-name']) . field_description('Your public name.'))) .
                    field(raw(field_label('Email', attrs: ['for' => 'f-mail']) . input('', ['id' => 'f-mail', 'aria-invalid' => 'true']) . field_error('Enter a valid email.')), invalid: true) .
                    field(raw(checkbox(attrs: ['id' => 'f-news']) . field_label('Email me news', attrs: ['for' => 'f-news'])), 'horizontal')
                ))
            ), 'w-full max-w-sm');
            X,
        'blade' => <<<'X'
            <x-ui.field.set class="w-full max-w-sm">
                <x-ui.field.legend>Profile</x-ui.field.legend>
                <x-ui.field.group>
                    <x-ui.field><x-ui.field.label for="f-name">Name</x-ui.field.label><x-ui.input id="f-name" /><x-ui.field.description>Your public name.</x-ui.field.description></x-ui.field>
                    <x-ui.field :invalid="true"><x-ui.field.label for="f-mail">Email</x-ui.field.label><x-ui.input id="f-mail" aria-invalid="true" /><x-ui.field.error>Enter a valid email.</x-ui.field.error></x-ui.field>
                    <x-ui.field orientation="horizontal"><x-ui.checkbox id="f-news" /><x-ui.field.label for="f-news">Email me news</x-ui.field.label></x-ui.field>
                </x-ui.field.group>
            </x-ui.field.set>
            X,
    ],
    'input-group' => [
        'title' => 'Input Group',
        'props' => [['input_group_addon($c, $align)', "string, 'inline-start'|'inline-end'", "'', 'inline-start'", ''], ['input_group_button($c, $variant)', 'string, string', "'', 'ghost'", ''], ...$common],
        'php' => <<<'X'
            echo input_group(raw(
                input_group_input(attrs: ['placeholder' => 'Search', 'aria-label' => 'Search']) .
                input_group_addon(raw(icon('m21 21-4.34-4.34M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0'))) .
                input_group_addon(raw(input_group_button('Go')), 'inline-end')
            ), 'max-w-sm');
            X,
        'blade' => <<<'X'
            <x-ui.input-group class="max-w-sm">
                <x-ui.input-group.input placeholder="Search" aria-label="Search" />
                <x-ui.input-group.addon>{!! icon('m21 21-4.34-4.34M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0') !!}</x-ui.input-group.addon>
                <x-ui.input-group.addon align="inline-end"><x-ui.input-group.button>Go</x-ui.input-group.button></x-ui.input-group.addon>
            </x-ui.input-group>
            X,
    ],
    'input-otp' => [
        'title' => 'Input OTP',
        'js' => 'None. One native input drawn as boxes, so paste, delete, autofill and SMS suggestions work.',
        'props' => [['length', 'int', '6', ''], ['class', 'string', "''", 'Goes on the wrapper'], ['attrs', 'array', '[]', 'Goes on the input: name, aria-label, inputmode']],
        'php' => <<<'X'
            echo input_otp(6, attrs: ['name' => 'code', 'aria-label' => 'One-time code']);
            X,
        'blade' => <<<'X'
            <x-ui.input-otp :length="6" name="code" aria-label="One-time code" />
            X,
    ],
    'combobox' => [
        'title' => 'Combobox',
        'js' => 'None. Uses <input list> and <datalist>. The popup is drawn by the browser.',
        'props' => [['combobox($c, $id)', 'string, string', "'', ''", ''], ['combobox_option($value, $c)', 'string, string', "'', ''", ''], ...$common],
        'php' => <<<'X'
            echo combobox(raw(combobox_option('Laravel') . combobox_option('Symfony') . combobox_option('Slim')), 'framework', 'max-w-xs', ['name' => 'framework', 'placeholder' => 'Select framework', 'aria-label' => 'Framework']);
            X,
        'blade' => <<<'X'
            <x-ui.combobox id="framework" class="max-w-xs" name="framework" placeholder="Select framework" aria-label="Framework">
                <x-ui.combobox.option value="Laravel" />
                <x-ui.combobox.option value="Symfony" />
                <x-ui.combobox.option value="Slim" />
            </x-ui.combobox>
            X,
    ],
    'date-picker' => [
        'title' => 'Date Picker',
        'js' => 'Needs date-picker.js to open the vui calendar. Without it the native date input stays, so the field always works. Gregorian only.',
        'props' => [['class', 'string', "''", 'Goes on the wrapper'], ['attrs', 'array', '[]', 'Goes on the input: name, value, min, max, required, disabled'], ['placeholder', 'string', "'Pick a date'", ''], ['week_start', 'int', '0', '0 Sunday, 1 Monday, 6 Saturday'], ['labels', 'array', '[]', 'Keys: weekdays (space separated), prev, next. The month name follows the page language']],
        'php' => <<<'X'
            echo date_picker(attrs: ['name' => 'date', 'value' => '2026-11-07', 'aria-label' => 'Date']);
            X,
        'blade' => <<<'X'
            <x-ui.date-picker name="date" value="2026-11-07" aria-label="Date" />
            X,
    ],
    'alert-dialog' => [
        'title' => 'Alert Dialog',
        'js' => 'None. Uses <dialog>. Esc cancels, an outside click does not. Action closes the dialog; to submit data, put your own form and button in the footer.',
        'props' => [['alert_dialog_trigger($c, $for)', 'string', 'required', '$for is the dialog id'], ['alert_dialog_content($c, $id)', 'string', 'required', ''], ['alert_dialog_title / alert_dialog_description ($c, $for)', 'string', "''", 'Pass the dialog id to wire ARIA'], ...$common],
        'php' => <<<'X'
            echo alert_dialog_trigger('Delete account', 'sure', button_classes('destructive'));
            echo alert_dialog_content(raw(
                alert_dialog_header(raw(
                    alert_dialog_title('Are you absolutely sure?', 'sure') .
                    alert_dialog_description('This action cannot be undone.', 'sure')
                )) .
                alert_dialog_footer(raw(alert_dialog_cancel('Cancel') . alert_dialog_action('Continue')))
            ), 'sure');
            X,
        'blade' => <<<'X'
            <x-ui.alert-dialog.trigger for="sure" :class="button_classes('destructive')">Delete account</x-ui.alert-dialog.trigger>
            <x-ui.alert-dialog.content id="sure">
                <x-ui.alert-dialog.header>
                    <x-ui.alert-dialog.title for="sure">Are you absolutely sure?</x-ui.alert-dialog.title>
                    <x-ui.alert-dialog.description for="sure">This action cannot be undone.</x-ui.alert-dialog.description>
                </x-ui.alert-dialog.header>
                <x-ui.alert-dialog.footer><x-ui.alert-dialog.cancel>Cancel</x-ui.alert-dialog.cancel><x-ui.alert-dialog.action>Continue</x-ui.alert-dialog.action></x-ui.alert-dialog.footer>
            </x-ui.alert-dialog.content>
            X,
    ],
    'sheet' => [
        'title' => 'Sheet',
        'js' => 'None. Uses <dialog>. The slide comes from motion.css.',
        'props' => [['sheet_content($c, $id, $side, $close)', "string, string, 'end'|'start'|'top'|'bottom', string|false", "required, required, 'end', 'Close'", 'Sides are logical: end is on the left in RTL'], ['sheet_trigger($c, $for)', 'string', 'required', 'Close buttons use dialog_close, which comes with it'], ...$common],
        'php' => <<<'X'
            echo sheet_trigger('Open end', 'sh1', button_classes('outline'));
            echo sheet_content(raw(
                sheet_header(raw(sheet_title('Edit profile', 'sh1') . sheet_description('Make changes here.', 'sh1'))) .
                sheet_footer(raw(dialog_close('Done', button_classes())))
            ), 'sh1');
            echo sheet_trigger('Open top', 'sh2', button_classes('outline'));
            echo sheet_content(raw(sheet_header(raw(sheet_title('Notice', 'sh2') . sheet_description('From the top.', 'sh2')))), 'sh2', 'top');
            X,
        'blade' => <<<'X'
            <x-ui.sheet.trigger for="sh1" :class="button_classes('outline')">Open end</x-ui.sheet.trigger>
            <x-ui.sheet.content id="sh1">
                <x-ui.sheet.header><x-ui.sheet.title for="sh1">Edit profile</x-ui.sheet.title><x-ui.sheet.description for="sh1">Make changes here.</x-ui.sheet.description></x-ui.sheet.header>
                <x-ui.sheet.footer><x-ui.dialog.close :class="button_classes()">Done</x-ui.dialog.close></x-ui.sheet.footer>
            </x-ui.sheet.content>
            <x-ui.sheet.trigger for="sh2" :class="button_classes('outline')">Open top</x-ui.sheet.trigger>
            <x-ui.sheet.content id="sh2" side="top">
                <x-ui.sheet.header><x-ui.sheet.title for="sh2">Notice</x-ui.sheet.title><x-ui.sheet.description for="sh2">From the top.</x-ui.sheet.description></x-ui.sheet.header>
            </x-ui.sheet.content>
            X,
    ],
    'drawer' => [
        'title' => 'Drawer',
        'js' => 'None. Uses <dialog>. No drag to dismiss: Esc, an outside click or a close button.',
        'props' => [['drawer_content($c, $id)', 'string', 'required', 'Bottom only. Use sheet for the other sides'], ['drawer_trigger($c, $for)', 'string', 'required', ''], ...$common],
        'php' => <<<'X'
            echo drawer_trigger('Open drawer', 'dr', button_classes('outline'));
            echo drawer_content(raw(
                drawer_header(raw(drawer_title('Move goal', 'dr') . drawer_description('Set your daily activity goal.', 'dr'))) .
                drawer_footer(raw(button('Submit') . drawer_close('Cancel', button_classes('outline'))))
            ), 'dr');
            X,
        'blade' => <<<'X'
            <x-ui.drawer.trigger for="dr" :class="button_classes('outline')">Open drawer</x-ui.drawer.trigger>
            <x-ui.drawer.content id="dr">
                <x-ui.drawer.header><x-ui.drawer.title for="dr">Move goal</x-ui.drawer.title><x-ui.drawer.description for="dr">Set your daily activity goal.</x-ui.drawer.description></x-ui.drawer.header>
                <x-ui.drawer.footer><x-ui.button>Submit</x-ui.button><x-ui.drawer.close :class="button_classes('outline')">Cancel</x-ui.drawer.close></x-ui.drawer.footer>
            </x-ui.drawer.content>
            X,
    ],
    'hover-card' => [
        'title' => 'Hover Card',
        'js' => 'None. CSS hover and keyboard focus, positioned by motion.css. It cannot be dismissed with Esc.',
        'props' => [['hover_card_trigger($c, $href)', 'string', "'', '#'", 'A link, so keyboard focus works'], ...$common],
        'php' => <<<'X'
            echo hover_card(raw(
                hover_card_trigger('@nextjs', '#', button_classes('link')) .
                hover_card_content('The React framework, created and maintained by Vercel.')
            ));
            X,
        'blade' => <<<'X'
            <x-ui.hover-card>
                <x-ui.hover-card.trigger href="#" :class="button_classes('link')">@@nextjs</x-ui.hover-card.trigger>
                <x-ui.hover-card.content>The React framework, created and maintained by Vercel.</x-ui.hover-card.content>
            </x-ui.hover-card>
            X,
    ],
    'navigation-menu' => [
        'title' => 'Navigation Menu',
        'js' => 'None. Panels use the popover attribute, so they open on click rather than hover.',
        'props' => [['navigation_menu_trigger($c, $for)', 'string', 'required', '$for is the panel id'], ['navigation_menu_content($c, $id)', 'string', 'required', ''], ['navigation_menu_link($c, $href)', 'string', "'', '#'", "attrs aria-current 'page' marks the active link. Style a top-level link with button_classes('ghost')"], ...$common],
        'php' => <<<'X'
            echo navigation_menu(raw(
                navigation_menu_item(raw(
                    navigation_menu_trigger('Learn', 'nm') .
                    navigation_menu_content(raw(
                        navigation_menu_link('Introduction', '#', attrs: ['aria-current' => 'page']) .
                        navigation_menu_link('Installation', '#')
                    ), 'nm', 'w-56')
                )) .
                navigation_menu_item(raw(navigation_menu_link('Docs', '#', button_classes('ghost'))))
            ));
            X,
        'blade' => <<<'X'
            <x-ui.navigation-menu>
                <x-ui.navigation-menu.item>
                    <x-ui.navigation-menu.trigger for="nm">Learn</x-ui.navigation-menu.trigger>
                    <x-ui.navigation-menu.content id="nm" class="w-56">
                        <x-ui.navigation-menu.link href="#" aria-current="page">Introduction</x-ui.navigation-menu.link>
                        <x-ui.navigation-menu.link href="#">Installation</x-ui.navigation-menu.link>
                    </x-ui.navigation-menu.content>
                </x-ui.navigation-menu.item>
                <x-ui.navigation-menu.item><x-ui.navigation-menu.link href="#" :class="button_classes('ghost')">Docs</x-ui.navigation-menu.link></x-ui.navigation-menu.item>
            </x-ui.navigation-menu>
            X,
    ],
    'menubar' => [
        'title' => 'Menubar',
        'js' => 'Optional menubar.js plus dropdown-menu.js add menubar roles and arrow keys. Tab, Esc and click work without them.',
        'props' => [['menubar_trigger($c, $for)', 'string', 'required', '$for is the menu id'], ['menubar_content($c, $id)', 'string', 'required', ''], ['menubar_item($c, $for, $variant)', 'string', "'', '', 'default'", 'Same as dropdown_menu_item'], ...$common],
        'php' => <<<'X'
            echo menubar(raw(
                menubar_trigger('File', 'mb-file') .
                menubar_content(raw(menubar_item(raw('New tab' . menubar_shortcut('⌘T')), 'mb-file') . menubar_separator() . menubar_item('Print', 'mb-file')), 'mb-file') .
                menubar_trigger('Edit', 'mb-edit') .
                menubar_content(raw(menubar_label('History') . menubar_item('Undo', 'mb-edit') . menubar_item('Redo', 'mb-edit')), 'mb-edit')
            ));
            X,
        'blade' => <<<'X'
            <x-ui.menubar>
                <x-ui.menubar.trigger for="mb-file">File</x-ui.menubar.trigger>
                <x-ui.menubar.content id="mb-file">
                    <x-ui.menubar.item for="mb-file">New tab<x-ui.menubar.shortcut>⌘T</x-ui.menubar.shortcut></x-ui.menubar.item>
                    <x-ui.menubar.separator />
                    <x-ui.menubar.item for="mb-file">Print</x-ui.menubar.item>
                </x-ui.menubar.content>
                <x-ui.menubar.trigger for="mb-edit">Edit</x-ui.menubar.trigger>
                <x-ui.menubar.content id="mb-edit">
                    <x-ui.menubar.label>History</x-ui.menubar.label>
                    <x-ui.menubar.item for="mb-edit">Undo</x-ui.menubar.item>
                    <x-ui.menubar.item for="mb-edit">Redo</x-ui.menubar.item>
                </x-ui.menubar.content>
            </x-ui.menubar>
            X,
    ],
    'context-menu' => [
        'title' => 'Context Menu',
        'js' => 'Needs context-menu.js to open at the pointer. Without it the browser\'s own menu shows. Add dropdown-menu.js for arrow keys.',
        'props' => [['context_menu_trigger($c, $for)', 'string', "'', ''", '$for is the menu id'], ['context_menu_content($c, $id)', 'string', 'required', ''], ['context_menu_item($c, $for, $variant)', 'string', "'', '', 'default'", 'Same as dropdown_menu_item'], ...$common],
        'php' => <<<'X'
            echo context_menu_trigger('Right click or long press here', 'cm', 'flex h-36 w-72 items-center justify-center rounded-md border border-dashed text-sm');
            echo context_menu_content(raw(
                context_menu_item(raw('Back' . context_menu_shortcut('⌘[')), 'cm') .
                context_menu_item('Reload', 'cm') .
                context_menu_separator() .
                context_menu_item('Delete', 'cm', 'destructive')
            ), 'cm', 'w-52');
            X,
        'blade' => <<<'X'
            <x-ui.context-menu.trigger for="cm" class="flex h-36 w-72 items-center justify-center rounded-md border border-dashed text-sm">Right click or long press here</x-ui.context-menu.trigger>
            <x-ui.context-menu.content id="cm" class="w-52">
                <x-ui.context-menu.item for="cm">Back<x-ui.context-menu.shortcut>⌘[</x-ui.context-menu.shortcut></x-ui.context-menu.item>
                <x-ui.context-menu.item for="cm">Reload</x-ui.context-menu.item>
                <x-ui.context-menu.separator />
                <x-ui.context-menu.item for="cm" variant="destructive">Delete</x-ui.context-menu.item>
            </x-ui.context-menu.content>
            X,
    ],
    'calendar' => [
        'title' => 'Calendar',
        'js' => 'None. Rendered on the server; days and arrows are links. Gregorian only. Use $week_start 6 and $labels for Persian text. This site has no server, so a docs-only script redraws the month here.',
        'props' => [['month', 'string', 'required', "'2025-02'"], ['selected', 'string', "''", "'2025-02-14'"], ['href', 'string', "''", 'Day link, {date} is filled in. Empty renders plain text'], ['nav', 'string', "''", 'Arrow link, {month} is filled in. Empty hides the arrows'], ['week_start', 'int', '0', '0 Sunday, 1 Monday, 6 Saturday'], ['labels', 'array', '[]', 'Keys: title, weekdays (space separated), prev, next'], ...$common],
        'php' => <<<'X'
            echo calendar('2025-02', '2025-02-14', '?date={date}', '?month={month}', class: 'border');
            X,
        'blade' => <<<'X'
            <x-ui.calendar month="2025-02" selected="2025-02-14" href="?date={date}" nav="?month={month}" class="border" />
            X,
    ],
    'command' => [
        'title' => 'Command',
        'js' => 'Optional command.js adds filtering, Up/Down, Enter and listbox roles. Without it the list is plain links and buttons you can Tab through.',
        'props' => [['command_group($c, $heading)', 'string', "'', ''", ''], ['command_item($c)', 'string', "''", 'attrs href makes it a link'], ['command_empty($c)', 'string', "'No results found.'", 'Shown when the filter matches nothing'], ...$common],
        'php' => <<<'X'
            echo command(raw(
                command_input(attrs: ['placeholder' => 'Type a command or search', 'aria-label' => 'Command']) .
                command_list(raw(
                    command_empty() .
                    command_group(raw(command_item('Calendar') . command_item('Search emoji')), 'Suggestions') .
                    separator() .
                    command_group(raw(command_item(raw('Profile' . kbd('⌘P', 'ms-auto'))) . command_item('Settings', attrs: ['href' => '#'])), 'Settings')
                ))
            ), 'w-full max-w-md rounded-lg border shadow-md');
            X,
        'blade' => <<<'X'
            <x-ui.command class="w-full max-w-md rounded-lg border shadow-md">
                <x-ui.command.input placeholder="Type a command or search" aria-label="Command" />
                <x-ui.command.list>
                    <x-ui.command.empty>No results found.</x-ui.command.empty>
                    <x-ui.command.group heading="Suggestions"><x-ui.command.item>Calendar</x-ui.command.item><x-ui.command.item>Search emoji</x-ui.command.item></x-ui.command.group>
                    <x-ui.separator />
                    <x-ui.command.group heading="Settings"><x-ui.command.item>Profile<x-ui.kbd class="ms-auto">⌘P</x-ui.kbd></x-ui.command.item><x-ui.command.item href="#">Settings</x-ui.command.item></x-ui.command.group>
                </x-ui.command.list>
            </x-ui.command>
            X,
    ],
    'carousel' => [
        'title' => 'Carousel',
        'js' => 'Optional carousel.js shows the arrows. Swipe, scroll and arrow keys work without it, because it is CSS scroll-snap.',
        'props' => [['carousel_previous / carousel_next ($label)', 'string', "'Previous slide' / 'Next slide'", 'Translate here'], ['carousel_item', 'class', 'basis-full', "Use class basis-1/2! for two per view"], ...$common],
        'php' => <<<'X'
            echo carousel(raw(
                carousel_content(raw(implode('', array_map(fn($i) => carousel_item(raw(card(raw(card_content((string) $i, 'flex aspect-square items-center justify-center text-4xl font-semibold'))))), range(1, 4))))) .
                carousel_previous() .
                carousel_next()
            ), 'mx-12 w-[calc(100%-6rem)] max-w-xs');
            X,
        'blade' => <<<'X'
            <x-ui.carousel class="mx-12 w-[calc(100%-6rem)] max-w-xs">
                <x-ui.carousel.content>
                    @foreach (range(1, 4) as $i)
                        <x-ui.carousel.item><x-ui.card><x-ui.card.content class="flex aspect-square items-center justify-center text-4xl font-semibold">{{ $i }}</x-ui.card.content></x-ui.card></x-ui.carousel.item>
                    @endforeach
                </x-ui.carousel.content>
                <x-ui.carousel.previous />
                <x-ui.carousel.next />
            </x-ui.carousel>
            X,
    ],
    'sidebar' => [
        'title' => 'Sidebar',
        'js' => 'None. It is a popover on phones and a column from md up. Needs the sidebar-theme import, which the CLI prints.',
        'props' => [['sidebar($c, $id)', 'string', "'', 'sidebar'", ''], ['sidebar_trigger($label, $for)', 'string', "'Toggle Sidebar', 'sidebar'", 'Only shows below md'], ['sidebar_menu_button($c, $href, $active)', 'string, string, bool', "'', '#', false", 'Renders the li and the link'], ...$common],
        'php' => <<<'X'
            echo sidebar_provider(raw(
                sidebar(raw(
                    sidebar_header('Acme Inc', 'font-semibold') .
                    sidebar_content(raw(sidebar_group(raw(
                        sidebar_group_label('Application') .
                        sidebar_menu(raw(sidebar_menu_button('Home', '#', true) . sidebar_menu_button('Inbox') . sidebar_menu_button('Settings')))
                    )))) .
                    sidebar_footer('v1.0', 'text-xs text-sidebar-foreground/70')
                ), 'sb', 'h-80') .
                sidebar_inset(raw('<div class="flex items-center gap-2 p-4 text-sm">' . sidebar_trigger(for: 'sb') . 'Page content</div>'))
            ), 'h-80 min-h-0! overflow-hidden rounded-lg border');
            X,
        'blade' => <<<'X'
            <x-ui.sidebar.provider class="h-80 min-h-0! overflow-hidden rounded-lg border">
                <x-ui.sidebar id="sb" class="h-80">
                    <x-ui.sidebar.header class="font-semibold">Acme Inc</x-ui.sidebar.header>
                    <x-ui.sidebar.content>
                        <x-ui.sidebar.group>
                            <x-ui.sidebar.group-label>Application</x-ui.sidebar.group-label>
                            <x-ui.sidebar.menu>
                                <x-ui.sidebar.menu-button href="#" :active="true">Home</x-ui.sidebar.menu-button>
                                <x-ui.sidebar.menu-button>Inbox</x-ui.sidebar.menu-button>
                                <x-ui.sidebar.menu-button>Settings</x-ui.sidebar.menu-button>
                            </x-ui.sidebar.menu>
                        </x-ui.sidebar.group>
                    </x-ui.sidebar.content>
                    <x-ui.sidebar.footer class="text-xs text-sidebar-foreground/70">v1.0</x-ui.sidebar.footer>
                </x-ui.sidebar>
                <x-ui.sidebar.inset><div class="flex items-center gap-2 p-4 text-sm"><x-ui.sidebar.trigger for="sb" />Page content</div></x-ui.sidebar.inset>
            </x-ui.sidebar.provider>
            X,
    ],
];
