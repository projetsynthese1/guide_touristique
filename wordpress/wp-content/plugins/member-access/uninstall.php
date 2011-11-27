<?php

    global $wpdb;
    delete_option('member_access_options');
    $wpdb->query(sprintf("ALTER TABLE %s DROP %s", $wpdb->posts, $wpdb->escape('member_access_visibility')));
