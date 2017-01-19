<?php
/**
 * Contains Settings class.
 *
 * @copyright 2017 Sigma Software
 * @package   UB
 *
 * @author    Dmitriy Mamlyga <dmitriy.mamlyga@sigma.software>
 */

namespace UB\External_Blogs;

/**
 * Class Settings.
 *
 * @package UB
 * @author  Dmitriy Mamlyga <dmitriy.mamlyga@sigma.software>
 */
class Settings {

	/**
	 * Instance of the object.
	 *
	 * @var Settings
	 */
	public static $instance;

	/**
	 * Global prefix for metabox.
	 *
	 * @var string
	 */
	protected $prefix = 'ub_external_blogs_options_';

	/**
	 * Option key, and option slug
	 *
	 * @var string
	 */
	protected $key = 'ub_external_blogs_options';

	/**
	 * Options page metabox id
	 *
	 * @var string
	 */
	protected $metabox_id = 'ub_blogger_settings_option_metabox';

	/**
	 * Options Page title
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 *
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Instantiation.
	 *
	 * @return Settings
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new Settings();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 */
	private function __construct() {

		$this->title = __( 'External Blogs', 'ub' );

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_settings' ) );

	}

	/**
	 * Register our setting to WP
	 *
	 * @since  0.1.0
	 */
	public function admin_init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 *
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUT
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 *
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo esc_attr( $this->key ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 *
	 * @since  0.1.0
	 */
	public function add_settings() {
		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box( array(
			'id'            => $this->metabox_id,
			'title'         => __( 'External blogs options', 'ub' ),
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key ),
			),
		) );

		$cmb->add_field( array(
			'name'    => 'Blog image',
			'desc'    => 'Please upload an image or enter an URL to set you blog image.',
			'id'      => $this->prefix . 'image',
			'type'    => 'file',
			// Optional:
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Add File',
			),
		) );
		$cmb->add_field( array(
			'name' => _x( 'Exclude form top list', 'wp-admin external bogs options', 'ub' ),
			'desc' => __( 'Check to remove you blog from the top list.', 'ub' ),
			'id'   => $this->prefix . 'top_list_status',
			'type' => 'checkbox',
		) );
	}

	/**
	 * Get blog image url.
	 *
	 * @return string
	 */
	public function get_blog_image_url() {
		return cmb2_get_option( $this->key, $this->prefix . 'image' );
	}

	/**
	 * Get top postr list exclude value.
	 *
	 * @return bool
	 */
	public function get_top_list_exclude_status() {
		return (bool) cmb2_get_option( $this->key, $this->prefix . 'top_list_status' );
	}
}
