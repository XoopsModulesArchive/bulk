<?php

//================================================================
// mydownloads: functions
// 2005-07-24 K.OHWADA
//================================================================

require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

$file_lang = XOOPS_ROOT_PATH . '/modules/mydownloads/language/' . $xoopsConfig['language'] . '/main.php';

if (file_exists($file_lang)) {
    require_once $file_lang;
} else {
    require_once XOOPS_ROOT_PATH . '/modules/mydownloads/language/english/main.php';
}

function mydownloads_file_cat($file)
{
    print_title(_MD_ADD_BULK_CAT);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    // parent category

    $line = array_shift($line_arr);

    $line = trim($line);

    [$parent_depth, $parent_title] = mydownloads_get_cat($line);

    if (!mydownloads_check_cat($parent_title)) {
        echo "<br>\n";

        echo '<b>' . _FINISH_FAULT . "</b><br>\n";

        return;
    }

    $pid_first = mydownloads_select_cid($parent_title);

    if (-1 == $pid_first) {
        echo "<br>\n";

        echo '<b>' . _FINISH_FAULT . "</b><br>\n";

        return;
    }

    // category

    $pid_arr = [];

    $pid_arr[0] = $pid_first;

    $pid = $pid_first;

    $depth_prev = 0;

    $flag_error = 0;

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$depth, $title] = mydownloads_get_cat($line);

        if (!mydownloads_check_cat($title)) {
            continue;
        }

        // under one level, or above level

        if (($depth == ($depth_prev + 1)) || ($depth < $depth_prev)) {
            $pid = $pid_arr[$depth];
        } // under two or more level

        elseif ($depth > $depth_prev) {
            print_error(_MD_ERR_LAYER);

            $flag_error = 1;

            break;
        }

        $newid = mydownloads_insert_cat($pid, $title);

        $pid_arr[$depth + 1] = $newid;

        $depth_prev = $depth;
    }

    echo "<br>\n";

    if ($flag_error) {
        echo '<b>' . _FINISH_FAULT . "</b><br>\n";
    } else {
        echo '<b>' . _MD_NEWCATADDED . "</b><br>\n";
    }
}

function mydownloads_file_downloads($file)
{
    print_title(_MD_ADD_BULK_DOWN);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    $flag_line = 0;

    $cid = -1;    // dummy

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
            $category_title = $line;

            if (!mydownloads_check_cat($category_title)) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $cid = mydownloads_select_cid($category_title);

            if (-1 == $cid) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $flag_line = 1;
        } // link

        elseif (1 == $flag_line) {
            [$title, $url, $description] = mydownloads_get_downloads($line, "\t");

            if (!mydownloads_check_downloads($title, $url, $description)) {
                continue;
            }

            mydownloads_insert_downloads($cid, $title, $url, $description);
        } else {
            print_error('system error');
        }
    }

    echo "<br>\n";

    echo '<b>' . _MD_NEWDLADDED . "</b><br>\n";
}

function mydownloads_get_cat($line)
{
    $depth = 0;

    $arrow = '';

    $title = '';

    if (preg_match('/^>/', $line)) {
        [$arrow, $title] = preg_preg_split("/\s+/", $line, 2);

        $depth = mb_substr_count($arrow, '>');
    } else {
        $title = $line;
    }

    echo str_trim_html("$arrow $title");

    echo "<br>\n";

    return [$depth, $title];
}

function mydownloads_check_cat($title)
{
    if (empty($title)) {
        print_error(_ML_NO_CAT);

        return false;
    }

    return true;
}

function mydownloads_get_downloads($line, $pattern = ',')
{
    $title = '';

    $url = '';

    $description = '';

    [$title, $url, $description] = split_line($line, $pattern);

    $str = "$title, $url, $description";

    echo str_trim_html($str);

    echo "<br>\n";

    $description = str_replace_crlf($description);

    return [$title, $url, $description];
}

function mydownloads_check_downloads($title, $url, $description)
{
    if (empty($title)) {
        print_error(_MD_NO_TITLE);

        return false;
    }

    if (empty($url)) {
        print_error(_MD_NO_URL);

        return false;
    }

    if (empty($description)) {
        print_error(_MD_NO_DESCRIPTION);

        return false;
    }

    return true;
}

function mydownloads_insert_cat($pid, $title)
{
    $pid = (int)$pid;

    if ($pid < 0) {
        print_error(_MD_ERR_PID);

        return;
    }

    $table_mydownloads_cat = db_prefix('mydownloads_cat');

    $title = addslashes($title);

    $sql = "INSERT INTO $table_mydownloads_cat (pid, title) VALUES ($pid, '$title')";

    db_exec($sql);

    return db_get_newid();
}

function mydownloads_insert_downloads($cid, $title, $url, $description)
{
    $cid = (int)$cid;

    if ($cid <= 0) {
        print_error(_MD_ERR_CID);

        return;
    }

    $table_mydownloads_downloads = db_prefix('mydownloads_downloads');

    $table_mydownloads_text = db_prefix('mydownloads_text');

    $submitter = get_uid();

    $status = 1;

    $date = time();

    $title = addslashes($title);

    $url = addslashes($url);

    $description = addslashes($description);

    $sql1 = "INSERT INTO $table_mydownloads_downloads (cid, title, url, submitter, status, date) VALUES ($cid, '$title', '$url', $submitter, $status, $date)";

    db_exec($sql1);

    $newid = db_get_newid();

    $sql2 = "INSERT INTO $table_mydownloads_text (lid, description) VALUES ($newid, '$description')";

    db_exec($sql2);
}

function mydownloads_select_cid($title)
{
    if ('TOP' == $title) {
        return 0;
    }

    $table = db_prefix('mydownloads_cat');

    return db_select_id($table, 'cid', 'title', $title);
}

function mydownloads_make_selbox($none = 0)
{
    $table_mydownloads_cat = db_prefix('mydownloads_cat');

    return db_make_selbox($table_mydownloads_cat, 'cid', 'pid', 'title', 'title', 0, $none, '', '', 'TOP');
}
