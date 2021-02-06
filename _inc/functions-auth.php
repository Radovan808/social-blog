<?php


	/**
	 * Logged In
	 *
	 * Is a user logged in?
	 *
	 * @return bool
	 */
	function logged_in()
	{
		global $auth, $auth_config;

		return
			isset($_COOKIE[$auth_config->cookie_name]) &&
			$auth->checkSession($_COOKIE[$auth_config->cookie_name]);
	}



	/**
	 * Do Login
	 *
	 * Create cookie after logging in
	 *
	 * @param   array  $data
	 * @return  bool
	 */
	function do_login( $data )
	{
		global $auth_config;

		return setcookie(
			$auth_config->cookie_name,
			$data['hash'],
			$data['expire'],
			$auth_config->cookie_path,
			$auth_config->cookie_domain,
			$auth_config->cookie_secure,
			$auth_config->cookie_http
		);
	}



	/**
	 * Do Logout
	 *
	 * Log the user out
	 *
	 * @return bool
	 */
	function do_logout()
	{
		if ( ! logged_in() ) return true;

		global $auth, $auth_config;

		return $auth->logout( $_COOKIE[$auth_config->cookie_name] );
	}



	/**
	 * Get user
	 *
	 * Grab data for the logged in user
	 *
	 * @param  integer  $user_id
	 * @return bool|mixed
	 */
	function get_user( $user_id = 0 )
	{
		global $auth, $auth_config;

		if ( ! $user_id && logged_in() ) {
			$user_id = $auth->getSessionUID($_COOKIE[$auth_config->cookie_name]) ?: 0;
		}

		return (object) $auth->getUser( $user_id );
	}



	/**
	 * Can Edit
	 *
	 * True if this post was written by the logged in user
	 *
	 * @param  mixed  $post
	 * @return bool
	 */
	function can_edit( $post )
	{
		// must be logged in
		if ( ! logged_in() ) {
			return false;
		}

		if ( is_object( $post ) ) {
			$post_id = (int) $post->user_id;
		}
		else {
			$post_id = (int) $post['user_id'];
		}

		$user = get_user();

		return $post_id === $user->uid;
	}