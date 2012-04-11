<?php 

/*

Plugin Name: Alexa Rank Widget 

Plugin URI: http://www.wordpressapi.com/wordpress-plugins/

Description: Adds a button which allows you to share post on yahoo buzz. 

License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html

Version: 1.0

Author: Wordpressapi.com

Author URI: http://www.wordpressapi.com

*/

add_action( 'widgets_init', 'alexa_rank_load_widget' );

function alexa_rank_load_widget() {
	register_widget( 'AlexaRankWordpressapi_Widget' );
}

class AlexaRankWordpressapi_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function AlexaRankWordpressapi_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'alexaRankWordpressapi_Widget', 'description' => __('This Widget will show the Alexa website ranking as your choice', 'Alexa_Rank_Widget') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'alexa-rank-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'alexa-rank-widget', __('Alexa Rank Widget', 'alexa_rank_widget'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

	/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$website_name = $instance['website_name'];
		$button_size = $instance['button_size'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		if( $button_size == 'squre') {

		echo '<A href="http://www.alexa.com/siteinfo/'.$website_name.'"><SCRIPT type="text/javascript" language="JavaScript" src="http://xslt.alexa.com/site_stats/js/s/a?url='.$website_name.'"></SCRIPT></A>';
		} else {
		echo '<A href="http://www.alexa.com/siteinfo/'.$website_name.'"><SCRIPT type="text/javascript" language="JavaScript" src="http://xslt.alexa.com/site_stats/js/s/b?url='.$website_name.'"></SCRIPT></A>';
		}

		/* After widget (defined by themes). */
		echo $after_widget;

	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['website_name'] = $new_instance['website_name'];
		$instance['button_size'] = $new_instance['button_size'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Alexa Rank', 'Alexa Rank'), 'website_name' => 'wordpressapi.com',  'button_size' => '120x95' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'website_name' ); ?>"><?php _e('Website Name:', 'website_name'); ?></label>
			<input id="<?php echo $this->get_field_id( 'website_name' ); ?>" name="<?php echo $this->get_field_name( 'website_name' ); ?>" value="<?php echo $instance['website_name']; ?>" style="width:100%;" />
		</p>		


		<p>
			<label for="<?php echo $this->get_field_id( 'button_size' ); ?>"><?php _e('Button Size:', 'button_size'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'button_size' ); ?>" name="<?php echo $this->get_field_name( 'button_size' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'squre' == $instance['button_size'] ) echo 'selected="selected"'; ?>>squre</option>
				<option <?php if ( 'vertical' == $instance['button_size'] ) echo 'selected="selected"'; ?>>vertical</option>
			</select>
		</p>

		

	<?php
	}
}

?>