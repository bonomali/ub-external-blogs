<?php
/**
 * Contains Blog class.
 *
 * @copyright 2017 Sigma Software
 * @package   UB
 *
 * @author    Victor Bairak(victor.bairak@sigma.software).
 */

namespace UB\External_Blogs;

/**
 * Class Blog.
 *
 * @package UB
 * @author  Victor Bairak <victor.bairak@sigma.software>
 */
class Blog {

	/**
	 * Blog ID.
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Blog name.
	 *
	 * @var string
	 */
	private $blogname;

	/**
	 * Site url.
	 *
	 * @var string
	 */
	private $siteurl;

	/**
	 * Blog image url.
	 *
	 * @var string
	 */
	private $blog_img;

	/**
	 * Top list exclude status.
	 *
	 * @var string
	 */
	private $top_list_exclude_status;

	/**
	 * Class fields that can be get via self::get_data() method.
	 *
	 * @var array
	 */
	private $accessible_fields = array( 'id', 'blogname', 'siteurl', 'blog_img', 'top_list_exclude_status' );

	/**
	 * Constructor.
	 *
	 * @param int $id Blog ID.
	 *
	 * @return Blog
	 */
	public function __construct( $id ) {
		$this->id = $id;
		$this->set_data();
	}

	/**
	 * Set object properties.
	 *
	 * @return void
	 */
	private function set_data() {

		switch_to_blog( $this->id );

		// TODO: Add image option to Settings.
		$settings = Settings::get_instance();

		$this->blog_img = $settings->get_blog_image_url();
		$this->top_list_exclude_status = $settings->get_top_list_exclude_status();
		$this->blogname = get_blog_option( $this->id, 'blogname' );
		$this->siteurl = get_blog_option( $this->id, 'home' );

		restore_current_blog();
	}

	/**
	 * Get object data as array.
	 *
	 * @return array
	 */
	public function get_data() {

		$data = array();

		foreach ( $this->accessible_fields as $field ) {
			$data[ $field ] = $this->{$field};
		}

		$data['is_external'] = true;

		return $data;
	}

}
