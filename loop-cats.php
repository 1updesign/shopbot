<?php //modified from loop.php ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'imbalance2' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'imbalance2' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php $imbalance2_theme_options = get_option('imbalance2_theme_options') ?>

<style type="text/css">

	.catcontent p:first-child {
		display:none;
	}

</style>

<div id="boxes">
<?php foreach(get_all_category_ids() as $cat_id): ?>
	<?php query_posts("category_id=$cat_id&posts_per_page=1"); ?>
	<?php $c = get_category( $cat_id ); ?>
	<?php //print_r($c); ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<div class="box" class="category">
			<div class="rel">
				<h1><a href="<?php echo get_category_link($cat_id); ?>" class="catcontent"><?php echo $c->name ?></a></h1>
				<div class="catcontent"><?php the_content(); ?></div>
				<!-- shown on hover -->
				<div class="texts">
					<div class="abs">
						<h1><a href="<?php echo get_category_link($cat_id); ?>" class="catcontent"><?php echo $c->name ?></a></h1>
						<div class="catcontent"><?php the_content(); ?></div>
					</div>
				</div>
				<!-- end shown on hover -->
			</div>
		</div>

	<?php endwhile; ?>
<?php endforeach; ?>
</div>

<?php if ( $wp_query->max_num_pages > 1 ) :
	if ( $imbalance2_theme_options['navigation'] == 0 ) : // Default ?>

<div class="fetch">
	<?php // next_posts_link( __( 'Load more posts', 'imbalance2' ) ); ?>
</div>

<script type="text/javascript">
// Ajax-fetching "Load more posts"
$('.fetch a').live('click', function(e) {
	e.preventDefault();
	$(this).addClass('loading').text('Loading...');
	$.ajax({
		type: "GET",
		url: $(this).attr('href') + '#boxes',
		dataType: "html",
		success: function(out) {
			result = $(out).find('#boxes .box');
			nextlink = $(out).find('.fetch a').attr('href');
			$('#boxes').append(result).masonry('appended', result);
			$('.fetch a').removeClass('loading').text('Load more posts');
			if (nextlink != undefined) {
				$('.fetch a').attr('href', nextlink);
			} else {
				$('.fetch').remove();
			}
		}
	});
});
</script>

	<?php elseif ( $imbalance2_theme_options['navigation'] == 1 ) : // Infinite scroll ?>

<div class="infinitescroll">
	<?php next_posts_link( __( 'Load more posts', 'imbalance2' ) ); ?>
</div>

<script type="text/javascript">
// Infinite Scroll
var href = 'first';
$(document).ready(function() {
	$('#boxes').infinitescroll({
		navSelector : '.infinitescroll',
		nextSelector : '.infinitescroll a',
		itemSelector : '#boxes .box',
		loadingImg : '<?php echo get_bloginfo('stylesheet_directory') ?>/images/loading.gif',
		loadingText : 'Loading...',
		donetext : 'No more pages to load.',
		debug : false
	}, function(arrayOfNewElems) {
		$('#boxes').masonry('appended', $(arrayOfNewElems));
		if (href != $('.infinitescroll a').attr('href'))
		{
			href = $('.infinitescroll a').attr('href');
		}
	});
});
</script>

	<?php endif; ?>

<?php endif; ?>