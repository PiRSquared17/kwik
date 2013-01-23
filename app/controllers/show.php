<?php

function get() {
    global $page, $unparsed_content, $page_pretty, $terms, $jumpers;

    if (array_key_exists('terms', $_REQUEST)) {

        $terms = empty($_REQUEST['terms']) ? $page : $_REQUEST['terms'];

        $page_pretty = 'Page search...';

        $unparsed_content = "==Search results==\n";
        $unparsed_content .= "===Page name matches===\n";
        $search = `cd pages; ls`;
        $found = false;
        foreach (explode("\n", $search) as $l) {
            if (strpos(strtolower($l), strtolower($terms)) !== false) {
                $unparsed_content .= "*[[$l]]\n";
                $found = true;
            }
        }
        if (!$found) {
            $unparsed_content .= "Nothing found... Click the Create button above to create a page for ''$terms''.\n";
        }

        $unparsed_content .= "\n===Page content matches===\n";
        $search = `cd pages; grep '$terms' *`; //TODO case insensitive search
        $el_ant = '';
        foreach (explode("\n", $search) as $l) {
            $el = explode(':', $l, 2);
            if (count($el) > 1) {
                if ($el_ant != $el[0]) {
                    $unparsed_content .= "*[[{$el[0]}]]\n";
                }
                $unparsed_content .= " {$el[1]} \n";
                $el_ant = $el[0];
            }
        }

    } else {

        $terms = '';

        if ($page == 'All') {
            $unparsed_content = "==All pages==\n";
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
                $unparsed_content .= "*[[$p]]\n";
            }
        } else {
            if (file_exists("pages/$page")) {
                $unparsed_content = file_get_contents("pages/$page");
            } else {
                $terms = $page;
                $unparsed_content = "Page doesn't exist. Click on the Create button to create it.";
            }
        }
    }
}
