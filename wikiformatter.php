<?

$path = '/phiki';

function wikiformatter($t) {

    //primera parte de análisis global

    $t = str_replace("\r", '', $t);

    $t = preg_replace("/'''([^']*)'''/", '<strong>\\1</strong>', $t);
    $t = preg_replace("/''([^']*)''/", '<cite>\\1</cite>', $t);

    //imágenes
    $t = preg_replace('/\[\[Imagen?: ?([^\[\]]*)\]\]/', '<img src="img/\\1" alt="\\1" title="\\1" />', $t); //la n y el espacio son opcionales
    //enlaces internos
    $t = preg_replace('/\[\[([^\[\]\|]*)\|([^\[\]\|]*)\]\]/', '<a href="\\1">\\2</a>', $t);
    $t = preg_replace('/\[\[([^\[\]]*)\]\]/', '<a href="\\1">\\1</a>', $t);
    //enlaces al exterior
    $t = preg_replace('/\[([^ ]*) (.*)\]/', '<a href="\\1">\\2</a>', $t);
    $t = preg_replace('/\[(.*)\]/', '<a href="\\1">\\1</a>', $t);
 
    //segunda parte de análisis secuencial (troceando en líneas)
    
    $pre = false;
    $br_before = false;
    $ul = false;
    $ol = false;
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
        
        //línea vacía supone salto de línea, salvo que hayamos pintado antes otro salto de línea
        if (strlen($l) == 0 && $br_before == false) {
            $l = "<br />\n";
            $br_before = true;
        } else {
            $br_before = false;
        }

        //control de texto preformateado
        if (($l[0] != ' ') && ($pre == true)) {$t2 .= "</pre>\n"; $pre = false;}
        if (($l[0] == ' ') && ($pre == false)) {$t2 .= "<pre>\n"; $pre = true;}

        //error_log('['.date('Y/m/d H:i:s')."] $l \n", 3, 'this.log');
        if (($pre == false) && (preg_match('/[^"](https?:\/\/.*)[^"]/', $l)==1)) $l = preg_replace('/(https?:\/\/.*)/', '<a href="\\1">\\1</a>', $l); //enlace externo se ignora en modo PRE
        
        //control de tablas, se ignora en modo PRE
        if ($pre == false) {
            if (substr($l, 0, 2) == '{|') $l = '<table border=1><tr>';
            elseif (substr($l, 0, 2) == '|-') $l = '</tr><tr>';
            elseif (substr($l, 0, 2) == '|}') $l = '</tr></table>';
            elseif ($l[0] == '!') $l = '<th>'.substr($l, 1).'</th>';
            elseif ($l[0] == '|') $l = '<td>'.substr($l, 1).'</td>';
        } //TODO listas dentro de tablas
        
        //control de listas, se ignora en modo PRE
        if (($pre == false) && ($l[0] == '*')) {
            if (preg_match('/^([\*#]*)([^\*#]*)$/', $l, $match)) {
                $li_level = strlen($match[1]);
                //error_log('['.date('Y/m/d H:i:s')."] $match \n", 3, 'this.log');
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
        foreach ($t3 as $i) echo '<li>',$i,'</li>',"\n";
        echo '</ul></div>';
    }
    echo $t2;
}
