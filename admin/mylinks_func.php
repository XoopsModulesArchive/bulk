<?php

//================================================================
// mylinks: functions
// 2005-07-24 K.OHWADA
//================================================================

require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

$file_lang = XOOPS_ROOT_PATH . '/modules/mylinks/language/' . $xoopsConfig['language'] . '/main.php';

if (file_exists($file_lang)) {
    require_once $file_lang;
} else {
    require_once XOOPS_ROOT_PATH . '/modules/mylinks/language/english/main.php';
}

$myts = MyTextSanitizer::getInstance();
$eh = new ErrorHandler();

function mylinks_file_cat($file)
{
    print_title(_ML_ADD_BULK_CAT);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    // parent category

    $line = array_shift($line_arr);

    $line = trim($line);

    [$parent_depth, $parent_title] = mylinks_get_cat($line);

    if (!mylinks_check_cat($parent_title)) {
        echo "<br>\n";

        echo '<b>' . _FINISH_FAULT . "</b><br>\n";

        return;
    }

    $pid_first = mylinks_select_cid($parent_title);

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

        [$depth, $title] = mylinks_get_cat($line);

        if (!mylinks_check_cat($title)) {
            continue;
        }

        // under one level, or above level

        if (($depth == ($depth_prev + 1)) || ($depth < $depth_prev)) {
            $pid = $pid_arr[$depth];
        } // under two or more level

        elseif ($depth > $depth_prev) {
            print_error(_ML_ERR_LAYER);

            $flag_error = 1;

            break;
        }

        $newid = mylinks_insert_cat($pid, $title);

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

function mylinks_file_links($file)
{
    print_title(_ML_ADD_BULK_LINK);

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

            if (!mylinks_check_cat($category_title)) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $cid = mylinks_select_cid($category_title);

            if (-1 == $cid) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $flag_line = 1;
        } // link

        elseif (1 == $flag_line) {
            [$title, $url, $description] = mylinks_get_links($line, "\t");

            if (!mylinks_check_links($title, $url, $description)) {
                continue;
            }

            mylinks_insert_links($cid, $title, $url, $description);
        } else {
            print_error('system error');
        }
    }

    echo "<br>\n";

    echo '<b>' . _MD_NEWLINKADDED . "</b><br>\n";
}

function mylinks_get_cat($line)
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

function mylinks_check_cat($title)
{
    if (empty($title)) {
        print_error(_ML_NO_CAT);

        return false;
    }

    return true;
}

function mylinks_get_links($line, $pattern = ',')
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

function mylinks_check_links($title, $url, $description)
{
    if (empty($title)) {
        print_error(_ML_NO_TITLE);

        return false;
    }

    if (empty($url)) {
        print_error(_ML_NO_URL);

        return false;
    }

    if (empty($description)) {
        print_error(_ML_NO_DESCRIPTION);

        return false;
    }

    return true;
}

function mylinks_insert_cat($pid, $title)
{
    $pid = (int)$pid;

    if ($pid < 0) {
        print_error(_ML_ERR_PID);

        return;
    }

    $table_mylinks_cat = db_prefix('mylinks_cat');

    $title = addslashes($title);

    $sql = "INSERT INTO $table_mylinks_cat (pid, title) VALUES ($pid, '$title')";

    db_exec($sql);

    return db_get_newid();
}

function mylinks_insert_links($cid, $title, $url, $description)
{
    $cid = (int)$cid;

    if ($cid <= 0) {
        print_error(_ML_ERR_CID);

        return;
    }

    $table_mylinks_links = db_prefix('mylinks_links');

    $table_mylinks_text = db_prefix('mylinks_text');

    $submitter = get_uid();

    $status = 1;

    $date = time();

    $title = addslashes($title);

    $url = addslashes($url);

    $description = addslashes($description);

    $sql1 = "INSERT INTO $table_mylinks_links (cid, title, url, submitter, status, date) VALUES ($cid, '$title', '$url', $submitter, $status, $date)";

    db_exec($sql1);

    $newid = db_get_newid();

    $sql2 = "INSERT INTO $table_mylinks_text (lid, description) VALUES ($newid, '$description')";

    db_exec($sql2);
}

function mylinks_select_cid($title)
{
    if ('TOP' == $title) {
        return 0;
    }

    $table = db_prefix('mylinks_cat');

    return db_select_id($table, 'cid', 'title', $title);
}

function mylinks_make_selbox($none = 0)
{
    $table_mylinks_cat = db_prefix('mylinks_cat');

    return db_make_selbox($table_mylinks_cat, 'cid', 'pid', 'title', 'title', 0, $none, '', '', 'TOP');
}
