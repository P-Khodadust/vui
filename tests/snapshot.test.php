<?php
// One snapshot per component example. docs/examples.php is the single source for tests and docs.
foreach ($examples as $name => $ex) {
    t("snapshot: $name", function () use ($name, $ex, $update) {
        $file = __DIR__ . "/snapshots/$name.html";
        $html = render($ex['php']);
        if ($update) file_put_contents($file, $html);
        ok(is_file($file), "no snapshot, run: php tests/run.php --update");
        eq($html, file_get_contents($file));
    });
}
