<?php
/**
 * Services archives template
 *
 * @package Product_Showcase
 */
get_header(); 
?>
	<?php do_action('sydney_before_content'); ?>

    <div id="primary" class="content-area col-md-9">
	<main id="main" class="post-wrap" role="main">

<?php
// Get data from URL into variables
$terms = $_GET['s'] != '' ? $_GET['s'] : '';
$title = $_GET['title'] != '' ? $_GET['title'] : '';
$notes = $_GET['notes'] != '' ? $_GET['notes'] : '';
$mode = $_GET['search'] != '' ? $_GET['search'] : '';
$headnote = $_GET['headnotes'] != '' ? $_GET['headnotes'] : '';
$heartnote = $_GET['heartnotes'] != '' ? $_GET['heartnotes'] : '';
$basenote = $_GET['basenotes'] != '' ? $_GET['basenotes'] : '';
//$_model = $_GET['model'] != '' ? $_GET['model'] : '';

	// Start the Query
if ($mode == 'advanced2') {
	the_widget( 'Menu_Products', array('title' => $title. $terms, 'terms'=>$terms, 'headnotes' => $headnote, 'heartnotes' => $heartnote, 'basenotes' => $basenote, 'mode' => 2));
} else {
	the_widget( 'Menu_Products', array('title' => $title. $terms, 'terms'=>$terms, 'notes' => $notes, 'mode' => 1));
}

?>
	</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
