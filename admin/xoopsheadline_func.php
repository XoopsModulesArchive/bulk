<?php

//================================================================
// xoopsheadline: functions
// 2005-07-24 K.OHWADA
//================================================================

function xoopsheadline_file($file)
{
    print_title(_HL_ADD_BULK);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    foreach ($line_arr as $line) {
        $line = trim($line);

        // blank

        if (empty($line)) {
            continue;
        }

        // xoopsheadline

        [$headline_name, $headline_url, $headline_rssurl, $headline_encoding] = xoopsheadline_get_xoopsheadline($line, "\t");

        if (!xoopsheadline_check_xoopsheadline($headline_name, $headline_url, $headline_rssurl)) {
            continue;
        }

        xoopsheadline_insert_xoopsheadline($headline_name, $headline_url, $headline_rssurl, $headline_encoding);
    }

    echo "<br>\n";

    echo '<b>' . _HL_HEADLINE_ADDED . "</b><br>\n";
}

function xoopsheadline_get_xoopsheadline($line, $pattern = ',')
{
    $headline_name = '';

    $headline_url = '';

    $headline_rssurl = '';

    $headline_encoding = '';

    [$headline_name, $headline_url, $headline_rssurl, $headline_encoding] = split_line($line, $pattern);

    $str = "$headline_name, $headline_url, $headline_rssurl, $headline_encoding";

    echo str_trim_html($str);

    echo "<br>\n";

    return [$headline_name, $headline_url, $headline_rssurl, $headline_encoding];
}

function xoopsheadline_check_xoopsheadline($headline_name, $headline_url, $headline_rssurl)
{
    if (empty($headline_name)) {
        print_error(_HL_NO_NAME);

        return false;
    }

    if (empty($headline_url)) {
        print_error(_HL_NO_URL);

        return false;
    }

    if (empty($headline_rssurl)) {
        print_error(_HL_NO_RSSURL);

        return false;
    }

    return true;
}

function xoopsheadline_insert_xoopsheadline($headline_name, $headline_url, $headline_rssurl, $headline_encoding = '')
{
    $table_xoopsheadline = db_prefix('xoopsheadline');

    $headline_cachetime = 24 * 60 * 60;    // 1 day

    $headline_asblock = 1;

    $headline_display = 1;

    if (empty($headline_encoding)) {
        $headline_encoding = 'utf-8';
    }

    $headline_name = addslashes($headline_name);

    $headline_url = addslashes($headline_url);

    $headline_rssurl = addslashes($headline_rssurl);

    $headline_encoding = addslashes($headline_encoding);

    $sql = "INSERT INTO $table_xoopsheadline (headline_name, headline_url, headline_rssurl, headline_encoding, headline_cachetime, headline_asblock, headline_display) VALUES ('$headline_name', '$headline_url', '$headline_rssurl', '$headline_encoding', $headline_cachetime, $headline_asblock, $headline_display )";

    db_exec($sql);
}
