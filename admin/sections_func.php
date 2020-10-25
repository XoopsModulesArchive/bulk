<?php

//================================================================
// sections: functions
// 2005-07-24 K.OHWADA
//================================================================

function sections_file($file)
{
    print_title(_SC_ADD_BULK);

    if (!print_error_file_not_exists($file)) {
        return;
    }

    $line_arr = file($file);

    $flag_line = 0;

    $secid = 0;

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
            [$secname, $image] = sections_get_sections($line, "\t");

            if (!sections_check_sections($secname, $image)) {
                echo "<br>\n";

                echo '<b>' . _FINISH_FAULT . "</b><br>\n";

                return;
            }

            $secid = sections_insert_sections($secname, $image);

            $flag_line = 1;
        } // seccont

        elseif (1 == $flag_line) {
            [$title, $content] = sections_get_seccont($line, "\t");

            if (!sections_check_seccont($title, $content)) {
                continue;
            }

            sections_insert_seccont($secid, $title, $content);
        } else {
            print_error('system error');
        }
    }

    echo "<br>\n";

    echo '<b>' . _SC_ARTICLE_ADDED . "</b><br>\n";
}

function sections_get_sections($line, $pattern = ',')
{
    $secname = '';

    $image = '';

    [$secname, $image] = split_line($line, $pattern);

    echo str_trim_html("$secname, $image");

    echo "<br>\n";

    return [$secname, $image];
}

function sections_check_sections($secname)
{
    if (empty($secname)) {
        print_error(_SC_NO_CAT);

        return false;
    }

    return true;
}

function sections_get_seccont($line, $pattern = ',')
{
    $title = '';

    $content = '';

    [$title, $content] = split_line($line, $pattern);

    $str = "$title, $content";

    echo str_trim_html($str);

    echo "<br>\n";

    $content = str_replace_crlf($content);

    return [$title, $content];
}

function sections_check_seccont($title, $content)
{
    if (empty($title)) {
        print_error(_SC_NO_TITLE);

        return false;
    }

    if (empty($content)) {
        print_error(_SC_NO_CONT);

        return false;
    }

    return true;
}

function sections_insert_sections($secname, $image)
{
    $table_sections_sections = db_prefix('sections');

    if (empty($image)) {
        $image = 'sections_slogo.png';
    }

    $secname = addslashes($secname);

    $$image = addslashes($$image);

    $sql = "INSERT INTO $table_sections_sections (secname, image) VALUES ('$secname', '$image')";

    db_exec($sql);

    return db_get_newid();
}

function sections_insert_seccont($secid, $title, $content)
{
    $table_sections_seccont = db_prefix('seccont');

    $title = addslashes($title);

    $content = addslashes($content);

    $sql = "INSERT INTO $table_sections_seccont (secid, title, content) VALUES ($secid, '$title', '$content')";

    db_exec($sql);
}
