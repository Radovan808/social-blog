<?php

	$id = segment(2);

	// add new post form
	if ( $id === 'new' ) {
		include_once 'add.php';
		die();
	}

	try {
		$post = get_post( $id );
	}
	catch ( PDOException $e ) {
		$post = false;
	}

	if ( ! $post ) {
		flash()->error("doesn't exist:(");
		redirect('/');
	}

	$page_title = $post->title;
	include_once "_partials/header.php";

?>

	<section class="box">
		<article class="post full-post">

			<header class="post-header">
				<h1 class="box-heading">
					<a href="<?= $post->link ?>"><?= $post->title ?></a>

					<?php if ( can_edit($post) ) : ?>
						<a href="<?= get_edit_link( $post ) ?>" class="btn btn-xs edit-link">edit</a>
						<a href="<?= get_delete_link( $post ) ?>" class="btn btn-xs edit-link">&times;</a>
					<?php endif ?>

					<time datetime="<?= $post->date ?>">
						<small><?= $post->time ?></small>
					</time>
				</h1>
			</header>

			<div class="post-content">
				<?= $post->text ?>
				<p class="written-by small">
					<small>- written by <a href="<?= $post->user_link ?>"><?= $post->email ?></a></small>
				</p>
			</div>

			<footer class="post-footer">
				<?php include '_partials/tags.php' ?>
			</footer>

		</article>
	</section>

<?php include_once "_partials/footer.php" ?>