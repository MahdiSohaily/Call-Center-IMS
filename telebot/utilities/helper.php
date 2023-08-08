<?php

function compareById($a, $b)
{
    return   $a['id'] - $b['id'];
}

function filterCode($elementValue)
{
    if (empty($elementValue)) {
        return '';
    }

    $codes = explode("\n", $elementValue);

    $filteredCodes = array_map(function ($code) {
        $code = preg_replace('/\[[^\]]*\]/', '', $code);
        $parts = preg_split('/[:,]/', $code, 2);
        $rightSide = trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $parts[1] ?? ''));
        return !empty($rightSide) ? $rightSide : trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $code));
    }, array_filter($codes, 'trim'));

    $finalCodes = array_filter($filteredCodes, function ($item) {
        return strlen(explode(' ', $item)[0]) > 6 && !preg_match('/[a-zA-Z]{4,}/', $item);
    });

    return implode("\n", array_map(function ($item) {
        return explode(' ', $item)[0];
    }, $finalCodes)) . "\n";
}
