<?php

// 2005-08-10 K.OHWADA
// some changes

//================================================================
// users: add bulk
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/users_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/users_sjis.csv';

$op = get_post_op();

switch ($op) {
    case 'addUsers':
        addUsers();
        break;
    case 'addUsersCheck':
        addUsersCheck();
        break;
    case 'menu':
    default:
        addUsersForm($FILE);
        break;
}

exit();
// =====

function addUsersForm($file)
{
    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF'); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _US_ADD_USERS; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    <?php echo _US_ADD_USERS_DSC; ?><br><br>
                    <textarea name='userlist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='addUsersCheck'><br><br>
                    <input type='submit' value='<?php echo _ADD; ?>'>
                    <input type='button' value='<?php echo _BACK; ?>' onclick='javascript:history.go(-1)'><br>
                </form>
                <form action='view_sjis_file.php' method='post' target='_blank'>
                    <input type='hidden' name='file' value='<?php echo $file; ?>'><br><br>
                    <input type='submit' value=' <?php echo _SAMPLE; ?> '><br>
                </form>
            </td>
        </tr>
    </table>
    <br>
    <?php

    xoops_cp_footer();
}

function addUsersCheck()
{
    $userlist = get_post_text('userlist');

    $line_arr = preg_split("\n", $userlist);

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _US_NO_USER);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _US_ADD_USERS . "</h4>\n";

    $table_users = db_prefix('users');

    $flag_err = 0;

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$uname, $email, $pass] = users_get_param($line);

        $ret = users_check($uname, $email, $pass);

        if (0 != $ret) {
            $flag_err = 1;
        }
    }

    $action = xoops_getenv('PHP_SELF');

    echo "<br><br>\n";

    echo "<form action='" . $action . "' method='post'>\n";

    if (!$flag_err) {
        echo "<input type='hidden' name='userlist' value='$userlist'>\n";

        echo "<input type='hidden' name='op' value='addUsers'><br><br>\n";

        echo "<input type='submit' value='" . _GO . "'>\n";
    }

    echo "<input type='button' value='" . _BACK . "' onclick='javascript:history.go(-1)'>";

    echo "</form><br>\n";

    xoops_cp_footer();
}

function addUsers()
{
    $line_arr = get_post_text_preg_split('userlist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _US_NO_USER);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _US_ADD_USERS . "</h4>\n";

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$uname, $email, $pass] = users_get_param($line);

        $ret = users_check($uname, $email, $pass);

        if (0 != $ret) {
            continue;
        }

        users_insert($uname, $email, $pass);
    }

    echo "<br>\n";

    echo '<b>' . _US_NEW_USER_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
