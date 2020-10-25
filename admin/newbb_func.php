<?php

//================================================================
// newbb: functions
// 2005-07-24 K.OHWADA
//================================================================

require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

$file_lang = XOOPS_ROOT_PATH . '/modules/newbb/language/' . $xoopsConfig['language'] . '/main.php';

if (file_exists($file_lang)) {
    require_once $file_lang;
} else {
    require_once XOOPS_ROOT_PATH . '/modules/newbb/language/english/main.php';
}

function newbb_file_cat($file)
{
    print_title(_BB_ADD_BULK_CAT);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    // category

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        $title = newbb_get_cat($line);

        if (!newbb_check_cat($title)) {
            continue;
        }

        newbb_insert_cat($title);
    }

    echo "<br>\n";

    echo '<b>' . _BB_NEW_CAT_ADDED . "</b><br>\n";
}

function newbb_file_forum($file)
{
    print_title(_BB_ADD_BULK_FORUM);

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

            if (!newbb_check_cat($category_title)) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $cid = newbb_select_cid($category_title);

            if (-1 == $cid) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $flag_line = 1;
        } // forum

        elseif (1 == $flag_line) {
            [$forum_name, $forum_desc] = newbb_get_forum($line, "\t");

            if (!newbb_check_forum($forum_name, $forum_desc)) {
                continue;
            }

            newbb_insert_forum($cid, $forum_name, $forum_desc);
        } else {
            print_error('system error');
        }
    }

    echo "<br>\n";

    echo '<b>' . _BB_NEW_FORUM_ADDED . "</b><br>\n";
}

function newbb_file_topic($file)
{
    print_title(_BB_ADD_BULK_TOPIC);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    $flag_line = 0;

    $forum_id = -1;    // dummy

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
        } // forum

        elseif (0 == $flag_line) {
            $forum_name = $line;

            if (!newbb_check_forum($forum_name, '', 0)) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $forum_id = newbb_select_forum_id($forum_name);

            if (-1 == $forum_id) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $flag_line = 1;
        } // topic

        elseif (1 == $flag_line) {
            [$subject, $post_text] = newbb_get_post($line, "\t");

            if (!newbb_check_post($subject, $post_text)) {
                continue;
            }

            $newid = newbb_insert_topic($forum_id, $subject);

            newbb_insert_post($forum_id, $newid, 0, $subject, $post_text);

            newbb_update_forum_topic($forum_id);
        } else {
            print_error('system error');
        }
    }

    echo "<br>\n";

    echo '<b>' . _BB_NEW_TOPIC_ADDED . "</b><br>\n";
}

function newbb_get_cat($line, $pattern = ',')
{
    $title = '';

    [$title] = split_line($line, $pattern);

    echo str_trim_html((string)$title);

    echo "<br>\n";

    return $title;
}

function newbb_check_cat($title)
{
    if (empty($title)) {
        print_error(_BB_NO_CAT);

        return false;
    }

    return true;
}

function newbb_get_forum($line, $pattern = ',')
{
    $forum_name = '';

    $forum_desc = '';

    [$forum_name, $forum_desc] = split_line($line, $pattern);

    $str = "$forum_name, $forum_desc";

    echo str_trim_html($str);

    echo "<br>\n";

    $forum_desc = str_replace_crlf($forum_desc);

    return [$forum_name, $forum_desc];
}

function newbb_check_forum($forum_name, $forum_desc, $flag = 1)
{
    if (empty($forum_name)) {
        print_error(_BB_NO_FORUM_NAME);

        return false;
    }

    if ($flag && empty($forum_desc)) {
        print_error(_BB_NO_FORUM_DESC);

        return false;
    }

    return true;
}

function newbb_get_topic($line, $pattern = ',')
{
    $topic_title = '';

    [$topic_title] = split_line($line, $pattern);

    $str = (string)$topic_title;

    echo str_trim_html($str);

    echo "<br>\n";

    return $topic_title;
}

function newbb_check_topic($topic_title)
{
    if (empty($topic_title)) {
        print_error(_BB_NO_TOPIC_TITLE);

        return false;
    }

    return true;
}

function newbb_get_post($line, $pattern = ',')
{
    $subject = '';

    $post_text = '';

    [$subject, $post_text] = split_line($line, $pattern);

    $str = "$subject, $post_text";

    echo str_trim_html($str);

    echo "<br>\n";

    $post_text = str_replace_crlf($post_text);

    return [$subject, $post_text];
}

function newbb_check_post($subject, $post_text)
{
    if (empty($subject)) {
        print_error(_BB_NO_SUBJECT);

        return false;
    }

    if (empty($post_text)) {
        print_error(_BB_NO_POST_TEXT);

        return false;
    }

    return true;
}

function newbb_insert_cat($cat_title)
{
    $table_bb_categories = db_prefix('bb_categories');

    $cat_order = 1;

    $cat_title = addslashes($cat_title);

    $sql = "INSERT INTO $table_bb_categories (cat_title, cat_order) VALUES ('$cat_title', $cat_order)";

    db_exec($sql);

    return db_get_newid();
}

function newbb_insert_forum($cat_id, $forum_name, $forum_desc)
{
    $cat_id = (int)$cat_id;

    if ($cat_id <= 0) {
        print_error(_BB_ERR_CID);

        return;
    }

    $table_bb_forums = db_prefix('bb_forums');

    $table_bb_forum_mods = db_prefix('bb_forum_mods');

    $forum_access = 2;

    $user_id = get_uid();

    $allow_sig = 1;

    $posts_per_page = 10;

    $forum_name = addslashes($forum_name);

    $forum_desc = addslashes($forum_desc);

    $sql1 = "INSERT INTO $table_bb_forums (cat_id, forum_name, forum_desc, forum_access, allow_sig, posts_per_page) VALUES ($cat_id, '$forum_name', '$forum_desc', $forum_access, $allow_sig, $posts_per_page)";

    db_exec($sql1);

    $newid = db_get_newid();

    $sql2 = "INSERT INTO $table_bb_forum_mods (forum_id, user_id) VALUES ($newid, $user_id)";

    db_exec($sql2);
}

function newbb_update_forum_topic($forum_id)
{
    $table_bb_forums = db_prefix('bb_forums');

    $forum_last_post_id = get_uid();

    $sql = "UPDATE $table_bb_forums SET forum_topics = forum_topics+1, forum_posts = forum_posts+1, forum_last_post_id = $forum_last_post_id WHERE forum_id = $forum_id";

    db_exec($sql);
}

function newbb_insert_topic($forum_id, $topic_title)
{
    $forum_id = (int)$forum_id;

    if ($forum_id <= 0) {
        print_error(_BB_ERR_FORUM_ID);

        return;
    }

    $table_bb_topics = db_prefix('bb_topics');

    $topic_poster = get_uid();

    $topic_last_post_id = $topic_poster;

    $topic_time = time();

    $topic_title = addslashes($topic_title);

    $sql = "INSERT INTO $table_bb_topics (forum_id, topic_title, topic_poster, topic_last_post_id, topic_time) VALUES ($forum_id, '$topic_title', $topic_poster, $topic_last_post_id, $topic_time)";

    db_exec($sql);

    return db_get_newid();
}

function newbb_insert_post($forum_id, $topic_id, $pid, $subject, $post_text)
{
    $forum_id = (int)$forum_id;

    if ($forum_id <= 0) {
        print_error(_BB_ERR_FORUM_ID);

        return;
    }

    $topic_id = (int)$topic_id;

    if ($topic_id <= 0) {
        print_error(_BB_ERR_TOPIC_ID);

        return;
    }

    $table_bb_posts = db_prefix('bb_posts');

    $table_bb_posts_text = db_prefix('bb_posts_text');

    $post_time = time();

    $uid = get_uid();

    $poster_ip = xoops_getenv('REMOTE_ADDR');

    $nohtml = 1;

    $subject = addslashes($subject);

    $post_text = addslashes($post_text);

    $sql1 = "INSERT INTO $table_bb_posts (forum_id, topic_id, pid, subject, post_time, uid, poster_ip, nohtml) VALUES ($forum_id, $topic_id, $pid, '$subject', $post_time, $uid, '$poster_ip', $nohtml)";

    db_exec($sql1);

    $newid = db_get_newid();

    $sql2 = "INSERT INTO $table_bb_posts_text (post_id, post_text) VALUES ($newid, '$post_text')";

    db_exec($sql2);
}

function newbb_select_cid($cat_title)
{
    $table = db_prefix('bb_categories');

    return db_select_id($table, 'cat_id', 'cat_title', $cat_title);
}

function newbb_select_forum_id($forum_name)
{
    $table = db_prefix('bb_forums');

    return db_select_id($table, 'forum_id', 'forum_name', $forum_name);
}

function newbb_make_selbox_cat()
{
    $table = db_prefix('bb_categories');

    return db_make_selbox_list($table, 'cat_id', 'cat_title', 'cat_order', 0, 0, '', '', '---');
}

function newbb_make_selbox_forum()
{
    $table = db_prefix('bb_forums');

    return db_make_selbox_list($table, 'forum_id', 'forum_name', 'forum_id', 0, 0, '', '', '---');
}
