<?php 
/**
 * @package  MattsPlugin
 */
namespace Inc\Api\Widgets;

use WP_Widget;
/**
* 
*/
class MediaWidget extends WP_Widget
{
	public $widget_ID;

	public $widget_name;
	
	public $widget_options  = array();
	
	public $control_options = array();

	function __construct() 
	{
		$this->widget_ID   = 'matts_media_widget';
		
		$this->widget_name = 'Matts Media Widget';
	
		$this->widget_options = array(
			'classname'                   => $this->widget_ID,
			'description'                 => $this->widget_name,
			'customize_selective_refresh' => true,
		);
		
		$this->control_options = array(
			'width'  => 400,
			'height' => 350,
		);
	}

	public function register()
	{
		parent::__construct( $this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options );
	
		add_action( 'widgets_init', array( $this, 'widgetsInit' ) );
	}
	
	public function widgetsInit()
	{
		register_widget( $this );
	}
	
	public function widget( $args, $instance ) 
	{
		echo $args['before_widget'];
// if the instance title attribute exists 
		if ( ! empty( $instance['title'] ) ) 
		{
// Print the title in the front end
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( ! empty( $instance['image'] ) ) 
		{
// Prints the image in the front end
			echo '<img src="'. esc_url( $instance['image'] ) .'" alt="">';
		}

		echo $args['after_widget'];
	}

	public function form( $instance ) 
	{
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Custom Text', 'matts_plugin' );

		$image = ! empty( $instance['image'] ) ? $instance['image'] : '';
		?>
		<p>
<!-- Label for the Title -->
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'matts_plugin' ); ?>
			</label> 

<!-- Input box for the Title name -->
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
<!-- Label for the Image -->
			<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>">
				<?php esc_attr_e( 'Image:', 'matts_plugin' ); ?>
			</label> 

<!-- Input for for the Image url -->
			<input class="widefat image-upload" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_url( $image ); ?>">

<!-- Button to select Image -->
			<button type="button" class="button button-primary js-image-upload">
				Select Image
			</button>
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) 
	{
		$instance = $old_instance;
	
// Variable to store title
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

// Variable to store Image
		$instance['image'] = ! empty( $new_instance['image'] ) ? $new_instance['image'] : '';
	
		return $instance;
	}
}
