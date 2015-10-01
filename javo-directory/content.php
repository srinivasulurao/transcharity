<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> class="row">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<div class="featured-post">
			<?php _e( 'Featured post', 'javo_fr' ); ?>
		</div>
		<?php endif; ?>

		<div class="row">

			<div class="col-md-12">
				<header class="entry-header text-center">
					<?php the_post_thumbnail('full', Array('class' => 'img-responsive')); ?>
				</header><!-- .entry-header -->
			</div><!-- col-md-4 -->

		</div>
		<div class="row">
			<div class="col-md-12">
				<?php if ( is_single() ) : ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="single-post-meta row">
						<div class="col-md-10 post-meta-infor">
							<i class="fa fa-calendar"></i>&nbsp;<?php the_date('Y-m-d', '<time>', '</time>'); ?>&nbsp;&nbsp;
							<i class="fa fa-user"></i>&nbsp;<?php the_author_meta('display_name'); ?>&nbsp;&nbsp;
							<i class="fa fa-folder-open"></i>&nbsp;<?php echo javo_get_cat($post->ID, "category");?>&nbsp;&nbsp;
							<?php if ( comments_open() ) : ?>
								<i class="fa fa-comment"></i>
								<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'javo_fr' ) . '</span>', __( '1 Reply', 'javo_fr' ), __( '% Replies', 'javo_fr' ) ); ?>
							<?php endif; // comments_open() ?>
						</div> <!-- col-md-10 -->

						<div class="col-md-2 text-right post-social">
							<span class="javo-sns-wrap social-wrap">
								<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
									<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
								</i>
								<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
									<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
								</i>
							</span>
						</div> <!-- col-md-2-->
					</div> <!-- single-post-meta -->

				<?php else : ?>
					<h1 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h1>
				<?php endif; // is_single() ?>
				<?php if ( is_search() ) : // Only display Excerpts for Search ?>
					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div><!-- .entry-summary -->
				<?php else : ?>
					<div class="entry-content">
						<?php
						if( is_category() )
						{
							printf('<a href="%s">%s</a>', get_permalink(), javo_str_cut( get_the_excerpt(), 300));
						}else{
							the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'javo_fr' ) );
						} ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'javo_fr' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->
				<?php endif; ?>
			</div><!-- 12 Columns Close -->
		</div><!-- Row Close -->
		<footer class="entry-meta">
			<div class="inner-footer">
			<?php //javo_drt_entry_meta(); ?>
			<?php edit_post_link( __( 'Edit', 'javo_fr' ), '<span class="edit-link">', '</span>' ); ?>
			<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
				<div class="author-info">
					<div class="author-avatar">
						<?php
						/** This filter is documented in author.php */
						$author_bio_avatar_size = apply_filters( 'javo_drt_author_bio_avatar_size', 68 );
						echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
						?>
					</div><!-- .author-avatar -->
					<div class="author-description">
						<h2><?php printf( __( 'About %s', 'javo_fr' ), get_the_author() ); ?></h2>
						<p><?php the_author_meta( 'description' ); ?></p>
						<div class="author-link">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
								<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'javo_fr' ), get_the_author() ); ?>
							</a>
						</div><!-- .author-link	-->
					</div><!-- .author-description -->
				</div><!-- .author-info -->
			<?php endif; ?>
			</div>
		</footer><!-- .entry-meta -->

	</article><!-- #post -->