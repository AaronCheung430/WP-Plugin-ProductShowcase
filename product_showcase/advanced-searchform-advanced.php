<form method="get" id="advanced-searchform-advanced" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">

    <?php 
    $titleW = isset( $instance['title_w'] ) ? $instance['title_w'] : '';
    $title = isset( $instance['title'] ) ? $instance['title'] : '';

    ?>

    <h3><?php echo ($titleW) ?></h3>

    <!-- PASSING THIS TO TRIGGER THE ADVANCED SEARCH RESULT PAGE FROM functions.php -->
    <input type="hidden" name="search" value="advanced2">

    <input type="hidden" name="title" value="<?php echo $title; ?>">

    <label for="name" class=""><?php _e( 'Keyword: ', 'textdomain' ); ?></label><br>
    <input type="text" value="<?php echo $_GET['s'] != '' ? $_GET['s'] : ''; ?>" placeholder="<?php _e( 'Type here', 'textdomain' ); ?>" name="s" id="s" />

    <p>
    <label for="headnotes" class=""><?php _e( 'Select a Headnote: ', 'textdomain' ); ?></label><br>
    <select name="headnotes" id="headnotes">
        <option value=""><?php _e( 'Select one...', 'textdomain' ); ?></option>
        <?php
        $headnotes = get_terms(['taxonomy'=>'headnotes']);
        foreach($headnotes as $headnote):
        ?>
        <option value="<?php echo $headnote->name; ?>"<?php if ($_GET['headnotes']==$headnote->name) echo ' selected';?>><?php echo $headnote->name; ?></option>
        <?php endforeach ?>
    </select>
    
    <br />
    <label for="heartnotes" class=""><?php _e( 'Select a Heartnote: ', 'textdomain' ); ?></label><br>
    <select name="heartnotes" id="heartnotes">
        <option value=""><?php _e( 'Select one...', 'textdomain' ); ?></option>
        <?php
        $heartnotes = get_terms(['taxonomy'=>'heartnotes']);
        foreach($heartnotes as $heartnote):
        ?>
        <option value="<?php echo $heartnote->name; ?>"<?php if ($_GET['heartnotes']==$heartnote->name) echo ' selected';?>><?php echo $heartnote->name; ?></option>
        <?php endforeach ?>
    </select>
    <br />
    <label for="basenotes" class=""><?php _e( 'Select a Basenote: ', 'textdomain' ); ?></label><br>
    <select name="basenotes" id="basenotes">
        <option value=""><?php _e( 'Select one...', 'textdomain' ); ?></option>
        <?php
        $basenotes = get_terms(['taxonomy'=>'basenotes']);
        foreach($basenotes as $basenote):
        ?>
        <option value="<?php echo $basenote->name; ?>"<?php if ($_GET['basenotes']==$basenote->name) echo ' selected';?>><?php echo $basenote->name; ?></option>
        <?php endforeach ?>
    </select>
    </p>
    <p><br />
    <input type="submit" id="searchsubmit" value="Search" />
    </p>

</form>




