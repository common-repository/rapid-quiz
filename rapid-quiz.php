<?php
/*
Plugin Name: Rapid Quiz
Plugin URI: http://www.rapidesl.com
Description: Rapid Quiz is the quickest, easiest way to create multiple choice questions, quizzes and exercises for WordPress.
Author: Rapid ESL
Version: 1.0
Author URI: http://www.rapidesl.com
Tags: quiz, quizzes, exercises, multiple choice,

Copyright (C) 2013  RapidESL.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

$rq_version = '1';

//  shortcode

function rapid_quiz_shortcode( $atts, $content = null ) {
	
	// loads script only if this shortcode is in the page
	wp_enqueue_script( 'rapid-quiz', plugins_url('/rapid-quiz.js', __FILE__),  array( 'jquery' ),  $rq_version);
	
	extract( shortcode_atts( array(
		'question' => '',
		'answer' => '',
		'options' => '',
		'notes' => '',		
	), $atts ) );
	
	$q_options = explode ('|', $options);
	
	// makes sure the css only outputs once
	static $rq_css_run;
	
		if (!$rq_css_run){
				
			rq_css();
			$rq_css_run = 1;
			
		}
	
		
	static $rq_count;
	
	$rq_count++;
	
	
	$rq_content = '';
	
	
	$rq_content .= '<div class="rq_panel">';
	

	$rq_content .= '<div class="rq_question" data-answered="no"><img src="'. plugins_url('/icons/correct.gif', __FILE__ ). '" class="rq_icon correct" style="display:none;" /><img src="'. plugins_url('/icons/incorrect.gif', __FILE__ ). '" class="rq_icon incorrect" style="display:none;" />'. $rq_count . '. ' . $question.'</div><ul>';
	
	foreach($q_options as $option) {
		
		if ($answer == $option) {
			
				
	
				$rq_content .= '<li class="rq_option_text" data-correct="1">';
				
				$rq_content .= '<input type="radio" class="rq_radio" value="'. $option .'" id="'. $option .'" /> <label for="'. $option .'"> '. $option.'</label>';
				
				$rq_content .= '</li>';
			
		} else {
			
				$rq_content .= '<li class="rq_option_text"><input type="radio" class="rq_radio" value="'. $option .'" id="'. $option .'" /> <label for="'. $option .'"> '. $option.'</label></li>';
			
		}
	}

		$rq_content .= '<li class="rq_notes" style="display: none;">'. $notes.'</li></ul></div><div style="clear:both;"></div>';

	// todo: single answer becomes text input that must match single answer given




return $rq_content;
	
}
add_shortcode( 'rapid_quiz', 'rapid_quiz_shortcode' );



add_action('media_buttons_context', 'add_my_custom_button');

add_action('admin_footer', 'add_inline_popup_content');

function add_my_custom_button($context) {
  
$container_id = 'rqDiv';
  
  //our popup's title
  $title = 'Rapid Quiz';

  //append the icon
  $context .= "<a class='thickbox button' onclick='rqClearForm()' title='{$title}' href='#TB_inline?width=400&height=600&inlineId={$container_id}'>Add Rapid Quiz question</a>";
  
  return $context;
}

function add_inline_popup_content() {
?>
<div id="rqDiv" style="display:none;">
	
<h2>Add a new Rapid Quiz question</h2>
  
<p>Mandatory fields are marked by an asterisk * </p>
  
<textarea id="rqQuestion" style="width:400px; height: 40px;" placeholder="* Type your question here"></textarea><br/><br/>
  
<input type="radio" name="rqCorrect" value="1"/>
<input type="text" id="rqa1" style="width: 200px;" placeholder="* Answer" value="" /><br/><br/>
  
<input type="radio" name="rqCorrect" value="2"/>
<input type="text" id="rqa2" style="width: 200px;" placeholder="Answer" value="" /><br/><br/>
   
<input type="radio" name="rqCorrect" value="3"/>
<input type="text" id="rqa3" style="width: 200px;" placeholder="Answer" value="" /><br/><br/>
    
<input type="radio" name="rqCorrect" value="4"/>
<input type="text" id="rqa4" style="width: 200px;" placeholder="Answer" value="" />

<p><em>NB: Select the correct answer above.</em></p>

<textarea id="rqNotes" style="width:400px; height: 40px;" placeholder="Notes - displayed when the correct answer is displayed."></textarea><br/>
  
  
  <br/>
 <a href="#" class="button" onclick="rqSend(); return false;">Add Question</a>
 
	<script type="text/javascript">
	
	function rqClearForm() {
		
		jQuery('textarea[id="rqQuestion"]').val('');
		jQuery('input:radio').removeAttr("checked");
		
		for (i=1; i <= 4; i++ ) {
			var optName = "rqa"+i;		       
			jQuery('input[id="' + optName +'"]').val('');
		}
		
		jQuery('textarea[id="rqNotes"]').val('');
	}
	

	
		function rqSend() {
		
		var question = jQuery('textarea[id="rqQuestion"]').val();
		
		if (!question)  {
			
			alert("Please enter a question.");
			return false;
			
		}
		
		var correct =  jQuery('input[name="rqCorrect"]:checked').val();
	
		if (!correct)  {
			
			alert("Please select the correct answer.");
			return false;
			
		}
		
		var options = '';
		var correctOK = false;
		
		for (i=1; i <= 4; i++ ) {
					
		var optName = "rqa"+i;
		
				       
			if(jQuery('input[id="' + optName +'"]').val().length !== 0) {
				
				if(i !==1) {
					options += "|";
				}
				
				options += jQuery('input[id="' + optName +'"]').val();
				
				// mark this as correct answer if it is
				if (i == correct) {
					
					var answer = jQuery('input[id="' + optName +'"]').val();
					correctOK = 1;

					
				}
			}

			
		}
	
	
			// stop things if user has selected a correct answer that doesn't exist
			if (!correctOK) {
				
				alert("Please select the correct answer.");
				return false;
				
			}
			
	
		var notes = jQuery('textarea[id="rqNotes"]').val();
		
		// change double-quotes to two single quotes to keep shortcode from breaking
		question = question.replace(/\"/g, "''");
		answer = answer.replace(/\"/g, "''");
		options = options.replace(/\"/g, "''");
		notes = notes.replace(/\"/g, "''");
		
		var rqContent = '[rapid_quiz question="' + question + '" answer="' + answer + '" options="' + options + '" notes="' + notes + '"]';
		
		
		// adding a newline character for better readability in editor (only works with html editor)
		send_to_editor("\n"+rqContent);
		
		}
	
		
	 </script>
  
</div>
<?php
}

// default css

$rq_default_css = '
	
	.rq_panel{
		padding: 20px 0 20px 0;
	}
	
	.rq_panel ul{
	}
	
	.rq_question {
		font-weight: bold;
	}
	
	.rq_option_text, .rq_notes{
		margin-left: 18px;
		float:left;
		clear: both;
	}
	
	.rq_option_text:hover {
		background: #eee;
		cursor: pointer;
	}
	
	.rq_notes {
		list-style: none;
		padding: 12px:
	}
	
	.rq_wrong {
		font-style: italic;
	}
	
	.rq_correct {
		font-style: italic;
	}
	
	.rq_icon {
		margin-right: 4px;
	}';


function rq_css() {
	
	$rq_custom_css = get_option( 'rapid-quiz-custom-css' );
	if (!$rq_custom_css) {
	global $rq_default_css;
	?>
	<style>
	<?php echo $rq_default_css; ?>
	</style>
<?php 	}


}



/// running wp_enqueue_script for jQuery - but only if it's a post / page that uses our shortcode
// based on http://dwainm.wordpress.com/2012/08/16/addenqueue-scripts-or-styles-for-pages-where-my-plugin-short-code-is-found/

function rq_load_jquery() {
 
global $post, $rq_version;
 
if ( !empty($post) ){
 // check the post content for the short code
 if ( stripos($post->post_content, '[rapid_quiz')!==FALSE ){
 // we have found a post with the short code
 wp_enqueue_script( 'jquery' );
 

 }
 }
}
// add scripts to wordpress front end with this hook
add_action('wp_enqueue_scripts', 'rq_load_jquery');



// admin pages





add_action('admin_menu', 'rq_register_admin_page');

function rq_register_admin_page() {
	
	add_menu_page( 'Rapid Quiz', 'Rapid Quiz', 'manage_options', 'rapid-quiz', 'gsrq_admin_page' );
	add_submenu_page( 'rapid-quiz', 'Settings', 'Settings',  'manage_options', 'rapid-quiz-settings', 'gsrq_settings_page' );
}



// info page

function gsrq_admin_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} ?>
	
	<div class="wrap">

	
  <div id="icon-options-general" class="icon32"></div>
<h2>Rapid Quiz</h2>



<div class="tool-box" style="width: 66%;">
	

<p>
Rapid Quiz is an easy way to create multiple choice quizzes and exercises. Unlike other quiz plugins, Rapid Quiz lets you create quizzes on the standard post / page editor using a popup panel - there's no need to go to a different page to create a quiz.</p>

<h3 class="title">How to add a quiz</h3>

<p>On the Edit Post / Page screen, place your cursor where you want your quiz to be and click the <em>Add Rapid Quiz question</em> button.</p>

<img src="<?php echo plugins_url('screenshot2.png', __FILE__ ); ?>" />

<p>In the panel that opens, type in your question, answer choices, correct answer and the optional notes (displayed after the user selects an answer). </p>

<img src="<?php echo plugins_url('screenshot3.png', __FILE__ ); ?>" />

<p>When you're done, click <em>Add Question</em>. You'll see Rapid Quiz has added a shortcode to your post for that question. </p>

<img src="<?php echo plugins_url('screenshot4.png', __FILE__ ); ?>" />

<p>To add another question, click the <em>Add Rapid Quiz question</em> again and create a new question. You can add as many questions as you like. When the post is published or previewed, Rapid Quiz transforms the shortcode into a fully working quiz.</p>

<img src="<?php echo plugins_url('screenshot1.png', __FILE__ ); ?>" />


<h3 class="title">Need help? Want to suggest a feature?</h3>
We'd love to hear from you - find us on the official <a href="http://wordpress.org/support/plugin/rapid-quiz">WordPress forums</a>. Rapid Quiz is developed by <a href="http://www.rapidesl.com/">Rapid ESL</a>, provider of premium lessons and teaching content to English as a Second Language teachers worldwide.

	</div>
	<?php
	
	

	
	
}


	
// settings page


function gsrq_settings_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	global $rq_default_css;
	
	// update if needed
	if ($_POST['rapid-quiz-update']) {
			update_option('rapid-quiz-custom-css', $_POST['rapid-quiz-custom-css']);
			
			$updated = 1;
		}
	
	?>
	
	<div class="wrap">

	
		<?php
		if( isset($updated) ) { ?>
	    <div id="message" class="updated">
	        <p><strong><?php _e('Settings saved.') ?></strong></p>
	    </div>
	
	<?php } ?> 
	
<form method="post" action="<?php __FILE__?>">
	

  <div id="icon-options-general" class="icon32"></div>
<h2>Rapid Quiz Settings</h2>



<div class="tool-box" style="width: 66%;">


<p><label for="">Use custom css?</label>
<?php $rq_custom_css = get_option( 'rapid-quiz-custom-css' ); ?>
<input type="checkbox" name="rapid-quiz-custom-css" value="1" <?php checked( $rq_custom_css,1 ); ?> />
<input type="hidden" name="rapid-quiz-update" value="1" />
<p>
Checking the box above will prevent the default quizz css from loading, allowing you to add your own custom css to the style.css file of your theme. 
For reference, the  default styling is shown below.</p>

<?php submit_button();?>
</form>
<p>
<?php echo nl2br($rq_default_css) ; ?>
</p>




	</div>
	<?php
	
	

	
	
}



