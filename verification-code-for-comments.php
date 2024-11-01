<?php
/*
Plugin Name: Verification Code for Comments
Plugin URI: http://pwwang.com/2009/11/23/verification-code-for-comments/
Description: Add an verification code when user posting a comment to keep robots away. You can use an image verification code or a math equation instead. <a href="options-general.php?page=verification-code-for-comments.php">Configuration Panel</a>
Version: 2.1.0
Author: pwwang
Author URI: http://pwwang.com
*/
session_start();

define(VCC_URL, get_bloginfo('wpurl')."/wp-content/plugins/verification-code-for-comments");
define(VCC_WEB, 'http://pwwang.com/2011/04/17/verification-code-for-comments-2/');

//check the input code
function vcc_check(){
	global $user_ID;
	if( !$user_ID ){
		$vcc = md5(trim(strtoupper($_POST['vcode'])));
		if($vcc!=$_SESSION['vcode']){
			wp_die( __('Error: invalid verification code.') );
		}
	}
}

// function for head output sytles
function vcc_header() {
	global $user_ID;
	if( !$user_ID ){
		$opts = explode(':', get_option('vcc_caselen'));
		echo "<script
		type=\"text/javascript\"
		src=\"".VCC_URL."/vcc.js.php?vp=".get_option('vcc_positionname').
		"&vu=".VCC_URL.
		"&vs=".get_option('vcc_skin').
		"&vm=".get_option('vcc_method').
		"&l=".$opts[1].
		"\">
		</script>\n";
	}
}

//get the image verification code or a math equation
function vcc_getVerification(){
	$opts = explode(':', get_option('vcc_caselen'));
	$vcc = '<div style="display:block; clear:both; width:180px; height:72px; margin:2px 0; background:url('.VCC_URL.'/vcc_bg'.get_option('vcc_skin').'.png) left no-repeat;">';
	$vcc .= '<img src="'.VCC_URL.'/vcode.class.php?m='.get_option('vcc_method').'&l='.$opts[1].'" id="vcc_image" style="margin:10px 12px 9px 14px;" />';
	$vcc .= '<a href="javascript:;" style="display:inline-block; width:20px; margin:10px 0 9px 0; height:20px;" id="vcc_refresher"></a>';
	$vcc .= '<input name="vcode" id="vcode" value="'.(get_option('vcc_method')=='string' ? 'Enter the above string' : 'Enter the equation result').'" type="text" style="border-width:0px; color:#999; font-style:italic; width:152px; margin:2px 0 0 14px; height:15px;" />';
	$vcc .= '</div>';
	return $vcc;
}

//set and save vcc options
function vcc_options(){
    if($_POST['vcc_options_save']){
		update_option('vcc_skin',$_POST['vcc_skin']);
		update_option('vcc_formid',$_POST['vcc_formid']);
		update_option('vcc_positionname',$_POST['vcc_positionname']);
		update_option('vcc_method', $_POST['vcc_method']);
		update_option('vcc_caselen', $_POST['vcc_case'].':'.$_POST['vcc_len']);
		
     	echo '<div class="updated"><p>'. 'Verification Code for Comments Options successfully saved.' .'</p></div>';
	}
	?>
	<div class="wrap">
	<h2><?='Verification Code for Comments Options' ?> </h2>
	Author: <a href="http://pwwang.com">pwwang</a> <a href="<?= VCC_WEB ?>">Plugin page</a>
	<hr style="border:1px solid #999;" /><br />
	<form method="post" id="verification_code_for_comments_options" action="">
		<fieldset style="display:block; border:2px solid #0857A5; background-color:#eee; padding:12px; width:620px;">
		<legend style="padding:0 4px; color:#fff; font-weight:bold; background-color:#5696CE; border:2px solid #0857A5;">Choose a skin</legend>
		<table border="0" cellpadding="0" cellspacing="4" width="0">
		<tr>
			<td><input type="radio" name="vcc_skin" value="0" id="vcc_bg0" <?= !get_option('vcc_skin') ? 'checked' : '' ?> /></td><td><label for="vcc_bg0"><?= '<img src="'.VCC_URL.'/vcc_bg0.png'.'" />' ?></label></td>
			<td></td><td></td>
		</tr>
		<tr>
			<td><input type="radio" name="vcc_skin" value="1" id="vcc_bg1" <?= get_option('vcc_skin')=='1' ? 'checked' : '' ?> /></td><td><label for="vcc_bg1"><?= '<img src="'.VCC_URL.'/vcc_bg1.png'.'" />' ?></label></td>
			<td><input type="radio" name="vcc_skin" value="2" id="vcc_bg2" <?= get_option('vcc_skin')=='2' ? 'checked' : '' ?> /></td><td><label for="vcc_bg2"><?= '<img src="'.VCC_URL.'/vcc_bg2.png'.'" />' ?></label></td>
			<td><input type="radio" name="vcc_skin" value="3" id="vcc_bg3" <?= get_option('vcc_skin')=='3' ? 'checked' : '' ?> /></td><td><label for="vcc_bg3"><?= '<img src="'.VCC_URL.'/vcc_bg3.png'.'" />' ?></label></td>
		</tr>
		<tr>
			<td><input type="radio" name="vcc_skin" value="4" id="vcc_bg4" <?= get_option('vcc_skin')=='4' ? 'checked' : '' ?> /></td><td><label for="vcc_bg4"><?= '<img src="'.VCC_URL.'/vcc_bg4.png'.'" />' ?></label></td>
			<td><input type="radio" name="vcc_skin" value="5" id="vcc_bg5" <?= get_option('vcc_skin')=='5' ? 'checked' : '' ?> /></td><td><label for="vcc_bg5"><?= '<img src="'.VCC_URL.'/vcc_bg5.png'.'" />' ?></label></td>
			<td><input type="radio" name="vcc_skin" value="6" id="vcc_bg6" <?= get_option('vcc_skin')=='6' ? 'checked' : '' ?> /></td><td><label for="vcc_bg6"><?= '<img src="'.VCC_URL.'/vcc_bg6.png'.'" />' ?></label></td>
		</tr>
		</table>
		</fieldset>
		<br />
		<fieldset style="display:block; border:2px solid #0857A5; background-color:#eee; padding:12px; width:620px;">
		<legend style="padding:0 4px; color:#fff; font-weight:bold; background-color:#5696CE; border:2px solid #0857A5;">Choose a position</legend>
		<div>Input your comment form id: <input type="text" name="vcc_formid" id="vcc_formid" value="<?= get_option('vcc_formid') ?>" /> <input type="button" value=" OK " onclick="showformarea(this.form.vcc_formid.value, this.form.vcc_positionname)" /> <a href="javascript:;" onclick="vcc_formid_help()">?</a></div>
		<input type="hidden" name="vcc_positionname" id="vcc_positionname" value="<?= get_option('vcc_positionname') ?>" />
		<b>CLICK</b> the input box, textarea or button to set the position of Verification Code Box.
		<div id="commentformbox" style="display:block; background-color:#fff; width:100%; height:300px; overflow:auto; border:1px solid #A6CBED">
		</div>
		</fieldset>
		<br />
		<fieldset style="display:block; border:2px solid #0857A5; background-color:#eee; padding:12px; width:620px;">
		<legend style="padding:0 4px; color:#fff; font-weight:bold; background-color:#5696CE; border:2px solid #0857A5;">Choose a method</legend>
		<div>
		<input type="radio" name="vcc_method" id="vcc_method1" value="string" <?= get_option('vcc_method')=='string' ? 'checked' : '' ?> /><label for="vcc_method1">String</label> &nbsp;	&nbsp;
		<input type="radio" name="vcc_method" id="vcc_method2" value="equation" <?= get_option('vcc_method')=='equation' ? 'checked' : '' ?> /><label for="vcc_method2">Equation</label> </div><br />
		<div id="vcc_string_opt" <?= get_option('vcc_method')=='equation' ? 'style="display:none;"' : '' ?>>
			<?php $opts = explode(':', get_option('vcc_caselen')); ?>
			Case sensitive: <input type="checkbox" name="vcc_case" value="1" <?= $opts[0] ? 'checked' : '' ?> /> &nbsp;	&nbsp;	&nbsp;	Length of string:
			<select style="width:50px;" name="vcc_len">
				<option value="3" <?= $opts[1]==3 ? 'selected' : '' ?>>3</option>
				<option value="4" <?= $opts[1]==4 ? 'selected' : '' ?>>4</option>
				<option value="5" <?= $opts[1]==5 ? 'selected' : '' ?>>5</option>
				<option value="6" <?= $opts[1]==6 ? 'selected' : '' ?>>6</option>
				<option value="7" <?= $opts[1]==7 ? 'selected' : '' ?>>7</option>
				<option value="8" <?= $opts[1]==8 ? 'selected' : '' ?>>8</option>
			</select>
		</div>
		</fieldset>
		<div id="preview_box"></div>
		<p class="submit"><input type="submit" name="vcc_options_save" value="Save" /> <input type="button" name="vcc_options_preview" value="Preview" onclick="vcc_preview()"></p>
		<script type="text/javascript">
		//<![CDATA[
		function vcc_formid_help(){
			alert('How to get it? \n\n\
Open one of your post which contains thecomment area, \n\
view the page source, and then find the string like:\n\n\
<form action="..." method="post" id="commentform">\n\n\
the value of attribute id like "commentform" is the\n\
very value we want.');
		}
		
		function showformarea(vcc_formid, vcc_positionname){
			var guid = '<?php $posts = wp_get_recent_posts(array('numberposts'=>1)); echo $posts[0]['guid'] ?>';
			var $formbox = jQuery('#commentformbox').text('Loading ... ');
			var $div = jQuery('<div />').load(guid + ' #' + vcc_formid, function(){

				//setTimeout(function(){
				$formbox.html( jQuery("form#"+vcc_formid, $div).html().replace(/type\s*=\s*['"]?submit['"]?/i, 'type="button"') );
				var $position = jQuery('<div />').text('The Verification Code Box will locate here.').css({
					display: 'block',
					width: '180px',
					height: '72px',
					'text-align': 'center',
					'vertical-align': 'middle',
					color: 'red',
					border: '1px solid red'
				}).attr('id', 'vcc_position_flag');
				jQuery('input, textarea', '#commentformbox').live('click', function(){
					jQuery('#vcc_position_flag').remove();
					jQuery(this).before($position);
					vcc_positionname.value = jQuery(this).attr('name');
				});
				//}, 500);
			});

		}
		
		jQuery("input[id=vcc_method1], label[for=vcc_method1]").click(function(){
			jQuery("#vcc_string_opt").slideDown();
		});
		
		jQuery("input[id=vcc_method2], label[for=vcc_method2]").click(function(){
			jQuery("#vcc_string_opt").slideUp();
		});
		
		function vcc_preview(){
			jQuery('#preview_box').html('<?= vcc_getVerification() ?>');
		}
		//]]>
		</script>
	</form>
	</div>	
	<?php
}

//initial vcc
function vcc_install(){
	/*
	add_option('vcc_inputbox_style',"<p><input type='text' name='%inputname%' id='%inputname%' />
<label for='%inputname%'> %verification% <small>(required)</small></label></p>");
	*/
	add_option('vcc_vcc_formid', 'commentform');
	add_option('vcc_vcc_positionname', 'comment');
	add_option('vcc_caselen', '0:4');
	add_option('vcc_method','string');
	add_option('vcc_skin', 0);
}

//admin menu
function vcc_adminMenu(){
	add_options_page('Verification Code for Comments Options', 'Verification Code for Comments', 9, 'verification-code-for-comments.php', 'vcc_options');
}

if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
	add_action('init', 'vcc_install');
}

add_action('wp_footer','vcc_header');
add_action('admin_menu','vcc_adminMenu',1);
add_action('pre_comment_on_post','vcc_check');

?>
