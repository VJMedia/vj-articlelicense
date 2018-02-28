<?php
/*
Plugin Name: VJMedia: Article License
Description: Choose License for Article
Version: 1.0
Author: <a href="http://www.vjmedia.com.hk">VJMedia Technical Team</a>
GitHub Plugin URI: https://github.com/VJMedia/vj-articlelicense
*/

class vjlicense{
	static public $license=array(
		"cc:by-nc-nd" => array("name" => "Creative Commons 姓名標示-非商業性-禁止改作", "shortname" => "CC 非商業性-禁止改作", "cccode" => "by-nc-nd"),
		"cc:by-nc-sa" => array("name" => "Creative Commons 姓名標示-非商業性-相同方式分享", "shortname" => "CC 非商業性-相同方式分享", "cccode" => "by-nc-sa"),
		"cc:by-nc" => array("name" => "Creative Commons 姓名標示-非商業性", "shortname" => "CC 非商業性", "cccode" => "by-nc"),
		"cc:by-nd" => array("name" => "Creative Commons 姓名標示-禁止改作", "shortname" => "CC 禁止改作", "cccode" => "by-nd"),
		"cc:by-sa" => array("name" => "Creative Commons 姓名標示-相同方式分享", "shortname" => "CC 相同方式分享", "cccode" => "by-sa"),
		"cc:by" => array("name" => "Creative Commons 姓名標示", "shortname" => "CC (姓名標示)", "cccode" => "by"),
	);
}

function licenseHtml($content) {
	global $post;
	$license = get_post_meta($post->ID, 'vj_license',true) ? vjlicense::$license[get_post_meta($post->ID, 'vj_license',true)] : null;
	if($license["cccode"]){
		$license["image"]="https://licensebuttons.net/l/{$license["cccode"]}/4.0/88x31.png";
		$license["url"]="http://creativecommons.org/licenses/{$license["cccode"]}/4.0/";
	}
	$vj_logo = get_bloginfo("wpurl") . "/wp-content/plugins/vj-license/images/vjlogo.gif";
 	if ($license){
		$image = "<img src=\"{$license["image"]}\" alt=\"{$license["name"]}\" class=\"alignleft\" style=\"margin-top:4px;\" />";
		$result = "<div class=\"vj_license\"><p><a rel=\"license\" href=\"{$license["url"]}\">{$image}</a>本文章{$attrib}以 <a rel=\"license\" href=\"{$license["url"]}\">{$license["name"]}</a> 授權</p></div>";
		$content = $content . $result;
	}
	return $content;
}

function license_options() {  
	$license=get_post_meta($_GET['post'], 'vj_license',true);
	?>
	<div class="wrap"><input name="submitted" type="hidden" value="vjlicense" />
	<label for="vj_license">授權:</label>
	<select id="vj_license" class="widefat" name="vj_license">
	<option value=""></option>
        <option value="cc:by-nc-nd" <?php selected( $license, 'cc:by-nc-nd' ); ?>>CC 姓名標示-非商業性-禁止改作</option>
	<option value="cc:by-nc-sa" <?php selected( $license, 'cc:by-nc-sa' ); ?>>CC 姓名標示-非商業性-相同方式分享</option>
	<option value="cc:by-nc" <?php selected( $license, 'cc:by-nc' ); ?>>CC 姓名標示-非商業性</option>
	<option value="cc:by-nd" <?php selected( $license, 'cc:by-nd' ); ?>>CC 姓名標示-禁止改作</option>
	<option value="cc:by-sa" <?php selected( $license, 'cc:by-sa' ); ?>>CC 姓名標示-相同方式分享</option>
	<option value="cc:by" <?php selected( $license, 'cc:by' ); ?>>CC 姓名標示</option>
	</select>
</div>
	<?php
}

function vjlicense_header() {
	$css_url = get_bloginfo("wpurl") . "/wp-content/plugins/vj-license/vj-license.css"; 	 
	echo "<link rel=\"stylesheet\" href=\"${css_url}\" />";
	add_meta_box('vj_license_control', '授權', 'license_options', 'post', 'normal', 'high');
}

function post_form() {
	if ( isset($_POST['submitted']) && $_POST['submitted'] == 'vj_license') {
		if($_POST['submitted']==""){
			delete_post_meta($_POST['post_ID'],'vj_license');
		}elseif ($_POST['vj_license'] != get_post_meta($_POST['post_ID'],'vj_license')){
			update_post_meta($_POST['post_ID'],'vj_license', $_POST['vj_license']);
	        }
	}
}

add_action('admin_head', 'vjlicense_header');
add_action('save_post', 'post_form');
add_action('edit_post', 'post_form');
add_action('publish_post', 'post_form');
add_action('admin_head', 'post_form');
add_filter('the_content','licenseHtml','100');
?>
