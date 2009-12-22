<?

$path = '/phiki';

function wikiformatter($t) {

    //primera parte de análisis global

    $t = str_replace("\r", '', $t);

    $t = str_replace('<', '&lt;', $t);
    $t = str_replace('>', '&gt;', $t);


    //segunda parte de análisis secuencial (troceando en líneas)
    
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
            case '=': //conteo de headings para el resumen y los anchor
                $anchor = rand();
                preg_match('/^(=*).*$/', $l, $level);
                $nivel = strlen($level[1]); //el número de = coincide con el heading
                $m = array();
                $nl = '';
                switch ($nivel) { //realizo las sustituciones
                    case 2:
                        preg_match('/^==([^=]*)==$/', $l, $m);
                        $idx[0]++; //cada vez que hay un nuevo elemento a un cierto nivel, se resetean los niveles anidados (me evita mantener contadores de anterior...)
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
                
                $nest = ''; //número nivel anidación
                for ($i=0; $i<$nivel-1; $i++) $nest .= $idx[$i].'.';
                
                $t3[] = '<a style="margin-left:' . ($nivel*30-60) . "px\" href=\"#$anchor\">$nest{$m[1]}</a>";
                $l = "<h$nivel><a name=\"$anchor\">{$m[1]}</a></h$nivel>";
                break;
            case ' ': //control de texto preformateado
                if ($pre == false) {
                    $li_level = 0;
                    if ($li_level < $li_level_old) {
                        for ($i = 0; $i < $li_level_old-$li_level; $i++) {
                            $t2 .= "</li>\n</ul>\n";
                        }
                        if ($li_level != 0) $t2 .= "</li>\n";
                    }
                    $li_level_old = 0;
                    $t2 .= "<pre>\n";
                    $pre = true;
                }
                break;
            case '#': //control de listas
            case '*':
                if ($pre == true) {$t2 .= "</pre>\n"; $pre = false;}
                
                preg_match('/^([\*#]*)(.*)$/', $l, $level);
                $li_level = strlen($level[1]); //contando los * o # es el li_level
                $l = $level[2];
                
                if ($li_level < $li_level_old) {
                    for ($i = 0; $i < $li_level_old-$li_level; $i++) {
                        $t2 .= "</li>\n</ul>\n";
                    }
                    if ($li_level != 0) $t2 .= "</li>\n";
                }
                
                if ($li_level > $li_level_old) {
                    $t2 .= "<ul class=\"list\">\n";
                }

                if (($li_level == $li_level_old) && $li_level != 0) {
                    $t2 .= "</li>\n";
                }

                break;
            default:
                //línea vacía supone salto de línea, salvo que hayamos pintado antes otro salto de línea
                if (strlen($l) == 0 && $br_before == false) {
                    $l = "<br />\n";
                    $br_before = true;
                } else {
                    $br_before = false;
                }
                
                $li_level = 0;
                if ($li_level < $li_level_old) {
                    for ($i = 0; $i < $li_level_old-$li_level; $i++) {
                        $t2 .= "</li>\n</ul>\n";
                    }
                    if ($li_level != 0) $t2 .= "</li>\n";
                }
                $li_level_old = 0;
                
                if ($pre == true) {$t2 .= "</pre>\n"; $pre = false;}
        }

        if ($pre == false) {

            //error_log('['.date('Y/m/d H:i:s')."] :$l:\n", 3, 'this.log');
            
            //control de negritas, cursivas y enlaces
            $l = preg_replace("/'''([^']*)'''/", '<strong>\\1</strong>', $l);
            $l = preg_replace("/''([^']*)''/", '<cite>\\1</cite>', $l);
            //imágenes
            $l = preg_replace('/\[\[Imagen?: ?([^\[\]]*)\]\]/', '<img src="img/\\1" alt="\\1" title="\\1" />', $l); //la n y el espacio son opcionales
            //enlaces internos
            $l = preg_replace('/\[\[([^\[\]\|]*)\|([^\[\]\|]*)\]\]/', '<a href="\\1">\\2</a>', $l);
            $l = preg_replace('/\[\[([^\[\]]*)\]\]/', '<a href="\\1">\\1</a>', $l);
            //enlaces al exterior
            $l = preg_replace('/\[([^ ]*) (.*)\]/', '<a href="\\1">\\2</a>', $l);
            $l = preg_replace('/\[(.*)\]/', '<a href="\\1">\\1</a>', $l);

            //control de tablas, no soporta listas dentro de tablas
            if (substr($l, 0, 2) == '{|') $l = '<table><tr>';
            elseif (substr($l, 0, 2) == '|-') $l = '</tr><tr>';
            elseif (substr($l, 0, 2) == '|}') $l = '</tr></table>';
            elseif ($l[0] == '!') $l = '<th>'.substr($l, 1).'</th>';
            elseif ($l[0] == '|') $l = '<td>'.substr($l, 1).'</td>';
            
            if (preg_match('/http(s?:\/\/[\w\/\.\?#=\-_%@\+:]*)/', $l, $m)==1) { //busca enlaces externos sueltos, no se puede hacer preg_replace pues estropea los ya encontrados
                $p = strpos($l, $m[0]);
                if ($p == 0 || $l{$p-1} != '"') { //TODO {} para cadenas será deprecado en PHP 6
                    $l = str_replace($m[0], "<a href=\"{$m[0]}\">{$m[0]}</a>", $l);
                }
            }
        }

        if ($li_level > 0) $t2 .= '<li>';

        $t2 .= trim($l)."\n";

        $li_level_old = $li_level;
    }
    
    
    //fin de parseo, impresión del texto final
    //imprimo los encabezados (si hay más de 3) y luego el texto
    if (count($t3)>3) {
        echo "<div class=\"clearfix\"><ul id=\"jumpers\">\n";
        foreach ($t3 as $i) echo '<li>',$i,'</li>',"\n";
        echo "</ul></div>\n";
    }
    echo $t2;
}
