<?php

//=========================================================
// mylinks: add bulk links
// 2005-07-12 K.OHWADA
//=========================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/mylinks_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/mylinks_links_sjis_1.csv';

$op = get_post_op();

if ('mylinksAddLinks' == $op) {
    mylinksAddLinks();
} else {
    mylinksAddLinksForm($FILE);
}

exit();
// =====

function mylinksAddLinksForm($file)
{
    global $mytree;

    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF');

    $selbox = mylinks_make_selbox(0); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _ML_ADD_BULK_LINK; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    &nbsp; <?php echo _MD_IN; ?> &nbsp; <?php echo $selbox; ?><br><br>
                    <?php echo _ML_ADD_BULK_LINK_DSC; ?><br><br>
                    <textarea name='linklist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='mylinksAddLinks'><br><br>
                    <input type='submit' value=' <?php echo _ADD; ?> '>
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

function mylinksAddLinks()
{
    $cid = get_post_int('cid');

    $line_arr = get_post_text_preg_split('linklist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _ML_NO_TITLE);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _ML_ADD_BULK_LINK . "</h4>\n";

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$title, $url, $description] = mylinks_get_links($line);

        if (!mylinks_check_links($title, $url, $description)) {
            continue;
        }

        mylinks_insert_links($cid, $title, $url, $description);
    }

    echo "<br>\n";

    echo '<b>' . _MD_NEWLINKADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
