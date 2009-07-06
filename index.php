<?
require_once 'wikiformatter.php';

$page = $_GET['page'];
if (empty($page)) $page = 'Portada';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?=$page?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link type="text/css" rel="stylesheet" media="all" href="/res/phiki.css" />
		<!--script type="text/javascript" src="/res/jquery.js"></script>
		<script type="text/javascript" src="/res/jquery.corner.js"></script>
		<script type="text/javascript">
		    $(document).ready(function() {
    		    $('#contents').corner();
    		    $('#menu a').corner();
		    });
		</script-->
    </head>
    <body>
        <div id="header">
        
        </div>
        <div id="contents">
            <ul id="menu">
                <li><a href="/<?=$page?>/edit">Edit page</a></li>
                <li><a href="/<?=$page?>/new">New page</a></li>
            </ul>
<?


$content = file_get_contents("pages/$page");
wikiformatter($content);


?>
        </div>
    </body>
</html>
