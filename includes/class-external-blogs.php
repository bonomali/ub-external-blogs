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

	/**
	 * Get blogs (among $domains) from remote networks(or multinetworks).
	 *
	 * @param array $domains
	 *
	 * @return array
	 */
	public function get_external_blogs( $domains = array() ) {

		$blogs = array();
		$remote_urls = $this->get_remote_urls();
		foreach ( $remote_urls as $url ) {
			if ( $endpoint = $this->get_remote_endpoint( $url ) ) {
				$blogs_response = $this->request( $endpoint, $domains );
				foreach ( $blogs_response as $domain => $blog ) {
					if ( $blog && empty( $blogs[ $domain ] ) ) {
						$blogs[ $domain ] = $blog;
					}
				}
			}
		}

		return $blogs;
	}

	/**
	 * Make request to external endpoint for blogs data.
	 *
	 * @param string $endpoint
	 * @param array  $domains
	 *
	 * @return array
	 */
	private function request( $endpoint = '', $domains = array() ) {

		$blogs    = array();
		$domains  = strtolower( implode( ',', $domains ) );
		$url      = $endpoint . $domains;
		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
			$blogs = (array) json_decode( $response['body'], true );
		}

		return $blogs;
	}

	/**
	 * Get list of remote urls (one url per external network) that we should to check for blogs.
	 *
	 * @return array
	 */
	private function get_remote_urls() {

		// TODO: instead of using constant add Multinetwork option for that.
		if ( defined( 'EXTERNAL_NETWORK_URL' ) ) {
			return (array) EXTERNAL_NETWORK_URL;
		}

		return array();
	}

	/**
	 * Get remote endpoint url.
	 *
	 * @param string $url
	 *
	 * @return bool|string
	 */
	private function get_remote_endpoint( $url = '' ) {

		$endpoint = false;

		if ( false !== filter_var( $url, FILTER_VALIDATE_URL ) ) {
			$endpoint = $url . '/wp-json/' . Rest_Blog_Data::REST_NAMESPACE . Rest_Blog_Data::REST_ROUTE_PREFIX;
		}
		return $endpoint;
	}

}
