<?php
// Quick connectivity check to exchange rate APIs
function fetch($url) {
    $ctx = stream_context_create([
        'http' => ['timeout' => 8],
        'https' => ['timeout' => 8],
    ]);
    $r = @file_get_contents($url, false, $ctx);
    if ($r === false) {
        echo "FAILED: $url\n";
        return;
    }
    echo "OK: $url\n";
    echo substr($r, 0, 200) . "\n\n";
}

fetch('https://api.exchangerate.host/latest?base=USD&symbols=EGP,JOD,ILS,EUR');
fetch('https://open.er-api.com/v6/latest/USD');

