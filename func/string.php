<?php

function myvesta_replace_in_file($find, $replace, $file) {
    if (!file_exists($file)) return myvesta_throw_error (MYVESTA_ERROR_FILE_DOES_NOT_EXISTS, "File '$file' not found");

    $buf=file_get_contents($file);

    if (strpos($buf, $find)===false) return myvesta_throw_error (MYVESTA_ERROR_STRING_NOT_FOUND, "String '$find' not found");

    $buf=str_replace($find, $replace, $buf);
    $r=file_put_contents($file, $buf);
    return $r;
}

function myvesta_str_get_between (&$text, $left_substring, $right_substring, $start=0, $return_left_substring=0, $return_right_substring=0, $left_substring_necessary=1, $right_substring_necessary=1)
{
    global $myvesta_str_found_at, $myvesta_str_end_at;
    $myvesta_str_found_at=0;
    $myvesta_str_end_at=0;
    $from_null=0;
    $pos1=strpos($text, $left_substring, $start);
    if ($pos1===FALSE)
    {
        if ($left_substring_necessary==1) return "";
        $pos1=$start;
        $from_null=1;
    }

    if ($return_left_substring==0)
    {
        if ($from_null==0) $pos1=$pos1+strlen($left_substring);
    }
    $pos2=strpos($text, $right_substring, $pos1+1);
    if ($pos2===FALSE)
    {
        if ($right_substring_necessary==1) return "";
        $pos2=strlen($text);
    }
    if ($return_right_substring==0) $len=$pos2-$pos1;
    else $len=($pos2-$pos1)+strlen($right_substring);
    
    $slen=strlen($text);
    if ($pos1+$len>$slen) $len=$slen-$pos1;
    
    $myvesta_str_found_at=$pos1;
    $myvesta_str_end_at=$pos1+$len;
    
    return substr($text, $pos1, $len);
}

function myvesta_str_replace_once_between_including_borders(&$text, $left, $right, $replace_with) {
    $pos1=strpos($text, $left);
    if ($pos1===false) return $text;
    $pos2=strpos($text, $right, $left+strlen($left));
    if ($pos2===false) return $text;
    return substr($text, 0, $pos1).$replace_with.substr($text, $pos2+strlen($right));
}

function myvesta_str_strip_once_between_including_borders(&$text, $left, $right) {
    $pos1=strpos($text, $left);
    if ($pos1===false) return $text;
    $pos2=strpos($text, $right, $left+strlen($left));
    if ($pos2===false) return $text;
    return substr($text, 0, $pos1).substr($text, $pos2+strlen($right));
}


function myvesta_str_replace_between_including_borders($text, $left, $right, $replace_with) {
    $start=0;
    $left_len=strlen($left);
    $right_len=strlen($right);
    while (true) {
        $pos1=strpos($text, $left);
        if ($pos1===false) break;
        $pos2=strpos($text, $right, $left+$left_len);
        if ($pos2===false) break;
        $text=substr($text, 0, $pos1).$replace_with.substr($text, $pos2+$right_len);
    }
    return $text;
}

function myvesta_str_strip_between_including_borders($text, $left, $right) {
    $left_len=strlen($left);
    $right_len=strlen($right);
    while (true) {
        $pos1=strpos($text, $left);
        if ($pos1===false) break;
        $pos2=strpos($text, $right, $left+$left_len);
        if ($pos2===false) break;
        $text=substr($text, 0, $pos1).substr($text, $pos2+$right_len);
    }
    return $text;
}
