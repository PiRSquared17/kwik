<?
//TODO tablas e imágenes...
//TODO mercurial

function wikiformatter($t) {
    global $page;
    
    //primera parte de análisis global

    $t = str_replace("\r", '', $t);
/*
    $t = preg_replace('/==(\w*)==/', '<h2><a name="RND">\\1</a></h2>', $t);
    $t = preg_replace('/===(\w*)===/', '<h3><a name="RND">\\1</a></h3>', $t);
    $t = preg_replace('/====(\w*)====/', '<h4><a name="RND">\\1</a></h4>', $t);
    $t = preg_replace('/=====(\w*)=====/', '<h5><a name="RND">\\1</a></h5>', $t);
*/
    $t = preg_replace("/'''([^']*)'''/", '<strong>\\1</strong>', $t);
    $t = preg_replace("/''([^']*)''/", '<cite>\\1</cite>', $t);

    //$t = preg_replace('/\[\[([^\[\]\|]*)\|([^\[\]\|]*)\]\]/', '<a href="\\1">\\2</a>', $t);
    //$t = preg_replace('/\[\[([^\[\]]*)\]\]/', '<a href="\\1">\\1</a>', $t);
    $t = preg_replace('/\[([^ ]*) (.*)\]/', '<a href="\\1">\\2</a>', $t);
    $t = preg_replace('/\[(.*)\]/', '<a href="\\1">\\1</a>', $t);
 
    //segunda parte de análisis secuencial
    //trocea en líneas
    $ul = false;
    $ol = false;
    $pre = false;
    $li_level = 0;
    $li_level_old = 0;
    $t3 = array();
    $idx = 0;
    foreach (split("\n", $t) as $l) {
        //conteo de headings para el resumen y los anchor
        if ($l[0] == '=') {
            $anchor = rand();
            preg_match('/^(=*).*$/', $l, $level);
            $m = array();
            $nl = '';
            switch (strlen($level[1])) {
                case 2:
                    preg_match('/^==([^=]*)==$/', $l, $m);
                    $n = 2;
                    break;
                case 3:
                    preg_match('/^===([^=]*)===$/', $l, $m);
                    $n = 3;
                    break;
                case 4:
                    preg_match('/^====([^=]*)====$/', $l, $m);
                    $n = 4;
                    break;
                case 5:
                    preg_match('/^=====([^=]*)=====$/', $l, $m);
                    $n = 5;
                    break;
            }
            ++$idx;
            $spc = '';
            for ($i=2; $i<$n; $i++) $spc .= '&nbsp;';
            $t3[] = "$spc<a href=\"#$anchor\">$idx. {$m[1]}</a>";
            $l = "<h$n><a name=\"$anchor\">{$m[1]}</a></h$n>";
        }
        
        //línea vacía supone salto de línea
        if (strlen($l) == 0) $l = "<br />\n";

        //control de texto preformateado
        if (($l[0] != ' ') && ($pre == true)) {$t2 .= "</pre>\n"; $pre = false;}
        if (($l[0] == ' ') && ($pre == false)) {$t2 .= "<pre>\n"; $pre = true;} //TODO en modo pre debería obviar las listas

        //control de listas
        if ($l[0] == '*') {
            if (preg_match('/^([\*#]*)([^\*#]*)$/', $l, $match)) {
                $li_level = strlen($match[1]);
                //print_r($match);
            }
        } else {
            $li_level = 0;
        }
        
        if ($li_level > $li_level_old) {
            $t2 .= "<ul class=\"list\">\n";
        }

        if (($li_level == $li_level_old) && $li_level != 0) {
            $t2 .= "</li>\n";
        }

        if ($li_level < $li_level_old) {
            for ($i = 0; $i < $li_level_old-$li_level; $i++) {
                $t2 .= "</li>\n</ul>\n";
            }
            if ($li_level != 0) $t2 .= "</li>\n";
        }

        if ($li_level > 0) { //por circunstancia de headings no se puede hacer al vuelo con $t2 .= sino que hay que usar variables intermedias
            $t2 .= '<li>'.trim($match[2]);
        } else {
            $t2 .= trim($l)."\n";
        }

        $li_level_old = $li_level;

    }
    
    //imprimo los encabezados y luego el texto
    if (!empty($t3)) {
        echo '<div class="clearfix"><ul id="jumpers">';
        foreach ($t3 as $i) echo '<li>',$i,'</li>';
        echo '</ul></div>';
    }
    echo $t2;
}
