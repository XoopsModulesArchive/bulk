<?php

//================================================================
// set users table for JCAFE school site
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/users_func.php';

$op = get_post_op();

switch ($op) {
    case 'go':
        create();
        break;
    case 'menu':
    default:
        print_form();
        break;
}

exit();
// =====

function print_form()
{
    xoops_cp_header();

    echo '<h4>' . _US_ADD_USERS . "</h4>\n";

    $action = xoops_getenv('PHP_SELF'); ?>
    <form action='<?php echo $action; ?>' method='post'>
        <input type='hidden' name='op' value='go'>
        <?php echo _US_PASSWORD; ?> <input type='text' name='pass'><br>
        <br>
        <input type='submit' value='<?php echo _GO; ?>'>
    </form>
    <?php

    xoops_cp_footer();
}

function create()
{
    $MAX_USER = 30;

    $pass = get_post_text('pass');

    if (empty($pass)) {
        redirect_header('index.php', 2, _US_NO_PASS);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _US_ADD_USERS . "</h4>\n";

    for ($i = 0; $i < $MAX_USER; $i++) {
        $uname = 'u' . sprintf('%03d', $i);

        $email = $uname . '@exsample.com';

        echo str_trim_html("$uname, $email, $pass");

        echo "<br>\n";

        users_insert($uname, $email, $pass);
    }

    echo "<br>\n";

    echo '<b>' . _US_NEW_USER_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
