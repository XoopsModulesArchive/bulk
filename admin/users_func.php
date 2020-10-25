<?php

// 2005-08-10 K.OHWADA
// some bugs

//================================================================
// users: functions
// 2005-07-24 K.OHWADA
//================================================================

$file_lang = XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/user.php';

if (file_exists($file_lang)) {
    require_once $file_lang;
} else {
    require_once XOOPS_ROOT_PATH . '/language/english/user.php';
}

function users_file($file)
{
    echo '<h4>' . _US_ADD_USERS . "</h4>\n";

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$uname, $email, $pass] = users_get_param($line, "\t");

        $ret = users_check($uname, $email, $pass);

        if (0 != $ret) {
            continue;
        }

        users_insert($uname, $email, $pass);
    }

    echo "<br>\n";

    echo '<b>' . _US_NEW_USER_ADDED . "</b><br>\n";
}

function users_get_param($line, $pattern = ',')
{
    $uname = '';

    $email = '';

    $pass = '';

    [$uname, $email, $pass] = split_line($line, $pattern);

    echo str_trim_html("$uname, $email, $pass");

    echo "<br>\n";

    return [$uname, $email, $pass];
}

function users_check($uname, $email, $pass)
{
    $flag_err = 0;

    $ret1 = users_check_uname($uname);

    if (0 != $ret1) {
        $flag_err = 1;
    }

    $ret2 = users_check_email($email);

    if (0 != $ret2) {
        $flag_err = 1;
    }

    $ret3 = users_check_pass($pass);

    if (0 != $ret3) {
        $flag_err = 1;
    }

    return $flag_err;
}

function users_check_uname($uname)
{
    if (empty($uname)) {
        print_error(_US_NO_USER);

        return 1;
    }

    $table_users = db_prefix('users');

    $uname = addslashes($uname);

    $sql = "SELECT count(*) FROM $table_users WHERE uname = '$uname'";

    $num = db_exec_num($sql);

    if (-1 == $num) {
        return 2;
    } elseif (0 != $num) {
        print_error(_US_NICKNAMETAKEN);

        return 3;
    }

    return 0;
}

function users_check_email($email)
{
    if (empty($email)) {
        print_error(_US_NO_EMAIL);

        return 1;
    }

    $table_users = db_prefix('users');

    $email = addslashes($email);

    $sql = "SELECT count(*) FROM $table_users WHERE email = '$email'";

    $num = db_exec_num($sql);

    if (-1 == $num) {
        return 2;
    } elseif (0 != $num) {
        print_error(_US_EMAILTAKEN);

        return 3;
    }

    return 0;
}

function users_check_pass($pass)
{
    if (empty($pass)) {
        print_error(_US_NO_PASS);

        return 1;
    }

    return 0;
}

function users_insert($uname, $email, $pass)
{
    $table_users = db_prefix('users');

    $table_groups_users_link = db_prefix('groups_users_link');

    $uname = addslashes($uname);

    $email = addslashes($email);

    $pass_md5 = md5($pass);

    $user_regdate = time();

    $user_viewemail = 0;

    $timezone_offset = 9.0;

    $umode = 'flat';

    $groupid = 2;

    $sql1 = "INSERT INTO $table_users (uname, email, user_regdate, user_viewemail, pass, timezone_offset, umode) VALUES ('$uname', '$email', $user_regdate, $user_viewemail, '$pass_md5',  $timezone_offset, '$umode')";

    db_exec($sql1);

    $uid = db_get_newid();

    $sql2 = "INSERT INTO $table_groups_users_link (groupid, uid) VALUES ($groupid, $uid)";

    db_exec($sql2);
}
