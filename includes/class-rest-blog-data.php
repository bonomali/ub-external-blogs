<?php
/**
 * Contains class Rest_Blog_Data.
 *
 * @copyright 2017 Sigma Software
 * @package   UB
 *
 * @author    Victor Bairak(victor.bairak@sigma.software).
 */

namespace UB\External_Blogs;

use \WP_Error, \WP_REST_Request;

/**
 * Class Rest_Blog_Data.
 *
 * @package UB
 * @author  Victor Bairak <victor.bairak@sigma.software>
 */
class Rest_Blog_Data {

	const REST_NAMESPACE = 'external-blogs/v1';

	const REST_ROUTE_PREFIX = '/blog-data/';

	/**
	 * Constructor.
	 *
	 * @return Rest_Blog_Data
	 */
	public function __construct() {

		add_action( 'rest_api_init', function () {
			// "domains" argument should contain comma separated domains list.
			register_rest_route( self::REST_NAMESPACE, self::REST_NAMESPACE . 'P<domains>((([a-z\d]([a-z\d\-]{0,61}[a-z\d])?\.)+[a-z]{2,6}),?)+)', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'rest_blog_data' ),
				'args'     => array(
					'domains' => array(
						'required' => true,
					),
				),
			) );
		} );
	}

	/**
	 * REST-handler for getting blog data.
	 *
	 * @param WP_REST_Request $data REST-request object.
	 *
	 * @return array|WP_Error
	 */
	public function rest_blog_data( $data ) {

		$blogs      = explode( ',', $data['domains'] );
		$blogs      = array_fill_keys( $blogs, false );
		$blogs_list = new Blogs_List();
		array_walk( $blogs, function( &$blog, $domain ) use ( $blogs_list ) {
			$blog = $blogs_list->get_blog_data_by_domain( $domain );
		});

		return $blogs;

	}

}
