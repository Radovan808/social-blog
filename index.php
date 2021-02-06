<?php

	require_once '_inc/config.php';

	$routes = [

		// HOMEPAGE
		'/' => [
			'GET'  => 'home.php'
		],

		// USER
		'/user' => [
			'GET'  => 'user.php'             // user profile
		],

		// LOGIN
		'/login' => [
			'GET'  => 'login.php',           // login form
			'POST' => 'login.php',           // do login
		],

		// REGISTER
		'/register' => [
			'GET'  => 'register.php',        // register form
			'POST' => 'register.php',        // do register
		],

		// LOGOUT
		'/logout' => [
			'GET'  => 'logout.php',          // logout user
		],

		// POST
		'/post' => [
			'GET'  => 'post.php',             // show post
			'POST' => '_admin/post-add.php',  // add new post
		],

		// TAG
		'/tag' => [
			'GET'  => 'tag.php',  // show posts for tag
		],

		// EDIT
		'/edit' => [
			'GET'  => 'edit.php',              // edit form
			'POST' => '_admin/post-edit.php',  // store new values
		],

		// DELETE
		'/delete' => [
			'GET'  => 'delete.php',             // delete form
			'POST' => '_admin/post-delete.php', // make the delete
		],

	];

	$page   = segment(1);
	$method = $_SERVER['REQUEST_METHOD'];

	// guests can go here
	$public = [
		'login', 'register'
	];

	// not logged in, you can only visit $public links
	if ( !logged_in() && !in_array( $page, $public ) ) {
		redirect('/login');
	}

	// show page
	if ( ! isset( $routes["/$page"][$method] ) ) {
		show_404();
	}

	require $routes["/$page"][$method];