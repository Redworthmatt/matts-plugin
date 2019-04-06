<?php 
/**
 * @package  MattsPlugin
 */
namespace Inc\Base;

use Inc\Api\SettingsApi;

use Inc\Base\BaseController;

use Inc\Api\Callbacks\TestimonialCallbacks;
/**
* 
*/
class TestimonialController extends BaseController
{
	public $settings;

	public $callbacks;

	public function register()
	{
		if ( ! $this->activated( 'testimonial_manager' ) ) return;
		
		$this->settings  = new SettingsApi();
		
		$this->callbacks = new TestimonialCallbacks();

// Required Actions
		add_action( 'init', array( $this, 'testimonial_cpt' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save_meta_box' ) );

		add_action( 'manage_testimonial_posts_columns', array( $this, 'set_custom_columns' ) );

		add_action( 'manage_testimonial_posts_custom_column', array( $this, 'set_custom_columns_data' ), 10, 2 );

		add_filter( 'manage_edit-testimonial_sortable_columns', array( $this, 'set_custom_columns_sortable' ) );

		$this->setShortcodePage();

		add_shortcode( 'testimonial-form', array( $this, 'testimonial_form' ) );
	}

	public function testimonial_form()
	{
		ob_start();

		require_once( "$this->plugin_path/templates/contact-form.php" );

		echo "<script src=\"$this->plugin_url/src/js/form.js\"></script>";

		return ob_get_clean();
	}

	public function setShortcodePage()
	{
		$subpage = array(
			array(
				'parent_slug' => 'edit.php?post_type=testimonial',
				'page_title' => 'Shortcodes',
				'menu_title' => 'Shortcodes',
				'capability' => 'manage_options',
				'menu_slug' => 'matts_testimonial_shortcode',
				'callback' => array( $this->callbacks, 'shortcodePage')
			)
		);

		$this->settings->addSubPages( $subpage )->register();
	}

	public function testimonial_cpt ()
	{
// Generate the info for the Custom Post Type
		$labels = array(
			'name'          => 'Testimonials',
			'singular_name' => 'Testimonial'
		);
		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => false,
			'menu_icon'           => 'dashicons-testimonial',
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'support'             => array( 'title', 'editor' )
		);

// Trigger the post type with a list of arguements
		register_post_type ( 'testimonial', $args );
	}

	public function add_meta_boxes()
	{
//  Meta box on the side for extra info
		add_meta_box(
			'testimonial_author',
			'Testimonial Options',
			array( $this, 'render_features_box' ),
			'testimonial',
			'side',
			'default'
		);
	}

	public function render_features_box($post)
	{
// Security to check for malicious data
		wp_nonce_field( 'matts_testimonial', 'matts_testimonial_nonce' );

//get the value if there is one of the custom meta box 
		$data  = get_post_meta( $post->ID, '_matts_testimonial_key', true );
		
		$name  = isset($data['name']) ? $data['name'] : '';
		
		$email = isset($data['email']) ? $data['email'] : '';

// if the data if set return the data overwise false
		$approved = isset($data['approved']) ? $data['approved'] : false;

// if the data if set return the data overwise false
		$featured = isset($data['featured']) ? $data['featured'] : false;

		?>
<!-- Label for Author Name-->
		<p>
			<label class="meta-label" for="matts_testimonial_author">
				Author Name
			</label>

<!-- input box for Author Name -->
			<input type="text" id="matts_testimonial_author" name="matts_testimonial_author" class="widefat" value="<?php echo esc_attr( $name ); ?>">
		</p>

		<p>
<!-- Label for Author Email -->
			<label class="meta-label" for="matts_testimonial_email">
				Author Email
			</label>

<!-- input box for Author Email -->
			<input type="email" id="matts_testimonial_email" name="matts_testimonial_email" class="widefat" value="<?php echo esc_attr( $email ); ?>">
		</p>

<!-- Container for Approval Meta data -->
		<div class="meta-container">

<!-- Label for Aprroval -->
			<label class="meta-label w-50 text-left" for="matts_testimonial_approved">
				Approved 
			</label>

<!-- Container for Approval checkbox -->
			<div class="text-right w-50 inline">
				<div class="ui-toggle inline">

<!-- Input for Approval Meta data -->
					<input type="checkbox" id="matts_testimonial_approved" name="matts_testimonial_approved" value="1" <?php echo $approved ? 'checked' : '';?>>
					<label for="matts_testimonial_approved">
						<div>
						</div>						
					</label>					
				</div>
			</div>
		</div>

<!-- Container for Featured Meta data -->
		<div class="meta-container">

<!-- Label for Featured -->
			<label class="meta-label w-50 text-left" for="matts_testimonial_featured">
				Featured
			</label>

<!-- Container for Featured checkbox -->
			<div class="text-right w-50 inline">
				<div class="ui-toggle inline">

<!-- Input for Featured Meta data -->
					<input type="checkbox" id="matts_testimonial_featured" name="matts_testimonial_featured" value="1" <?php echo $featured ? 'checked' : '';?>>
					<label for="matts_testimonial_featured">
						<div>
						</div>						
					</label>					
				</div>
			</div>
		</div>

		<?php
	}

	public function save_meta_box($post_id)
	{
// If this is not set we are not in testimonial section
		if ( ! isset($_POST['matts_testimonial_nonce'] ) ) 
		{
			return $post_id;
		}

// Store the nonce in a custom variable
		$nonce = $_POST['matts_testimonial_nonce'];

// Verify that the nonce is valid
		if ( ! wp_verify_nonce( $nonce, 'matts_testimonial' ) ) 
		{
			return $post_id;
		}

// Check if Wordpress is autosaving or a user save
		if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		{
			return $post_id;
		}

// Check if the user has the capability to edit the page
		if ( ! current_user_can( 'edit_post', $post_id ) ) 
		{
			return $post_id;	
		}

// Store the meta box data
		$data = array(
			'name' => sanitize_text_field( $_POST['matts_testimonial_author'] ),
			'email' => sanitize_text_field( $_POST['matts_testimonial_email'] ),
			'approved' =>  $_POST['matts_testimonial_approved'] ? 1 : 0,
			'featured' =>  $_POST['matts_testimonial_featured'] ? 1 : 0
		);
		
// Checks if the value exists and updates the value
		update_post_meta( $post_id, '_matts_testimonial_key', $data );
	}

	public function set_custom_columns($columns)
	{
		$title = $columns['title'];

		$date = $columns['date'];

		unset( $columns['title'], $columns['date'] );

		$columns['name'] = 'Author Name';

		$columns['title'] = $title;

		$columns['approved'] = 'Approved';

		$columns['featured'] = 'Featured';

		$columns['date'] = $date;

		return $columns;
	}

	public function set_custom_columns_data( $column, $post_id)
	{
//get the value if there is one of the custom meta box 
		$data  = get_post_meta( $post_id, '_matts_testimonial_key', true );
		
		$name  = isset($data['name']) ? $data['name'] : '';
		
		$email = isset($data['email']) ? $data['email'] : '';

// if the data if set return the Yes overwise No
		$approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>YES</strong>' : 'NO';

// if the data if set return the Yes overwise No
		$featured = isset($data['featured']) && $data['featured'] === 1 ? '<strong>YES</strong>' : 'NO';

		switch($column)
		{
			case 'name':
				echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
				break;
			
			case 'approved':
				echo $approved;
				break;

			case 'featured':
				echo $featured;
				break;
		
		}
	}

	public function set_custom_columns_sortable($columns)
	{
		$columns['name'] = 'name';

		$columns['approved'] = 'approved';

		$columns['featured'] = 'featured';

		return $columns;
	}
}