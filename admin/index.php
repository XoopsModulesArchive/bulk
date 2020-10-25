<?php

//================================================================
// set table menu
// 2005-07-21 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';

xoops_cp_header();

$module_xoopsheadline = XoopsModule::getByDirname('xoopsheadline');
$module_xoopsfaq = XoopsModule::getByDirname('xoopsfaq');
$module_xoopspoll = XoopsModule::getByDirname('xoopspoll');
$module_sections = XoopsModule::getByDirname('sections');
$module_news = XoopsModule::getByDirname('news');
$module_mylinks = XoopsModule::getByDirname('mylinks');
$module_mydownloads = XoopsModule::getByDirname('mydownloads');
$module_newbb = XoopsModule::getByDirname('newbb');

echo '<h3>' . _BULK_IMPORT . "</h3>\n";
echo "<ul>\n";
echo "<li><a href='users.php'>" . _US_ADD_USERS . "</a><br><br></li>\n";

if ($module_xoopsheadline) {
    echo "<li><a href='xoopsheadline.php'>" . _HL_ADD_BULK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _HL_ADD_BULK . "<br><br></li>\n";
}

if ($module_xoopspoll) {
    echo "<li><a href='xoopspoll.php'>" . _PL_ADD_BULK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _PL_ADD_BULK . "<br><br></li>\n";
}

if ($module_xoopsfaq) {
    echo "<li><a href='xoopsfaq.php'>" . _XD_ADD_BULK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _XD_ADD_BULK . "<br><br></li>\n";
}

if ($module_sections) {
    echo "<li><a href='sections.php'>" . _SC_ADD_BULK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _SC_ADD_BULK . "<br><br></li>\n";
}

if ($module_news) {
    echo "<li><a href='news_topic.php'>" . _NW_ADD_BULK_TOPIC . "</a><br><br></li>\n";

    echo "<li><a href='news_story.php'>" . _NW_ADD_BULK_STORY . "</a><br><br></li>\n";
} else {
    echo '<li>' . _NW_ADD_BULK_TOPIC . "<br><br></li>\n";

    echo '<li>' . _NW_ADD_BULK_STORY . "<br><br></li>\n";
}

if ($module_mylinks) {
    echo "<li><a href='mylinks_cat.php'>" . _ML_ADD_BULK_CAT . "</a><br><br></li>\n";

    echo "<li><a href='mylinks_link.php'>" . _ML_ADD_BULK_LINK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _ML_ADD_BULK_CAT . "<br><br></li>\n";

    echo '<li>' . _ML_ADD_BULK_LINK . "<br><br></li>\n";
}

if ($module_mydownloads) {
    echo "<li><a href='mydownloads_cat.php'>" . _MD_ADD_BULK_CAT . "</a><br><br></li>\n";

    echo "<li><a href='mydownloads_down.php'>" . _MD_ADD_BULK_DOWN . "</a><br><br></li>\n";
} else {
    echo '<li>' . _MD_ADD_BULK_CAT . "<br><br></li>\n";

    echo '<li>' . _MD_ADD_BULK_DOWN . "<br><br></li>\n";
}

if ($module_newbb) {
    echo "<li><a href='newbb_cat.php'>" . _BB_ADD_BULK_CAT . "</a><br><br></li>\n";

    echo "<li><a href='newbb_forum.php'>" . _BB_ADD_BULK_FORUM . "</a><br><br></li>\n";

    echo "<li><a href='newbb_topic.php'>" . _BB_ADD_BULK_TOPIC . "</a><br><br></li>\n";
} else {
    echo '<li>' . _BB_ADD_BULK_CAT . "<br><br></li>\n";

    echo '<li>' . _BB_ADD_BULK_FORUM . "<br><br></li>\n";

    echo '<li>' . _BB_ADD_BULK_TOPIC . "<br><br></li>\n";
}

echo "</ul>\n";
echo '<h4>' . _BULK_IMPORT_FILE . "</h4>\n";
echo "<ul>\n";
echo "<li><a href='users_file.php'>" . _US_ADD_USERS . "</a><br><br></li>\n";

if ($module_xoopsheadline) {
    echo "<li><a href='xoopsheadline_file.php'>" . _HL_ADD_BULK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _HL_ADD_BULK . "<br><br></li>\n";
}

if ($module_xoopspoll) {
    echo "<li><a href='xoopspoll_file.php'>" . _PL_ADD_BULK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _PL_ADD_BULK . "<br><br></li>\n";
}

if ($module_xoopsfaq) {
    echo "<li><a href='xoopsfaq_file.php'>" . _XD_ADD_BULK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _XD_ADD_BULK . "<br><br></li>\n";
}

if ($module_sections) {
    echo "<li><a href='sections_file.php'>" . _SC_ADD_BULK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _SC_ADD_BULK . "<br><br></li>\n";
}

if ($module_news) {
    echo "<li><a href='news_file_topic.php'>" . _NW_ADD_BULK_TOPIC . "</a><br><br></li>\n";

    echo "<li><a href='news_file_story.php'>" . _NW_ADD_BULK_STORY . "</a><br><br></li>\n";
} else {
    echo '<li>' . _NW_ADD_BULK_TOPIC . "<br><br></li>\n";

    echo '<li>' . _NW_ADD_BULK_STORY . "<br><br></li>\n";
}

if ($module_mylinks) {
    echo "<li><a href='mylinks_file_cat.php'>" . _ML_ADD_BULK_CAT . "</a><br><br></li>\n";

    echo "<li><a href='mylinks_file_link.php'>" . _ML_ADD_BULK_LINK . "</a><br><br></li>\n";
} else {
    echo '<li>' . _ML_ADD_BULK_CAT . "<br><br></li>\n";

    echo '<li>' . _ML_ADD_BULK_LINK . "<br><br></li>\n";
}

if ($module_mydownloads) {
    echo "<li><a href='mydownloads_file_cat.php'>" . _MD_ADD_BULK_CAT . "</a><br><br></li>\n";

    echo "<li><a href='mydownloads_file_down.php'>" . _MD_ADD_BULK_DOWN . "</a><br><br></li>\n";
} else {
    echo '<li>' . _MD_ADD_BULK_CAT . "<br><br></li>\n";

    echo '<li>' . _MD_ADD_BULK_DOWN . "<br><br></li>\n";
}

if ($module_newbb) {
    echo "<li><a href='newbb_file_cat.php'>" . _BB_ADD_BULK_CAT . "</a><br><br></li>\n";

    echo "<li><a href='newbb_file_forum.php'>" . _BB_ADD_BULK_FORUM . "</a><br><br></li>\n";

    echo "<li><a href='newbb_file_topic.php'>" . _BB_ADD_BULK_TOPIC . "</a><br><br></li>\n";
} else {
    echo '<li>' . _BB_ADD_BULK_CAT . "<br><br></li>\n";

    echo '<li>' . _BB_ADD_BULK_FORUM . "<br><br></li>\n";

    echo '<li>' . _BB_ADD_BULK_TOPIC . "<br><br></li>\n";
}

echo "</ul>\n";

xoops_cp_footer();
exit();
// =====
