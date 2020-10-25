<?php

//================================================================
// xoopspoll: functions
// 2005-07-24 K.OHWADA
//================================================================

require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

function xoopspoll_file($file)
{
    print_title(_PL_ADD_BULK);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    $flag_line = 0;

    $poll_id = 0;

    $num = 0;

    xoopspoll_set_barcolor();

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
        } // question

        elseif (0 == $flag_line) {
            [$question, $multiple] = xoopspoll_get_desc($line, "\t");

            if (!xoopspoll_check_desc($question)) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $poll_id = xoopspoll_insert_desc($question, $description, $multiple);

            $num = 0;

            $flag_line = 1;
        } // option

        elseif (1 == $flag_line) {
            $option_text = xoopspoll_get_option($line, "\t");

            if (!xoopspoll_check_option($option_text)) {
                continue;
            }

            xoopspoll_insert_option($poll_id, $option_text, $num);

            $num++;
        } else {
            print_error('system error');
        }
    }

    echo "<br>\n";

    echo '<b>' . _PL_POLL_ADDED . "</b><br>\n";
}

function xoopspoll_get_desc($line, $pattern = ',')
{
    $question = '';

    $description = '';

    $multiple = 0;

    [$question, $multiple] = split_line($line, $pattern);

    echo str_trim_html("$question, $multiple");

    echo "<br>\n";

    return [$question, $multiple];
}

function xoopspoll_check_desc($question)
{
    if (empty($question)) {
        print_error(_PL_NO_QUESTION);

        return false;
    }

    return true;
}

function xoopspoll_get_option($line, $pattern = ',')
{
    $option_text = '';

    [$option_text] = split_line($line, $pattern);

    $str = (string)$option_text;

    echo str_trim_html($str);

    echo "<br>\n";

    return $option_text;
}

function xoopspoll_check_option($option_text)
{
    if (empty($option_text)) {
        print_error(_PL_NO_OPTION);

        return false;
    }

    return true;
}

function xoopspoll_insert_desc($question, $multiple)
{
    $description = '';

    $user_id = get_uid();

    $start_time = time();

    $end_time = $start_time + 30 * 24 * 60 * 60;    // one month

    $display = 1;

    $table_xoopspoll_desc = db_prefix('xoopspoll_desc');

    $question = addslashes($question);

    $multiple = (int)$multiple;

    $sql = "INSERT INTO $table_xoopspoll_desc (question, description, user_id, start_time, end_time, multiple, display) VALUES ('$question', '$description', $user_id, $start_time, $end_time, $multiple, $display)";

    db_exec($sql);

    return db_get_newid();
}

function xoopspoll_insert_option($poll_id, $option_text, $num)
{
    $table_xoopspoll_option = db_prefix('xoopspoll_option');

    $option_text = addslashes($option_text);

    $option_color = xoopspoll_get_barcolor($num);

    $sql = "INSERT INTO $table_xoopspoll_option (poll_id, option_text, option_color) VALUES ($poll_id, '$option_text', '$option_color')";

    db_exec($sql);
}

function xoopspoll_set_barcolor()
{
    global $xoopspoll_barcolor_arr, $xoopspoll_barcolor_max;

    $xoopspoll_barcolor_arr = [];

    $barcolor_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/modules/xoopspoll/images/colorbars/');

    foreach ($barcolor_array as $barcolor) {
        if ('blank.gif' != $barcolor) {
            $xoopspoll_barcolor_arr[] = $barcolor;
        }
    }

    $xoopspoll_barcolor_max = count($xoopspoll_barcolor_arr);
}

function xoopspoll_get_barcolor($num)
{
    global $xoopspoll_barcolor_arr, $xoopspoll_barcolor_max;

    $barcolor_num = (int)$num % $xoopspoll_barcolor_max;

    return $xoopspoll_barcolor_arr[$barcolor_num];
}
