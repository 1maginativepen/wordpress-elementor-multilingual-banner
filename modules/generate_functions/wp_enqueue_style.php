<?php

    function load_scripts() {
        echo "Does this output to the actual page?"; 
    }
    add_action('wp_enqueue_scripts', 'load_scripts');

?>