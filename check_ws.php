<?php
$files = glob("app/Http/Controllers/Owner/*.php");
$files[] = "routes/web.php";
foreach ($files as $f) {
    $c = file_get_contents($f);
    if (!str_starts_with($c, "<?php")) {
        echo "Whitespace found in $f\n";
        echo "First characters: " . bin2hex(substr($c, 0, 10)) . "\n";
    }
}
echo "Check done.\n";
