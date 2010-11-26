<?php defined('RENDER') or die('<h1>403 Forbidden</h1>');

$menu = '<li><a href="/kwik" title="Cancels page edition">Cancel</a></li>
         <li><button name="preview" type="submit" accesskey="p" title="Page preview, without saving">Preview</button></li>
         <li><button name="save" type="submit" accesskey="s" title="Saves changes to this page">Save</button></li>
         <li><button name="delete" type="submit" title="Deletes current page from disk, forever">Delete</button></li>';

$yield = '<span class="resizer" id="rowa" title="keep pressing to enlarge textbox">more</span>
          <span class="resizer" id="rowd" title="keep pressing to shrink textbox">less</span>
          <textarea name="content" rows="25" cols="80" class="prettyprint">' . $content . '</textarea>';

$js = '<script type="text/javascript" src="/kwik/public/jquery.js"></script>
       <script type="text/javascript">
           <!--
           var add = 0;
           $(".resizer").mousedown(function(){
               if ($(this).attr("id") == "rowa") add = 1;
               else add = -1;
               resizer();
           }).mouseup(function(){
               add = 0;
           });
           function resizer() {
               $("textarea").attr("rows",$("textarea").attr("rows") + add);
               if (add != 0) setTimeout(resizer, 30);
           }
           $("button[name=delete]").click(function(){
               return confirm("This will delete the current page. Are you sure?");
           });
           -->
       </script>';

require_once 'helpers/wikiformatter.php';
require_once 'layout.php';