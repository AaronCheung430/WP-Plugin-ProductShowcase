<?php
/**
 * The template for displaying all single posts.
 *
 * @package Product_Showcase
 */

get_header(); ?>

	<?php $fullwidth = 'fullwidth';	?>

	<?php do_action('sydney_before_content'); ?>

	<div id="primary" class="content-area col-md-9 <?php echo $fullwidth; ?>">
		
		<main id="main" class="post-wrap" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action('sydney_inside_top_post'); ?>

	<header class="entry-header">
	
		<div class="meta-post">
			<?php //sydney_all_cats(); ?>
		</div>

		<?php //the_title( '<h1 class="title-post entry-title">', '</h1>' ); ?>

		<?php if (get_theme_mod('hide_meta_single') != 1 ) : ?>
		<div class="single-meta">
			<?php //sydney_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	


	<div class="entry-content">
		<?php //the_content(); ?>

		<?php
			$position = get_post_meta( get_the_ID(), 'wpcf-position', true );
			$facebook = get_post_meta( get_the_ID(), 'wpcf-facebook', true );
			$twitter = get_post_meta( get_the_ID(), 'wpcf-twitter', true );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sydney' ),
				'after'  => '</div>',
			) );
		?>

		<style>
		.space-top{
			padding-top: 25px;
		}
		.table1 td{
			border: 0px;
    		padding: 5px;
    		text-align: left;
		}
		.table1 td:nth-child(odd){
			width: 100px;
			vertical-align: top;
		}
		h3{
			font-size: 30px;
		}
		body, #mainnav ul ul a {
			font-family: 'Open Sans', 'Microsoft JhengHei', 'LiHei Pro Medium', sans-serif !important;
		}
		.flex-container {
			display: flex;
			flex-wrap: wrap;
			max-width: inherit !important;
		}
		.flex-container > div {
			width: 100%;
		}
		@media only screen and (min-width: 768px) {
			.flex-container h3 {
				margin-top: 0;
			}
			.flex-container > div:nth-child(1) {
				width: 45%;
				padding-right: 25px;
			}
			.flex-container > div {
				width: 55%;
			}
		}
		</style>
	

		<div class="flex-container">

		<?php if ( has_post_thumbnail() && ( get_theme_mod( 'post_feat_image' ) != 1 ) ) : ?>
		<div class="entry-thumb">
			<?php the_post_thumbnail('large-thumb'); ?>
		</div>
		<?php endif; ?>

		<div style="text-align: left;" data-title-color="#443f3f" data-headings-color="#443f3f" class="panel-widget-style panel-widget-style-for-2207-2-1-0">
		<?php
		the_title( '<h3>', '</h3>' );
		?>
		<div class="mobile-small">
			<table class="table1">
				<tbody>
					<tr>
						<td style="min-width: 85px;">Head notes:</td>
						<td><?php the_terms( $post->ID, headnotes, ' ', ', ', ' ' ); ?></td>
					</tr>
					<tr>
						<td style="min-width: 85px;">Heart notes:</td>
						<td><?php the_terms( $post->ID, heartnotes, ' ', ', ', ' ' ); ?></td>
					</tr>
					<tr>
						<td style="min-width: 85px;">Base notes:</td>
						<td><?php the_terms( $post->ID, basenotes, ' ', ', ', ' ' ); ?></td>
						<?php //echo esc_html($twitter);?>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="space-top"></div>
		<?php 
		the_content( '<div><hr class="mobile-visible"><div style="font-weight: 400; font-style: italic;">', '</div></div>' ); 
		?>
		</div>
		</div>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php //sydney_entry_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php do_action('sydney_inside_bottom_post'); ?>

</article><!-- #post-## -->


			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php do_action('sydney_after_content'); ?>

<?php if ( get_theme_mod('fullwidth_single', 0) != 1 ) {
	//get_sidebar();
} ?>
<?php get_footer(); ?>
