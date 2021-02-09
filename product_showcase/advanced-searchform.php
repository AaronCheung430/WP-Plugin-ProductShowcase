<form method="get" id="advanced-searchform" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">

    <?php 
    $titleW = isset( $instance['title_w'] ) ? $instance['title_w'] : '';
    $title = isset( $instance['title'] ) ? $instance['title'] : '';
    ?>

    <h3><?php echo ($titleW) ?></h3>

    <!-- PASSING THIS TO TRIGGER THE ADVANCED SEARCH RESULT PAGE FROM functions.php -->
    <input type="hidden" name="search" value="advanced">

    <input type="hidden" name="title" value="<?php echo $title; ?>">

    <label for="name" class=""><?php _e( 'Notes Name: ', 'textdomain' ); ?></label><br>
    <input type="text" value="<?php echo $_GET['s'] != '' ? $_GET['s'] : ''; ?>" placeholder="<?php _e( 'Type here', 'textdomain' ); ?>" name="s" id="s" />

    <p>
    <label for="notes" class=""><?php _e( 'Select a Note: ', 'textdomain' ); ?></label><br>
    <select name="notes" id="notes">
        <option value=""><?php _e( 'Select one...', 'textdomain' ); ?></option>
        <option value="headnotes"<?php if ($_GET['notes']=='headnotes') echo ' selected';?>><?php _e( 'Headnotes', 'textdomain' ); ?></option>
        <option value="heartnotes"<?php if ($_GET['notes']=='heartnotes') echo ' selected';?>><?php _e( 'Heartnotes', 'textdomain' ); ?></option>
        <option value="basenotes"<?php if ($_GET['notes']=='basenotes') echo ' selected';?>><?php _e( 'Basenotes', 'textdomain' ); ?></option>
    </select>
    </p>
    <p><br />
    <input type="submit" id="searchsubmit" value="Search" />
    </p>

</form>




