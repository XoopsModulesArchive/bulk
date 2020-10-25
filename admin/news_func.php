<?php

//================================================================
// news: functions
// 2005-07-24 K.OHWADA
//================================================================

require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

$file_lang = XOOPS_ROOT_PATH . '/modules/news/language/' . $xoopsConfig['language'] . '/admin.php';

if (file_exists($file_lang)) {
    require_once $file_lang;
} else {
    require_once XOOPS_ROOT_PATH . '/modules/news/language/english/admin.php';
}

function news_file_topic($file)
{
    print_title(_NW_ADD_BULK_TOPIC);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    // parent category

    $line = array_shift($line_arr);

    $line = trim($line);

    [$parent_depth, $parent_title] = news_get_topic($line);

    if (!news_check_topic($parent_title)) {
        echo "<br>\n";

        echo '<b>' . _FINISH_FAULT . "</b><br>\n";

        return;
    }

    $pid_first = news_select_topicid($parent_title);

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

        [$depth, $topic_title] = news_get_topic($line);

        if (!news_check_topic($topic_title)) {
            continue;
        }

        // under one level, or above level

        if (($depth == ($depth_prev + 1)) || ($depth < $depth_prev)) {
            $pid = $pid_arr[$depth];
        } // under two or more level

        elseif ($depth > $depth_prev) {
            print_error(_NW_ERR_LAYER);

            $flag_error = 1;

            break;
        }

        $newid = news_insert_topic($pid, $topic_title);

        $pid_arr[$depth + 1] = $newid;

        $depth_prev = $depth;
    }

    echo "<br>\n";

    if ($flag_error) {
        echo '<b>' . _FINISH_FAULT . "</b><br>\n";
    } else {
        echo '<b>' . _NW_NEW_TOPIC_ADDED . "</b><br>\n";
    }
}

function news_file_story($file)
{
    print_title(_NW_ADD_BULK_STORY);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    $flag_line = 0;

    $topicid = -1;    // dummy

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
            [$category_depth, $category_title] = news_get_topic($line);

            if (!news_check_topic($category_title)) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $topicid = news_select_topicid($category_title);

            if (-1 == $topicid) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $flag_line = 1;
        } // link

        elseif (1 == $flag_line) {
            [$title, $hometext] = news_get_story($line, "\t");

            if (!news_check_story($title, $hometext)) {
                continue;
            }

            news_insert_story($topicid, $title, $hometext);
        } else {
            print_error('system error');
        }
    }

    echo "<br>\n";

    echo '<b>' . _NW_NEW_STORY_ADDED . "</b><br>\n";
}

function news_get_topic($line)
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

function news_check_topic($title)
{
    if (empty($title)) {
        print_error(_NW_NO_TITLE);

        return false;
    }

    return true;
}

function news_get_story($line, $pattern = ',')
{
    $title = '';

    $hometext = '';

    [$title, $hometext] = split_line($line, $pattern);

    $str = "$title, $hometext";

    echo str_trim_html($str);

    echo "<br>\n";

    $hometext = str_replace_crlf($hometext);

    return [$title, $hometext];
}

function news_check_story($title, $hometext)
{
    if (empty($title)) {
        print_error(_NW_NO_TITLE);

        return false;
    }

    if (empty($hometext)) {
        print_error(_NW_NO_HOMEBODY);

        return false;
    }

    return true;
}

function news_insert_topic($topic_pid, $topic_title, $topic_imgurl = '')
{
    $topic_pid = (int)$topic_pid;

    if ($topic_pid < 0) {
        print_error(_NW_ERR_PID);

        return;
    }

    $table_topics = db_prefix('topics');

    $topic_title = addslashes($topic_title);

    $topic_imgurl = addslashes($topic_imgurl);

    $sql = "INSERT INTO $table_topics (topic_pid, topic_title, topic_imgurl) VALUES ($topic_pid, '$topic_title', '$topic_imgurl')";

    db_exec($sql);

    return db_get_newid();
}

function news_insert_story($topicid, $title, $hometext)
{
    $topicid = (int)$topicid;

    if ($topicid <= 0) {
        print_error(_NW_ERR_CID);

        return;
    }

    $table_stories = db_prefix('stories');

    $uid = get_uid();

    $created = time();

    $published = time();

    $hostname = xoops_getenv('REMOTE_ADDR');

    $story_type = 'admin';

    $title = addslashes($title);

    $hometext = addslashes($hometext);

    $sql = "INSERT INTO $table_stories (topicid, title, hometext, uid, created, published, hostname, story_type) VALUES ($topicid, '$title', '$hometext', $uid, $created, $published, '$hostname', '$story_type')";

    db_exec($sql);
}

function news_select_topicid($topic_title)
{
    if ('TOP' == $topic_title) {
        return 0;
    }

    $table_topics = db_prefix('topics');

    $topic_title = addslashes($topic_title);

    $sql = "SELECT topic_id FROM $table_topics WHERE topic_title = '$topic_title'";

    $rec = db_exec_row($sql);

    if (-1 == $rec) {
        return -1;
    }

    $count = count($rec);

    if (0 == $count) {
        print_error(_NW_NO_CATEGORY);

        return -1;
    } elseif ($count > 1) {
        print_error(_NW_MANY_CATEGORY);

        return -1;
    }

    return $rec[0]['topic_id'];
}

function news_make_selbox($none = 0)
{
    $table_topics = db_prefix('topics');

    return db_make_selbox($table_topics, 'topic_id', 'topic_pid', 'topic_title', 'topic_title', 0, $none, '', '', 'TOP');
}
