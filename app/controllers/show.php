<?php

$form_for = 'action="/kwik/" method="get"';

function get() {
    global $page, $content, $page_pretty, $terms;

    if (array_key_exists('terms', $_REQUEST)) {

        $terms = empty($_REQUEST['terms']) ? $page : $_REQUEST['terms'];

        $page_pretty = 'Page search...';

        $content = "==Search results==\n";
        $content .= "===Page name matches===\n";
        $search = `cd pages; ls`;
        $found = false;
        foreach (explode("\n", $search) as $l) {
            if (strpos(strtolower($l), strtolower($terms)) !== false) {
                $content .= "*[[$l]]\n";
                $found = true;
            }
        }
        if (!$found) {
            $content .= "Nothing found... Click the Create button above to create a page for ''$terms''.\n";
        }

        $content .= "\n===Page content matches===\n";
        $search = `cd pages; grep '$terms' *`; //TODO case insensitive search
        $el_ant = '';
        foreach (explode("\n", $search) as $l) {
            $el = explode(':', $l, 2);
            if (count($el) > 1) {
                if ($el_ant != $el[0]) {
                    $content .= "*[[{$el[0]}]]\n";
                }
                $content .= " {$el[1]} \n";
                $el_ant = $el[0];
            }
        }

    } else {

        $terms = '';

        if ($page == 'All') {
            $content = "==All pages==\n*[[Main_page]]\n\n";
            $pages = array();
            if ($h = @opendir('pages')) {
                while (false !== ($f = readdir($h))) {
                    if ($f[0] != '.' && $f[0] != 'Main_page') {
                        $pages[] = $f;
                    }
                }
                closedir($h);
            } else
                @mkdir('pages');
            natsort($pages);
            foreach ($pages as $p) {
                $content .= "*[[$p]]\n";
            }
        } else {
            if (file_exists("pages/$page")) {
                $content = file_get_contents("pages/$page");
            } else {
                $terms = $page;
                $content = "Page doesn't exist. Click on the link to create it.";
            }
        }
    }
}
