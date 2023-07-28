<style>body, pre, code { font-family:"Monaco", "Menlo",  "Consolas", "Courier New", monospace; }
body { margin: 5% 10%; } pre { font-size: 0.8em; } pre span { color: blue; } .error { color: red; } 
.success { color: green; }</style>
<?php

    define('WP_USE_THEMES', false);
    global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
    require($_SERVER["DOCUMENT_ROOT"].'/wp-load.php');
    global $wpdb;
    $wpdb->show_errors();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define( 'DIEONDBERROR', true ); // Multisite support
    $url = 'migrate.php';
    timer_start();
    $output_count = 0;
    $query_memory = '';
    $time_start = microtime_float();
    $url = 'migrate.php';

    // Title
    echo '<p><a href="'.$url.'">Go home</a></p><h1>Upgrading improvements 2015/07/21</h1>';

    
    // Allow options
    $inputs = array();
    foreach (array('dbmigrate','if_not_exists','drop', 'no_create', 'skip', 'filemigrate', 'availability', 'prices') as $input) {
         $inputs[$input] = (isset($_GET[$input]) ? $_GET[$input] : "0");
    }

    // Output queries
    function outputFromQuery($wpdb, $output) {
        global $query_memory;
        global $output_count;
        // Check all queries output are different to last query
        echo '
<pre>['.$output_count++.'] Query output (timer, query and output):<br/>
<span>Time: '.timer_stop(0,5).' s</span><br/><br/>';
        var_dump($wpdb->last_query);
        var_dump($output); 
        echo '<br/>-------</pre>';
        if ($query_memory == $wpdb->last_query) {
            trigger_error("Last query run was identical."); exit;
        }
    }
    
    // Output anything else
    function outputFromOther($output) {
        global $query_memory;
        global $output_count;
        echo '
<pre>['.$output_count++.'] Generic output (timer and output):<br/>
<span>Time: '.timer_stop(0,5).' s</span><br/><br/>';
        var_dump($output); 
        echo '<br/>-------</pre>';
    }
    
    // Get float of time
    function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
   
    // Standard homepage with confirmation options to make sure we do the right things.
    if ($inputs['dbmigrate'] != "1" && $inputs['filemigrate'] != "1") {
        writeIntro();
        exit;
    }
    
    function writeIntro() {
        global $url;
        echo "<h3>Readme</h3>
            <p>This migration will create new tables for optimizing search while not modifying existing tables.</p>
            <h3>Options</h3>
            <p>Please select your option to continue:</p>
            <ol>
            <li><a href='".$url."?dbmigrate=1'>Migration with <code>CREATE TABLE</a></li>
            <li><a href='".$url."?dbmigrate=1&if_not_exists=1'>Migration with <code>CREATE TABLE IF NOT EXISTS</code></a></li>
            <li><a href='".$url."?dbmigrate=1&no_create=1'>Migration without <code>CREATE TABLE</code></a></li>
            <li><a href='".$url."?dbmigrate=1&drop=1'>Drop new tables</code></a></li>
            <li><a href='".$url."?dbmigrate=1&drop=2'>Drop with <code>IF EXISTS</code> then standard migration</code></a></li>
            <li><a href='".$url."?filemigrate=1'>Migrate logo files</code></a></li>
            <li><a href='".$url."?availability=1'>Migrate availability</code></a></li>
            <li><a href='".$url."?prices=1'>Migrate prices</code></a></li>
            </ol>
        ";
    }

    
    // Database migration
    if ($inputs['dbmigrate'] == "1") {
        // Drop existing tables?
        if ($inputs["drop"] == "1" OR $inputs["drop"] == "2") {
            $if_exists = ($inputs['drop'] == 2 ? 'IF EXISTS ' : '');
            echo '<h3 class="error">Dropping tables '.$if_exists.'</h3>';
            dropTables($if_exists);
            echo '<h4 class="success">Drop tables completed.</h3><p>-----</p>';
        }
        // Create new tables?
        if ($inputs["no_create"] != "1") {
            $if_not_exists = ($inputs['if_not_exists'] == "1" ? "IF NOT EXISTS " : "");
            echo '<h3>Create new table '.$if_not_exists.'</h3>';
            createTables($if_not_exists);
            echo '<h4 class="success">Create new table completed.</h3><p>-----</p>';
        }
        // Import content
        echo '<h3>Import content about all houses in all sites</h3>';
        importContent($inputs['skip']);
        echo '<h4 class="success">Import content completed.</h3><p>-----</p>';
        exit;
    }
    
    // File migration 
    if ($inputs['filemigrate'] == "1") {
        echo '<h3>Migrate files</h3>';
        fileMigrate();
        echo '<h4 class="success">Migrate files completed.</h3><p>-----</p>';
        exit;
    }
    
    if ($inputs['availability'] == "1") {
        echo '<h3>Migrate availability</h3>';
        availabilityMigrate();
        echo '<h4 class="success">Migrate availability completed.</h3><p>-----</p>';
        exit;
    }
    
    if ($inputs['prices'] == "1") {
        echo '<h3>Migrate prices</h3>';
        pricesMigrate();
        echo '<h4 class="success">Migrate prices completed.</h3><p>-----</p>';
        exit;
    }
    
    function availabilityMigrate() {
        
    }
    
    function pricesMigrate() {
        
    }

    function fileMigrate() {
        global $url;
        

        $sites = wp_get_sites();
        if (count($sites) === 0) {
            trigger_error("No sites found."); exit;
        }
        
        $root = $_SERVER["DOCUMENT_ROOT"];
        $new_folder = '/wp-content/themes/clubsandwich/images/';
        
        foreach ($sites as $key => $site) {
            $blog_id = $site['blog_id'];
            $site_id = $site['site_id'];
            $blog_name = get_blog_details($blog_id)->blogname;
            
            echo '<h4># '.$blog_name.' ("blog_id" = '.$blog_id.', "site_id" = '.$site_id.')</h4>';
            switch_to_blog($blog_id);
            $current_logo = get_field('site_logo', 'option');
            if (is_string($current_logo)) {
                $local_path_array = explode('/wp-content', $current_logo);
                $local_path = $root.'/wp-content'.$local_path_array[1];
                $file_name = 'site-logo-'.$blog_id.'.png';
                $new_path = $root.$new_folder.$file_name;
                outputFromOther(array($local_path, $new_path));
                $output = copy($local_path, $new_path);
                if ($output) {
                    echo '<img style="border:2px solid grey; padding:3px;" src="'.$new_folder.$file_name.'"/>
                        <p class="success">Copy successful.</p>';
                }
                else {
                    echo '<img style="border:2px solid grey; padding:3px;" src="'.$new_folder.$file_name.'"/>
                        <p class="error">Copy failed.</p>';
                }
            }
            else {
                echo 'No logo.';
            }
        }
    
    }
    
    function dropTables($if_exists) {
        global $wpdb;
        global $inputs;
        $output = $wpdb->get_results("DROP TABLE ".$if_exists."houses");
        outputFromQuery($wpdb, $output);
        $output = $wpdb->get_results("DROP TABLE ".$if_exists."availability");
        outputFromQuery($wpdb, $output);
        $output = $wpdb->get_results("DROP TABLE ".$if_exists."rates");
        outputFromQuery($wpdb, $output);
        if ($inputs["drop"] == "1") exit;
    }


    function createTables($if_not_exists) {   
        global $wpdb;
        // Houses table
    	$output = $wpdb->get_results("
CREATE TABLE ".$if_not_exists."`houses` (
  `house_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `post_title` longtext NOT NULL,
  `permalink` longtext NOT NULL,
  `post_thumbnail` varchar(255) NOT NULL DEFAULT '',
  `availability_option` tinyint(1) NOT NULL DEFAULT '0',
  `availability_site_ref` bigint(20) unsigned NOT NULL DEFAULT '0',
  `availability_site_post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `location` longtext,
  `location_text` longtext,
  `locations` longtext,
  `sleeps_min` float unsigned,
  `sleeps_max` float unsigned NOT NULL,
  `brief_description` longtext,
  `brief_description_winter` longtext,
  `all_prices_with_from` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`house_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6873002 DEFAULT CHARSET=utf8;
");
        outputFromQuery($wpdb, $output);
        
        // Availability table
    	$output = $wpdb->get_results("
CREATE TABLE ".$if_not_exists."`availability` (
  `availability_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `month` longtext NOT NULL,
  `booked_days` longtext NOT NULL,
  PRIMARY KEY (`availability_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
");
        outputFromQuery($wpdb, $output);
        
        // Rates table
    	$output = $wpdb->get_results("
CREATE TABLE ".$if_not_exists."`rates` (
  `pricing_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `month` longtext NOT NULL,
  `rates` longtext NOT NULL,
  PRIMARY KEY (`pricing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
");
        outputFromQuery($wpdb, $output);
        
        
        
    }
    
    
    function importContent($skip) {
        global $wpdb;
        // Check how many rows are in the table
        $c = $wpdb->get_var("SELECT count(*) FROM houses");
        if ($c > 0) {
            echo '<p class="error">Notice: There are '.$c.' existing rows. This procedure will add, not update.';
        }
      
        // Migrate all blogs in the network (or all blogs in the 'site')
        function migrateAllBlogs($skip) {
            global $url;
            global $time_start;    
    
            $sites = wp_get_sites();
            if (count($sites) === 0) {
                trigger_error("No sites found."); exit;
            }
            // Allow a user to skip to a site for timeouts.
            if ($skip !== 0) {
                echo '<p class="error">Skipping at point '.$skip.'</p>';
                $sites = array_slice($sites, $skip); 
            }
            foreach ($sites as $key => $site) {
                $blog_id = $site['blog_id'];
                $site_id = $site['site_id'];
                $blog_name = get_blog_details($blog_id)->blogname;
                
                echo '<h4># '.$blog_name.' ("blog_id" = '.$blog_id.', "site_id" = '.$site_id.')</h4>';
    
                migrateBlog($blog_id);
                
                // Prevent timeouts by just doing 5 seconds at a time.
                $time_end = microtime_float();
                if ($time_end - $time_start > 5 && $key !== max(array_keys($sites))) {
                    echo '<p>Completed to "blog_id" = '.$blog_id.'. <a class="error" href="'.$url.'?dbmigrate=1&no_create=1&skip='.($key+$skip+1).'">Continue ></a><p>';
                    exit;
                }
            }
        }
        
        // Migrate a specified blog
        function migrateBlog($blog_id) {
            switch_to_blog($blog_id);
            
            $args = array(
              'post_type' => 'houses',
              'post_status' => 'publish', // Should there be other statuses too?
              'posts_per_page' => -1);
            
            $query = new WP_Query($args);
            if ( $query->have_posts() ) {
                echo '<p>Total houses found in site: '.$query->found_posts.'</p>';
                while ($query->have_posts()) {
                    migrateHouse($query, $blog_id);
                }
            }
            else {
                trigger_error('No houses found for blog ID '.$blog_id);
            }
        }
        
        // Migrate a specific house
        // Input: takes a query which still has posts left
        function migrateHouse($query, $blog_id) {
            global $wpdb;
            $query->the_post();
            
            // Check we are on the right blog
            if ($blog_id != get_current_blog_id()) {
                echo '<p class="error">Error: $blog id ['.$blog_id.'] !== current_blog_id ['.
                    get_current_blog_id().']</p>'; exit;
            }
            
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $permalink = get_post_permalink();
            
            echo '<p>## '.$post_title.' ("blog_id" = '.$blog_id.', "post_id" = '.$post_id.')</p>';
            
            // Metadata
            $m = get_post_meta($post_id);
            
            // availability_calendar is count of months
            $month_count = intval($m['availability_calendar'][0]);
            for ($c=0; $c<$month_count; $c++) {
           
                // availability_calendar_0_month is the month
                $name = 'availability_calendar_'.$c.'_month';
                $month = $m[$name][0];
                
                // availability_calendar_0_availability-days
                $name = 'availability_calendar_'.$c.'_availability-days';
                $month_booked_days = $m[$name][0];
                
                // availability_calendar_0_rates includes all rates for a month. 
                $name = 'availability_calendar_'.$c.'_rates';
                $month_rates = $m[$name][0];
                $month_rates_unserialized = unserialize(unserialize($month_rates));
                $number_of_rows = count($month_rates_unserialized);
                
                // Count of rate types (NAMES)
                $name = 'availability_calendar_'.$c.'_rate_types';
                $rate_type_count = intval($m[$name][0]);
                var_dump($rate_type_count);

                $new_periods = array();
                var_dump($month);
                // Output example: $new_periods['Weekend'][0] = '4000';
                for ($rate_id=0; $rate_id<$rate_type_count; $rate_id++) {
                    //availability_calendar_".$meta_key."_rate_types_%_period
                    $name = 'availability_calendar_'.$c.'_rate_types_'.$rate_id.'_period';
                    $period_name = $m[$name][0];
                    if ($period_name != 'null') {
                        for ($week_count=0; $week_count<$number_of_rows; $week_count++) {
                            $rate_name = 'rate_'.($rate_id+1);
                            $price = $month_rates_unserialized[$week_count][$rate_name];
                            $new_periods[$period_name][$week_count] = $price;
                            
                            // No such thing as more than 6 weeks in a month
                            if ($week_count == 5) break 1;
                        }
                    }
                }
                $rates_serialized = serialize($new_periods);

                
                // Availability
                $vars = array(
                    'availability', // table_name
                    array( // fields to include
                        'blog_id' => $blog_id, 
                        'post_id' => $post_id,
                        'month' => $month,
                        'booked_days' => $month_booked_days
                    ),
                    array( // format: %s = string, %d = integer, %f = float
                        '%d', // blog_id
                        '%d', // post_id
                        '%s', // month
                        '%s'  // booked_days

                    ));
                
                // $wpdb->insert( $table, $data, $format );
                $wpdb->insert($vars[0], $vars[1], $vars[2]);
                // ID generated is given by $wpdb->insert_id
                outputFromQuery($wpdb, $wpdb->insert_id);
                
                // Rates
                $vars = array(
                    'rates', // table_name
                    array( // fields to include
                        'blog_id' => $blog_id, 
                        'post_id' => $post_id,
                        'month' => $month,
                        'rates' => $rates_serialized
                    ),
                    array( // format: %s = string, %d = integer, %f = float
                        '%d', // blog_id
                        '%d', // post_id
                        '%s', // month
                        '%s'  // rates

                    ));
                
                // $wpdb->insert( $table, $data, $format );
                $wpdb->insert($vars[0], $vars[1], $vars[2]);
                // ID generated is given by $wpdb->insert_id
                outputFromQuery($wpdb, $wpdb->insert_id);
            }
            
            // Only include published houses in house table
        	if ( get_post_status ( $post_id ) != 'publish' ) {
                echo '<p>Not published, skipping include</p>';
    			return;
        	}


            
            //$post_thumbnail = get_the_post_thumbnail();
            $post_thumbnail = unserialize($m['house_photos'][0]);
    		$post_thumbnail = wp_get_attachment_image_src( $post_thumbnail[0], 'house_search' );
    		$post_thumbnail = $post_thumbnail[0];
            
            $locations = wp_get_object_terms( $post_id,  'location' );
            if ( !empty( $locations ) ) {
                $vals = array();
                foreach ( $locations as $l ) {
                    array_push($vals, $l->slug);
                }
                $locations = serialize($vals);
            }
            else {
                $locations = '';
            }
            
            $vars = array(
                'houses', // table_name
                array( // fields to include
                    'blog_id' => $blog_id, // Ensures we have still changed sites
                    'post_id' => $post_id,
                    'post_title' => $post_title,
                    'permalink' => $permalink,
                    'post_thumbnail' => $post_thumbnail,
                    'availability_option' => $m['availability_option'][0],
                    'availability_site_ref' => $m['availability_site_ref'][0],
                    'availability_site_post_id' => $m['availability_site_post_id'][0],
                    'location' => $m['location'][0],
                    'location_text' => $m['location_text'][0],
                    'locations' => $locations,
                    'sleeps_min' => $m['sleeps_min'][0],
                    'sleeps_max' => $m['sleeps_max'][0],
                    'brief_description' => $m['brief_description'][0],
                    'brief_description_winter' => $m['brief_description_winter'][0],
                    'all_prices_with_from' => $m['all_prices_with_from'][0]
                ),
                array( // format: %s = string, %d = integer, %f = float
                    '%d', // blog_id
                    '%d', // post_id
                    '%s', // post_title
                    '%s', // permalink
                    '%s', // post_thumbnail
                    '%d', // availability_option
                    '%d', // availability_site_ref
                    '%d', // availability_site_post_id
                    '%s', // location
                    '%s', // location_text
                    '%s', // locations
                    '%f', // sleeps_min
                    '%f', // sleeps_max
                    '%s', // brief_description
                    '%s', // brief_description_winter
                    '%d'  // all_prices_with_from
                ));
            
            // $wpdb->insert( $table, $data, $format );
            $wpdb->insert($vars[0], $vars[1], $vars[2]);
            // ID generated is given by $wpdb->insert_id
            outputFromQuery($wpdb, $wpdb->insert_id);
    
        }
        migrateAllBlogs($skip);
    }
   

?>