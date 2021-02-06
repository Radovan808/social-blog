<?php

	// include
	require '../_inc/config.php';

	// just to be safe
	if ( ! logged_in() ) {
		redirect('/');
	}


	// c'mon baby do the locomo.. validation
	if ( ! $data = validate_post() ) {
		redirect('back');
	}

	extract( $data );
	$slug = slugify( $title );

	$query = $db->prepare("
		INSERT INTO posts
			( user_id, title, text, slug )
		VALUES
			( :uid, :title, :text, :slug )
	");

	$insert = $query->execute([
		'uid'   => get_user()->uid,
		'title' => $title,
		'text'  => $text,
		'slug'  => $slug
	]);



	if ( ! $insert )
	{
		flash()->warning( 'sorry, girl' );
		redirect('back');
	}

    //file upload

    // save the file in directory
    $uploaded_file = 'uploads/'.$_FILES['the_file']['name'];

    if (is_uploaded_file($_FILES['the_file']['tmp_name'])) {
        if (!move_uploaded_file($_FILES['the_file']['tmp_name'], $uploaded_file)) {
            echo 'Chyba: nelze přesunout soubor do cílového adresáře.';
            exit;
        }

    }

	// great success!
	$post_id = $db->lastInsertId();

    //add file in to database
    $query = $db->prepare("
                INSERT INTO image
                    ( post_id, filename )
                VALUES
                    ( :post_id, :filename )
            ");

    $insert = $query->execute([
        'post_id' => $post_id,
        'filename' => $uploaded_file
    ]);
	// if we have tags, add them
	if ( isset( $tags ) && $tags = array_filter( $tags ) )
	{
		foreach ( $tags as $tag_id )
		{
			$insert_tags = $db->prepare("
				INSERT INTO posts_tags
				VALUES (:post_id, :tag_id)
			");

			$insert_tags->execute([
				'post_id' => $post_id,
				'tag_id'  => $tag_id
			]);
		}
	}

	// let's visit the new post
	flash()->success( 'yay, new one!' );

	redirect(get_post_link([
		'id'   => $post_id,
		'slug' => $slug,
	]));


