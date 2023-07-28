<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
<div id="search-inputs">
<input type="text" value="<?php if (get_search_query() != 'all') {the_search_query();} ?>" name="s" id="s" />
<input type="submit" id="searchsubmit" value="Search" />
</div>
</form>