<?php
function str_title_case($str)
{
    $str = snake_case($str);
    $str = str_replace('_', ' ', $str);
    $str = title_case($str);
    return $str;
}

function str_title_from_array($ar, $key = null)
{
    if (!is_array($ar)) {
        return str_title_case($ar);
    }

    if (isset($ar['title'])) {
        return $ar['title'];
    }

    return str_title_case($key);
}

function str_key_from_array($ar, $key = null)
{
    if (is_array($ar)) {
        return $key;
    }

    if (is_numeric($key)) {
        return $ar;
    }

    return $key;
}

function str_clean_url($text)
{
    if (empty($text)) {
        return '';
    }

    $text = preg_replace("/(https?\:\/\/[^\\\" ]+)/", " ", $text);

    return $text;
}

function str_short_text($text = '')
{
    // get first letter of each word
    if (empty($text)) {
        return '';
    }

    $text = convertVietnameseToEnglish($text);
    $tmp = explode(' ', $text);
    $results = [];
    foreach ($tmp as $t) {
        $t = trim($t);
        if (empty($t)) {
            continue;
        }

        $results[] = $t[0];
    }

    return implode('', $results);
}

function str_clean_route_name($class)
{
    return str_replace('-', '', str_replace('_', '.', $class));
}

function str_plurals($str, $join = '.')
{
    if (empty($str)) {
        return '';
    }

    if (strpos($str, $join) === false) {
        return str_plural($str);
    }

    $blackLists = ['shopy'];
    $tmp = explode($join, $str);
    $results = [];
    foreach ($tmp as $t) {
        if (in_array($t, $blackLists)) {
            $results[] = $t;
        } else {
            $results[] = str_plural($t);
        }
    }

    return implode($join, $results);
}

function str_clean_title($class)
{
    return str_replace('\\', ' ', str_replace('-', ' ', str_replace('_', ' ', $class)));
}

function str_clean_str($str)
{
    if (empty($str)) {
        return '';
    }

    // remove no mean characters
    $str = str_replace(':', ' ', $str);
    return trim(str_clean_double_space(strtolower(convertVietnameseToEnglish($str))));
}

function str_clean_double_space($text)
{
    if (empty($text)) {
        return '';
    }

    $text = preg_replace("/\s+/", " ", $text);
    return $text;
}

function str_limit_words($str = '', $words = 100, $end = '...')
{
    if (empty($str)) {
        return '';
    }

    return \Illuminate\Support\Str::words($str, $words, $end);
}

if (!function_exists('str_random')) {
    function str_random($length = 16)
    {
        return \Illuminate\Support\Str::random($length);
    }
}

if (!function_exists('str_camel')) {
    function str_camel($str)
    {
        return \Illuminate\Support\Str::camel($str);
    }
}
