<?php
/**
 * Plugin Name: Billing Emails in Orders Table
 * Description: Adds a billing email column to the WooCommerce Orders table.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: billing-emails-orders-table
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add a custom column to the WooCommerce Orders table.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function beo_add_billing_email_column( $columns ) {
    $new_columns = [];

    foreach ( $columns as $key => $column ) {
        $new_columns[ $key ] = $column;
        if ( 'order_date' === $key ) {
            $new_columns['billing_email'] = __( 'Billing Email', 'billing-emails-orders-table' );
        }
    }

    return $new_columns;
}
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'beo_add_billing_email_column' );

/**
 * Populate the custom column with data.
 *
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 */
function beo_populate_billing_email_column( $column, $post_id ) {
    if ( 'billing_email' === $column ) {
        $order = wc_get_order( $post_id );

        if ( $order ) {
            $billing_email = $order->get_billing_email();
            echo esc_html( $billing_email );
        }
    }
}
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'beo_populate_billing_email_column', 10, 2 );

/**
 * Enqueue custom CSS for the admin area.
 */
function beo_enqueue_admin_styles() {
    ?>
    <style>
        @media (min-width: 768px) {
            .column-billing_email {
                width: 20%;
            }
        }
    </style>
    <?php
}
add_action( 'admin_head', 'beo_enqueue_admin_styles' );
