<?php
/**
 * Contains External_Blogs class.
 *
 * @copyright 2017 Sigma Software
 * @package   UB
 *
 * @author    Victor Bairak(victor.bairak@sigma.software).
 */

namespace UB\External_Blogs;

/**
 * Class External_Blogs.
 *
 * @package UB
 * @author  Victor Bairak <victor.bairak@sigma.software>
 */
class External_Blogs {

	public function get_external_blogs( $domains = array() ) {

		$blogs = array();
		$remote_urls = $this->get_remote_urls();
		foreach ( $remote_urls as $url ) {
			if ( $endpoint = $this->get_remote_endpoint( $url ) ) {
				$blogs = $this->request( $endpoint, $domains );
			}
		}


		return $blogs;
	}

	private function request( $endpoint = '', $domains = array() ) {
		$domains = strtolower( implode( ',', $domains ) );
		$url = $endpoint . $domains;
		$response = wp_remote_get( $url );
		return array();
	}

	private function get_remote_urls() {

		// TODO instead of using constant add Multinetwork option for that.
		if ( defined( 'EXTERNAL_NETWORK_URL' ) ) {
			return (array) EXTERNAL_NETWORK_URL;
		}

		return array();
	}

	private function get_remote_endpoint( $url = '' ) {

		$endpoint = false;

		if ( false !== filter_var( $url, FILTER_VALIDATE_URL ) ) {
			$endpoint = $url . '/wp-json/' . Rest_Blog_Data::REST_NAMESPACE . Rest_Blog_Data::REST_ROUTE_PREFIX;
		}
		return $endpoint;
	}

}
