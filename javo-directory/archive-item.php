<?php
/**
 * The template for displaying Archive Items
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */
get_header(); ?>
<div class="container">
	<div class="col-md-9 main-content-wrap">
		<section id="primary" class="site-content">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>
				<header class="archive-header">
					<h1 class="archive-title"><?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'javo_fr' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'javo_fr' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'javo_fr' ) ) . '</span>' );
						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'javo_fr' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'javo_fr' ) ) . '</span>' );
						else :
							_e( 'Archives', 'javo_fr' );
						endif;
					?></h1>
				</header><!-- .archive-header -->

				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					/* Include the post format-specific template for the content. If you want to
					 * this in a child theme then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

				endwhile;

				javo_drt_content_nav( 'nav-below' );
				?>

			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary -->

	</div><!-- col-md-9 -->
<?php get_sidebar(); ?>
</div> <!-- contaniner -->
<?php get_footer(); ?>