<?php
/**
*Plugin Name: WP Alphabetic Navigation
*Plugin URI: http://bonanzaweb.com.ua
*Description: Adds alphabetic navigation for your posts
*Version: 1.0
*Author: Boris Volkovich
*Author URI: https://www.elance.com/s/borisv/
**/

/*  Copyright 2013  Volkovich Boris  (email : greenmoonbase@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Admin Part */

add_action('admin_menu','alpha_add_menu');

function alpha_add_menu()
{
	add_options_page('WP Alphabetic Navigation','WP Alphabetic Navigation','manage_options',__FILE__,'alpha_options_page');
}

function alpha_options_page()
{
?>
	<div class="wrap">
			<h2>WP Alphabetic Navigation</h2>
			Adds alphabetic navigation to your Wordpress website
			<?php 
			if($_POST['alpha_lang'])
			{
				update_option('alpha_lang',$_POST['alpha_lang']);
				update_option('alpha_layout',$_POST['alpha_layout']);
				update_option('alpha_direction',$_POST['alpha_direction']);
				update_option('alpha_color',$_POST['alpha_color']);
				update_option('alpha_background',$_POST['alpha_background']);
				
			}
			?>
			Alphabet
			<form method="POST" action="">
				Alphabet: <select name="alpha_lang">
					<option value="LATIN"<?php if(get_option('alpha_lang') == "LATIN") {echo ' selected="selected"';}?>>LATIN</option>
					<option value="CYRILLIC"<?php if(get_option('alpha_lang') == "CYRILLIC") {echo ' selected="selected"';}?>>CYRILLIC</option>
				</select><br>
				Layout: <select name="alpha_layout">
					<option value="HEADER"<?php if(get_option('alpha_layout') == "HEADER") {echo ' selected="selected"';}?>>HEADER</option>
					<option value="FOOTER"<?php if(get_option('alpha_layout') == "FOOTER") {echo ' selected="selected"';}?>>FOOTER</option>
					<option value="CUSTOM"<?php if(get_option('alpha_layout') == "CUSTOM") {echo ' selected="selected"';}?>>CUSTOM</option>
				</select><br>
				Direction: <select name="alpha_direction">
					<option value="left"<?php if(get_option('alpha_direction') == "left") {echo ' selected="selected"';}?>>HORIZONTAL</option>
					<option value="none"<?php if(get_option('alpha_direction') == "none") {echo ' selected="selected"';}?>>VERTICAL</option>
				</select><br>
				Letters color: <input name="alpha_color" value="<? echo get_option('alpha_color');?>"/>
				Background color: <input name="alpha_background" value="<? echo get_option('alpha_background');?>"/>
			<?php submit_button(); ?>
			</form>
			<?php
			if (get_option('alpha_layout') == "CUSTOM")
			{
				echo 'To show alphabetic navigation bar in a custom place add &lt;?php show_aphabet(); ?&gt; in your theme';
			}
			?>
	</div>
<?php
}
/* Front-End Part */

if (get_option('alpha_layout') == "FOOTER")
{
	add_action('wp_footer', 'show_aphabet');
}
else if (get_option('alpha_layout') == "HEADER")
{
	add_action('wp_head', 'show_aphabet');
}
else 
{
}

function show_aphabet() {
	if (get_option('alpha_lang') == "LATIN")
	{
		$wp_alphabet = range("A","Z");
		echo '<div class="alphadiv">';
		foreach ($wp_alphabet as $wp_aplha)
		{
			echo '<a href="index.php?letter='.$wp_aplha.'"><div class="wp_aphlastyle">'.$wp_aplha.'</div></a>';
		}
		echo '</div>';
	}
	else
	{
		$abc = array();
		foreach (range(chr(0xC0), chr(0xDF)) as $b)
			$abc[] = iconv('CP1251', 'UTF-8', $b);
		foreach ($abc as $wp_aplha)
		{
			echo '<a href="index.php?letter='.$wp_aplha.'"><div class="wp_aphlastyle">'.$wp_aplha.'</div></a>';
			
		}
	}
}	

if($_GET["letter"])
{
add_filter('posts_where','wp_alphaposts');

function wp_alphaposts( $where )
{
	global $wpdb;
	
	$where .= 'AND left('.$wpdb->prefix.'posts.post_title,1) = "'.$_GET["letter"].'"';
	$sticky = get_option('sticky_posts');
	$where .= ' AND '.$wpdb->prefix.'posts.ID NOT IN ('.implode(',', $sticky).')';

	return $where;
}
}

?>


 <style type="text/css">
	.wp_aphlastyle
	{
		float: <?php echo get_option('alpha_direction');?>;
		padding:0px 5px;
		margin:2px;
		color: <?php echo get_option('alpha_color');?>;
		background-color: <?php echo get_option('alpha_background');?>;
	}
 </style>