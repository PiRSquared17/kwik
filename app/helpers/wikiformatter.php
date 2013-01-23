<?php

/*
  Copyright (c) 2009 Daniel Cruz Horts

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  THE SOFTWARE.
 */

function wikiformatter($t, $split_output = false) {

    //first part: global analysis

    $t = str_replace("\r", '', $t);

    $t = str_replace('<', '&lt;', $t);
    $t = str_replace('>', '&gt;', $t);


    //second part: sequential analysis (line cutting)

    $pre = false;
    $br_before = false;
    $ul = false;
    $ol = false;
    $li_level = 0;
    $li_level_old = 0;
    $t3 = array();
    $idx = array();
    foreach (explode("\n", $t) as $l) {

        switch ($l[0]) {
            case '=': //headings counting, for anchors and digest
                $anchor = rand();
                preg_match('/^(=*).*$/', $l, $level);
                $nivel = strlen($level[1]); //the number of = matches heading number
                $m = array();
                $nl = '';
                switch ($nivel) { //begin replacements
                    case 2:
                        preg_match('/^==([^=]*)==$/', $l, $m);
                        $idx[0]++; //every time there is a new element in a certain level, nested levels are reset (it helps me avoid 'before' counters...)
                        $idx[1] = null;
                        $idx[2] = null;
                        $idx[3] = null;
                        break;
                    case 3:
                        preg_match('/^===([^=]*)===$/', $l, $m);
                        $idx[1]++;
                        $idx[2] = null;
                        $idx[3] = null;
                        break;
                    case 4:
                        preg_match('/^====([^=]*)====$/', $l, $m);
                        $idx[2]++;
                        $idx[3] = null;
                        break;
                    case 5:
                        preg_match('/^=====([^=]*)=====$/', $l, $m);
                        $idx[3]++;
                        break;
                }

                $nest = ''; //nesting level number
                for ($i = 0; $i < $nivel - 1; $i++)
                    $nest .= $idx[$i] . '.';

                $t3[] = '<a style="margin-left:' . ($nivel * 30 - 60) . "px\" href=\"#$anchor\">$nest{$m[1]}</a>";
                $l = "<h$nivel id=\"$anchor\">{$m[1]}</h$nivel>";
                break;
            case ' ': //controlling preformatted texts
                if ($pre == false) {
                    $li_level = 0;
                    if ($li_level < $li_level_old) {
                        for ($i = 0; $i < $li_level_old - $li_level; $i++) {
                            $t2 .= "</li>\n</ul>\n";
                        }
                        if ($li_level != 0)
                            $t2 .= "</li>\n";
                    }
                    $li_level_old = 0;
                    $t2 .= "<pre>\n";
                    $pre = true;
                }
                break;
            case '#': //lists control
            case '*':
                if ($pre == true) {
                    $t2 .= "</pre>\n";
                    $pre = false;
                }

                preg_match('/^([\*#]*)(.*)$/', $l, $level);
                $li_level = strlen($level[1]); //counting * or # in li_level
                $l = $level[2];

                if ($li_level < $li_level_old) {
                    for ($i = 0; $i < $li_level_old - $li_level; $i++) {
                        $t2 .= "</li>\n</ul>\n";
                    }
                    if ($li_level != 0)
                        $t2 .= "</li>\n";
                }

                if ($li_level > $li_level_old) {
                    $t2 .= "<ul class=\"list\">\n";
                }

                if (($li_level == $li_level_old) && $li_level != 0) {
                    $t2 .= "</li>\n";
                }

                break;
            default:
                //an empty line means line feed, unless there is another line feed right before
                if (strlen($l) == 0 && $br_before == false) {
                    $l = "<br>\n";
                    $br_before = true;
                } else {
                    $br_before = false;
                }

                $li_level = 0;
                if ($li_level < $li_level_old) {
                    for ($i = 0; $i < $li_level_old - $li_level; $i++) {
                        $t2 .= "</li>\n</ul>\n";
                    }
                    if ($li_level != 0)
                        $t2 .= "</li>\n";
                }
                $li_level_old = 0;

                if ($pre == true) {
                    $t2 .= "</pre>\n";
                    $pre = false;
                }
        }

        if ($pre == false) {

            //error_log('['.date('Y/m/d H:i:s')."] :$l:\n", 3, 'this.log');
            //bold, italics and links control
            $l = preg_replace("/'''([^']*)'''/", '<strong>\\1</strong>', $l);
            $l = preg_replace("/''([^']*)''/", '<cite>\\1</cite>', $l);
            //images
            $l = preg_replace('/\[\[Imagen?: ?([^\[\]]*)\]\]/', '<img src="img/\\1" alt="\\1" title="\\1">', $l); //the n and the space are optional
            //internal links
            $l = preg_replace('/\[\[([^\[\]\|]*)\|([^\[\]\|]*)\]\]/', '<a href="/\\1">\\2</a>', $l);
            $l = preg_replace('/\[\[([^\[\]]*)\]\]/', '<a href="/\\1">\\1</a>', $l);
            //external links
            $l = preg_replace('/\[([^ ]+) ([^\]]+)\]/', '<a href="\\1">\\2</a>', $l);
            $l = preg_replace('/\[(.*)\]/', '<a href="\\1">\\1</a>', $l);

            //table control, it doesn't support lists inside tables yet
            if (substr($l, 0, 2) == '{|')
                $l = '<table class="table table-bordered table-striped"><tr>';
            elseif (substr($l, 0, 2) == '|-')
                $l = '</tr><tr>';
            elseif (substr($l, 0, 2) == '|}')
                $l = '</tr></table>';
            elseif ($l[0] == '!')
                $l = '<th>' . substr($l, 1) . '</th>';
            elseif ($l[0] == '|')
                $l = '<td>' . substr($l, 1) . '</td>';

            if (preg_match('/http(s?:\/\/[\w\/\.\?#=\-_%@\+&:~]*)/', $l, $m) == 1) { //searches for scattered external links: i can't do preg_replace because it will destroy the links previously found
                $p = strpos($l, $m[0]);
                if ($p == 0 || $l{$p - 1} != '"') { //TODO using {} for strings will be deprecated by PHP 6
                    $l = str_replace($m[0], "<a href=\"{$m[0]}\">{$m[0]}</a>", $l);
                }
            }

            $l = trim($l);
        } else {
            $l = rtrim(substr($l, 1));
        }

        if ($li_level > 0)
            $t2 .= '<li>';

        $t2 .= $l;
        $t2 .= "\n";

        $li_level_old = $li_level;
    }


    //finished parsing, printing final text
    //printing headings and digest (if there are more than 3) and then the text
    $jumpers = '';

    if (count($t3) > 3) {
        $jumpers .= "<ul class=\"breadcrumb\" id=\"jumpers\">\n";
        foreach ($t3 as $i) {
            $jumpers .= "<li>$i</li>\n";
        }
        $jumpers .= "</ul>\n";
    }

    if ($split_output) {

        return array($jumpers, $t2);
        
    } else {

        return $jumpers . $t2;
    }
}
