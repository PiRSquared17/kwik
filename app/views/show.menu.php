<li>
    <input type="text" name="terms" id="text_field" accesskey="f" value="<?php echo $terms?>" title="Search or create a term [Ctrl+K]">
    <button name="search" type="submit" title="Searches for the term in existing pages">Search</button>
    <button name="new" type="button" title="Creates a page with the specified term, or leads to the page if already exists">Create</button>
</li>
<li><a href="/kwik/All" accesskey="q" title="Lists all pages this wiki stores">All pages</a></li>
<li><a href="/kwik/<?php echo $page?>/edit" accesskey="e" title="Changes this page to edition mode">Edit page</a></li>
