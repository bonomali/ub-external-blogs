<?php
/**
 * Contains storage class for handling Blogs list.
 *
 * @copyright 2017 Sigma Software
 * @package   UB
 *
 * @author    Victor Bairak(victor.bairak@sigma.software).
 */

namespace UB\External_Blogs;

/**
 * Class Blogs_Data.
 *
 * @package UB
 * @author  Victor Bairak <victor.bairak@sigma.software>
 */
class Blogs_List {

	/**
	 * Array of Blogs objects.
	 *
	 * @var Blog[]
	 */
	private $blogs = array();

	/**
	 * Blogs IDs in format domain->blog_id.
	 *
	 * @var array
	 */
	private $blogs_ids;

	/**
	 * Get Blog data by domain.
	 *
	 * @param string $domain Domain.
	 *
	 * @return bool|array Return false if blog doesn't exist.
	 */
	public function get_blog_data_by_domain( $domain = '' ) {

		$domain = strtolower( $domain );

		if ( ! isset( $this->blogs_ids ) ) {
			$this->set_blogs_ids();
		}

		// If domain is not in current network, return false.
		if ( ! array_key_exists( $domain, $this->blogs_ids ) ) {
			return false;
		}

		// If blog is not in a blogs list, assume that it wasn't added yet, and add it.
		if ( ! array_key_exists( $domain, $this->blogs ) ) {
			$this->blogs[ $domain ] = new Blog( $this->blogs_ids[ $domain ] );
		}

		return $this->blogs[ $domain ]->get_data();
	}

	/**
	 * Set blogs_ids with registered PUBLIC blogs IDs.
	 *
	 * @return void
	 */
	private function set_blogs_ids() {

		/* @var \wpdb $wpdb */
		global $wpdb;

		$this->blogs_ids = array();

		// Get all public blogs.
		$blogs = get_sites( array(
			'public' => 1,
			'number' => 0,
		) );

		/* @var \WP_Site $blog */
		foreach ( $blogs as $blog ) {
			$this->blogs_ids[ $blog->domain ] = $blog->blog_id;
		}

		// Get blogs from mapping table (Mercator).
		$suppress             = $wpdb->suppress_errors( true );
		$domain_mapping_table = "{$wpdb->base_prefix}domain_mapping";
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$domain_mapping_table}'" ) === $domain_mapping_table ) {
			$result = $wpdb->get_results( "SELECT * FROM {$domain_mapping_table} WHERE 1 = 1" );
			foreach ( $result as $blog ) {

				// Check if domain belongs to one of active blogs.
				if ( in_array( $blog->blog_id, $this->blogs_ids ) ) {
					$this->blogs_ids[ $blog->domain ] = $blog->blog_id;
				}
			}
		}
		$wpdb->suppress_errors( $suppress );
	}

}
