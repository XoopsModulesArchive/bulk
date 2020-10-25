<?php

//================================================================
// xoopsfaq: functions
// 2005-07-24 K.OHWADA
//================================================================

function xoopsfaq_file($file)
{
    print_title(_XD_ADD_BULK);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    $flag_line = 0;

    $category_id = 0;

    foreach ($line_arr as $line) {
        $line = trim($line);

        // blank

        if (empty($line)) {
            continue;
        }

        // pause

        if (check_line_pause($line)) {
            echo "<br>\n";

            $flag_line = 0;
        } // category

        elseif (0 == $flag_line) {
            $category_title = xoopsfaq_get_categories($line, "\t");

            if (!xoopsfaq_check_categories($category_title)) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $category_id = xoopsfaq_insert_categories($category_title);

            $flag_line = 1;
        } // contents

        elseif (1 == $flag_line) {
            [$contents_title, $contents_contents] = xoopsfaq_get_contents($line, "\t");

            if (!xoopsfaq_check_contents($contents_title, $contents_contents)) {
                continue;
            }

            xoopsfaq_insert_contents($category_id, $contents_title, $contents_contents);
        } else {
            print_error('system error');
        }
    }

    echo "<br>\n";

    echo '<b>' . _XD_FAQ_ADDED . "</b><br>\n";
}

function xoopsfaq_get_categories($line, $pattern = ',')
{
    $category_title = '';

    [$category_title] = split_line($line, $pattern);

    echo str_trim_html((string)$category_title);

    echo "<br>\n";

    return $category_title;
}

function xoopsfaq_check_categories($category_title)
{
    if (empty($category_title)) {
        print_error(_XD_NO_CAT);

        return false;
    }

    return true;
}

function xoopsfaq_get_contents($line, $pattern = ',')
{
    $contents_title = '';

    $contents_contents = '';

    [$contents_title, $contents_contents] = split_line($line, $pattern);

    $str = "$contents_title, $contents_contents";

    echo str_trim_html($str);

    echo "<br>\n";

    $contents_contents = str_replace_crlf($contents_contents);

    return [$contents_title, $contents_contents];
}

function xoopsfaq_check_contents($contents_title, $contents_contents)
{
    if (empty($contents_title)) {
        print_error(_XD_NO_QUESTION);

        return false;
    }

    if (empty($contents_contents)) {
        print_error(_XD_NO_ANSWER);

        return false;
    }

    return true;
}

function xoopsfaq_insert_categories($category_title)
{
    $table_xoopsfaq_categories = db_prefix('xoopsfaq_categories');

    $category_title = addslashes($category_title);

    $sql = "INSERT INTO $table_xoopsfaq_categories (category_title) VALUES ('$category_title')";

    db_exec($sql);

    return db_get_newid();
}

function xoopsfaq_insert_contents($category_id, $contents_title, $contents_contents)
{
    $table_xoopsfaq_contents = db_prefix('xoopsfaq_contents');

    $contents_time = time();

    $contents_title = addslashes($contents_title);

    $contents_contents = addslashes($contents_contents);

    $sql = "INSERT INTO $table_xoopsfaq_contents (category_id, contents_title, contents_contents, contents_time) VALUES ($category_id, '$contents_title', '$contents_contents', $contents_time)";

    db_exec($sql);
}
