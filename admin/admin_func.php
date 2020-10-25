<?php

//================================================================
// admin functions
// 2005-07-21 K.OHWADA
//================================================================

//-----------------------------------------------
// get value from POST
//-----------------------------------------------
function get_post_op()
{
    return get_post_text('op', 'menu');
}

function get_post_int($key, $default = 0)
{
    $val = $default;

    if (isset($_POST[$key])) {
        $val = (int)$_POST[$key];
    }

    return $val;
}

function get_post_text($key, $default = '')
{
    $val = $default;

    if (isset($_POST[$key])) {
        $val = strip_slashes($_POST[$key]);
    }

    return $val;
}

function get_post_text_preg_split($key)
{
    $arr = [];

    if (isset($_POST[$key])) {
        $val = strip_slashes($_POST[$key]);

        $arr = preg_split("\n", $val);
    }

    return $arr;
}

function strip_slashes($str)
{
    if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }

    return $str;
}

//-----------------------------------------------
// check or split line
//-----------------------------------------------
function check_line_pause($line)
{
    if (0 === strpos($line, "---")) {
        return true;
    }

    return false;
}

function split_line($line, $pattern = ',')
{
    $item_arr = preg_split($pattern, $line);

    foreach ($item_arr as $key => $item) {
        $item_arr[$key] = trim($item);
    }

    return $item_arr;
}

//-----------------------------------------------
// convert strings
//-----------------------------------------------
function str_trim_html($str, $start = 0, $width = 100, $maker = '...')
{
    $str = mb_strimwidth($str, $start, $width, $maker);

    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5);
}

function str_replace_crlf($str)
{
    $str = str_replace('\r\n', "\r\n", $str);

    $str = str_replace('\r', "\r", $str);

    $str = str_replace('\n', "\n", $str);

    return $str;
}

//-----------------------------------------------
// print form
//-----------------------------------------------
function print_form_file($title, $dsc, $file)
{
    print_title($title);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    echo $dsc . "<br>\n";

    print_file($file);

    print_form_go();
}

function print_title($title)
{
    echo "<h4>$title</h4>\n";
}

function print_file($file)
{
    echo "<form>\n";

    echo "<textarea cols='60' rows='20'>";

    readfile($file);

    echo "</textarea>\n";

    echo "</form>\n";
}

function print_form_go($op = 'go', $button = '')
{
    $action = xoops_getenv('PHP_SELF');

    if (empty($button)) {
        $button = _EXEC;
    } ?>
    <form action='<?php echo $action; ?>' method='post'>
        <input type='hidden' name='op' value='<?php echo $op; ?>'>
        <input type='submit' value='<?php echo $button; ?>'>
        <input type='button' value='<?php echo _BACK; ?>' onclick='javascript:history.go(-1)'>
    </form>
    <?php
}

//-----------------------------------------------
// print error
//-----------------------------------------------
function print_error($msg)
{
    echo "<font color='red'>" . $msg, "</font><br>\n";
}

function print_error_file_not_exists($file)
{
    if (!file_exists($file)) {
        print_error("file not exists: $file");

        return false;
    }

    return true;
}

function exit_not_file_exists($file)
{
    if (!file_exists($file)) {
        xoops_cp_header();

        print_error("not exists: $file");

        xoops_cp_footer();

        exit();
    }
}

//-----------------------------------------------
// use $xoopsUser
//-----------------------------------------------
function get_uid()
{
    global $xoopsUser;

    $uid = 0;

    if ($xoopsUser) {
        $uid = $xoopsUser->getVar('uid');
    }

    return $uid;
}

//-----------------------------------------------
// use $xoopsDB
//-----------------------------------------------
function db_get_newid()
{
    global $xoopsDB;

    return $xoopsDB->getInsertId();
}

function db_prefix($str)
{
    global $xoopsDB;

    return $xoopsDB->prefix($str);
}

function db_exec_num($sql)
{
    global $xoopsDB;

    $ret = db_exec($sql);

    if (false === $ret) {
        return -1;
    }

    $arr = $xoopsDB->fetchRow($ret);

    $num = $arr[0];

    if (empty($num)) {
        $num = 0;
    }

    return $num;
}

function db_exec_row($sql)
{
    global $xoopsDB;

    $ret = db_exec($sql);

    if (false === $ret) {
        return -1;
    }

    $arr = [];

    while (false !== ($row = $xoopsDB->fetchArray($ret))) {
        $arr[] = $row;
    }

    return $arr;
}

function db_exec($sql)
{
    global $xoopsDB;

    $ret = $xoopsDB->queryF($sql);

    if (false !== $ret) {
        return $ret;
    }

    $error = $xoopsDB->error();

    echo "<font color='red'>$sql<br>$error</font><br>\n";

    return false;
}

function db_select_id($table, $id, $key, $value)
{
    $value = addslashes($value);

    $sql = "SELECT $id FROM $table WHERE $key = '$value'";

    $rec = db_exec_row($sql);

    if (-1 == $rec) {
        return -1;
    }

    $count = count($rec);

    if (0 == $count) {
        print_error(_ERR_UNMATCH_REC);

        return -1;
    } elseif ($count > 1) {
        print_error(_ERR_MANY_REC);

        return -1;
    }

    return $rec[0][$id];
}

function db_make_selbox_list($table, $id, $title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '', $none_name = '---')
{
    global $xoopsDB;

    if ('' == $sel_name) {
        $sel_name = $id;
    }

    $text = '';

    $text .= "<select name='" . $sel_name . "'";

    if ('' != $onchange) {
        $text .= " onchange='" . $onchange . "'";
    }

    $text .= ">\n";

    $sql = "SELECT $id, $title FROM $table ";

    if ('' != $order) {
        $sql .= " ORDER BY $order";
    }

    $result = $xoopsDB->query($sql);

    if ($none) {
        $text .= "<option value='0'>$none_name</option>\n";
    }

    while (list($catid, $name) = $xoopsDB->fetchRow($result)) {
        $sel = '';

        if ($catid == $preset_id) {
            $sel = " selected='selected'";
        }

        $text .= "<option value='$catid'$sel>$name</option>\n";
    }

    $text .= "</select>\n";

    return $text;
}

//-----------------------------------------------
// use xoopstree.php
//-----------------------------------------------

// porting from makeMySelBox
function db_make_selbox($table, $id, $pid, $title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '', $none_name = '---')
{
    global $xoopsDB;

    if ('' == $sel_name) {
        $sel_name = $id;
    }

    $text = '';

    $text .= "<select name='" . $sel_name . "'";

    if ('' != $onchange) {
        $text .= " onchange='" . $onchange . "'";
    }

    $text .= ">\n";

    $sql = "SELECT $id, $title FROM $table WHERE $pid=0";

    if ('' != $order) {
        $sql .= " ORDER BY $order";
    }

    $result = $xoopsDB->query($sql);

    if ($none) {
        $text .= "<option value='0'>$none_name</option>\n";
    }

    while (list($catid, $name) = $xoopsDB->fetchRow($result)) {
        $sel = '';

        if ($catid == $preset_id) {
            $sel = " selected='selected'";
        }

        $text .= "<option value='$catid'$sel>$name</option>\n";

        $sel = '';

        $arr = db_get_child_tree_array($table, $id, $pid, $catid, $order);

        foreach ($arr as $option) {
            $option['prefix'] = str_replace('.', '--', $option['prefix']);

            $catpath = $option['prefix'] . '&nbsp;' . htmlspecialchars($option[$title], ENT_QUOTES | ENT_HTML5);

            if ($option[$id] == $preset_id) {
                $sel = " selected='selected'";
            }

            $text .= "<option value='" . $option[$id] . "'$sel>$catpath</option>\n";

            $sel = '';
        }
    }

    $text .= "</select>\n";

    return $text;
}

function db_get_child_tree_array($table, $id, $pid, $sel_id = 0, $order = '', $parray = [], $r_prefix = '')
{
    $mytree = new XoopsTree($table, $id, $pid);

    return $mytree->getChildTreeArray($sel_id, $order, $parray, $r_prefix);
}

?>
