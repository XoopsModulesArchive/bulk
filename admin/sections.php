<?php

//================================================================
// sections: add bulk category & content
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/sections_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/sections_sjis.csv';

$op = get_post_op();

if ('sectionsAddBulk' == $op) {
    sectionsAddBulk();
} else {
    sectionsAddBulkForm($FILE);
}

exit();
// =====

function sectionsAddBulkForm($file)
{
    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF'); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _SC_ADD_BULK; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    <?php echo _SC_ADD_BULK_DSC1; ?><br>
                    <?php echo _SC_ADD_BULK_DSC2; ?><br>
                    <?php echo _SC_ADD_BULK_DSC3; ?><br><br>
                    <textarea name='faqlist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='sectionsAddBulk'><br><br>
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

function sectionsAddBulk()
{
    $line_arr = get_post_text_preg_split('faqlist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _SC_NO_TITLE);

        exit();
    }

    xoops_cp_header();

    print_title(_SC_ADD_BULK);

    // sections

    $line = array_shift($line_arr);

    $line = trim($line);

    [$secname, $image] = sections_get_sections($line);

    if (!sections_check_sections($secname)) {
        echo "<br>\n";

        echo '<b>' . _FINISH_FAULT . "</b><br>\n";

        echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

        xoops_cp_footer();

        exit();
    }

    $secid = sections_insert_sections($secname, $image);

    // seccont

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$title, $content] = sections_get_seccont($line);

        if (!sections_check_seccont($title, $content)) {
            continue;
        }

        sections_insert_seccont($secid, $title, $content);
    }

    echo "<br>\n";

    echo '<b>' . _SC_ARTICLE_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
