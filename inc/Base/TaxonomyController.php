<?php 
/*
	@package MattsPlugin
 */
	namespace Inc\Base;

	use \Inc\Api\SettingsApi;
	use \Inc\Base\BaseController;
	use \Inc\Api\Callbacks\AdminCallbacks;
	use \Inc\Api\Callbacks\TaxonomyCallbacks;


/**
 * 
 */
class TaxonomyController extends BaseController
{
	public $settings;

	public $callbacks;

	public $tax_callbacks;

	public $subpages = array();

	public $taxonomies = array();

	public function register()
	{

		if ( ! $this->activated( 'taxonomy_manager' ) ) return;

		$this->settings  = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->tax_callbacks = new TaxonomyCallbacks();

		$this->setSubPages();

		$this->setSettings();

		$this->setSections();

		$this->setFields();

		$this->settings->addSubPages( $this->subpages )->register();

		$this->storeTaxonomies();

		if ( ! empty( $this->taxonomies ) ) {
			add_action( 'init', array( $this, 'registerTaxonomy' ) );
		}

	}

	public function setSubPages()
	{
		$this->subpages = array(
			array(
// menu subpages to be stored within the plugin menu
				'parent_slug' => 'matts_plugin', 
				'page_title'  => 'Taxonomy', 
				'menu_title'  => 'Taxonomy Manager', 
				'capability'  => 'manage_options', 
				'menu_slug'   => 'matts_taxonomy', 
				'callback'    =>  array( $this->callbacks, 'adminTaxonomy' )
			)
		);
	}

	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'matts_plugin_tax_settings',
				'option_name'  => 'matts_plugin_tax',
				'callback'     => array($this->tax_callbacks, 'taxSanitize')
			)
		);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id'       => 'matts_tax_index',
				'title'    => 'Taxonomy Manager',
				'callback' => array($this->tax_callbacks, 'taxSectionManager'),
				'page'     => 'matts_taxonomy'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array(
			array(
				'id'       => 'taxonomy',
				'title'    => 'Taxonomy ID',
				'callback' => array($this->tax_callbacks, 'textField'),
				'page'     => 'matts_taxonomy',
				'section'  => 'matts_tax_index',
				'args'     => array(
					'option_name' => 'matts_plugin_tax',
					'label_for'   => 'taxonomy',
					'placeholder' => 'eg. genre',
					'array'       => 'taxonomy'
				)
			),
			array(
				'id'          => 'singular_name',
				'title'       => 'Singular Name',
				'callback'    => array( $this->tax_callbacks, 'textField' ),
				'page'        => 'matts_taxonomy',
				'section'     => 'matts_tax_index',
				'args'        => array(
					'option_name' => 'matts_plugin_tax',
					'label_for'   => 'singular_name',
					'placeholder' => 'eg. Genre',
					'array'       => 'taxonomy'
				)
			),
			array(
				'id'          => 'hierarchical',
				'title'       => 'Hierarchical',
				'callback'    => array( $this->tax_callbacks, 'checkboxField' ),
				'page'        => 'matts_taxonomy',
				'section'     => 'matts_tax_index',
				'args'        => array(
					'option_name' => 'matts_plugin_tax',
					'label_for'   => 'hierarchical',
					'class'       => 'ui-toggle',
					'array'       => 'taxonomy'
				)
			),
			array(
				'id'          => 'objects',
				'title'       => 'Post Types',
				'callback'    => array( $this->tax_callbacks, 'checkboxPostTypesField' ),
				'page'        => 'matts_taxonomy',
				'section'     => 'matts_tax_index',
				'args'        => array(
					'option_name' => 'matts_plugin_tax',
					'label_for'   => 'objects',
					'class'       => 'ui-toggle',
					'array'       => 'taxonomy'
				)
			)
		);

		$this->settings->setFields( $args );
	}

	public function storeTaxonomies()
	{
// get the taxonmies array
		$options = get_option( 'matts_plugin_tax' ) ?: array();

// store those info into an array
		foreach ($options as $option) {

			$labels = array(
				'name'              => $option['singular_name'],
				'singular_name'     => $option['singular_name'],
				'search_items'      => 'Search ' . $option['singular_name'],
				'all_items'         => 'All ' . $option['singular_name'],
				'parent_item'       => 'Parent ' . $option['singular_name'],
				'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
				'edit_item'         => 'Edit ' . $option['singular_name'],
				'update_item'       => 'Update ' . $option['singular_name'],
				'add_new_item'      => 'Add New ' . $option['singular_name'],
				'new_item_name'     => 'New ' . $option['singular_name'] . ' Name',
				'menu_name'         => $option['singular_name'],
			);

			$this->taxonomies[] = array(
				'hierarchical'      => isset($option['hierarchical']) ? true : false,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $option['taxonomy'] ),
				'objects'           => isset($option['objects']) ? $option['objects'] : null
			);

		}	
	}	
// register the taxonomy
	public function registerTaxonomy()
	{
		foreach ( $this->taxonomies as $taxonomy ) 
		{
			$objects = isset($taxonomy['objects']) ? array_keys($taxonomy['objects']) :null;
			register_taxonomy( $taxonomy['rewrite']['slug'], array_keys($taxonomy['objects']), $taxonomy );
		}
	}
}