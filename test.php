<?php
// Template Name: Testing


// Get the cart instance
// Replace 'flat_rate' with the desired shipping method ID
// $defaultShippingMethod = get_default_shipping_method();

// // Check if the current shipping method is not set (or set dynamically based on user selection)
// if (!isset($currentShippingMethod) || empty($currentShippingMethod)) {
//     // Force the default shipping method
//     $currentShippingMethod = $defaultShippingMethod;
// }

// // Render the shipping method in your checkout/cart template
// echo "<div class='shipping-method'>$currentShippingMethod</div>";

// Loop through each shipping package
// foreach ($shipping_packages as $package_key => $package) {
//     // Get the available shipping methods for the package
//     $available_methods = $cart->get_available_shipping_methods($package);

//     // Loop through each available shipping method
//     foreach ($available_methods as $method_key => $method) {
//         // Get the shipping method ID
//         $method_id = $method->id;

//         // Get the shipping method title
//         $method_title = $method->get_title();

//         // Output or use the shipping method information as needed
//         echo "Shipping Method ID: $method_id, Title: $method_title <br>";
//     }
// }

// get_header();
// echo do_shortcode('[wcc_switcher]');

// get_footer();


//15518,10087
delete_post_meta(23607, 'is_customer_mail_sent');
delete_post_meta(15518, 'is_customer_mail_sent');

//_sendCustomEmailToCustomerAfterApplyingTrackingSystem();












//$order = wc_get_order(10087); //10087
// $data = get_post_meta(1522, 'is_customer_mail_sent', true);
// print_r($data);
// $data = get_post_meta(1929, '_wc_shipment_tracking_items', true);
// print_r($data);
//_sendCustomEmailToCustomerAfterApplyingTrackingSystem();
// Get all orders
// $args = array(
//     'status' => 'completed', // Retrieves orders with any status
//     'limit' => -1, // Retrieves all orders
//     'meta_key'      => 'is_customer_mail_sent', // Postmeta key field
//     'meta_value'    => "YES", // Postmeta value field
//     'meta_compare'  => 'NOT EXISTS',
// );

// $orders = wc_get_orders($args);

// foreach ($orders as $order) {
//     $order_id = $order->get_id();
//     $order_status = $order->get_status();
//     // Add more order details as needed
//     echo "Order ID: $order_id, Status: $order_status ," . get_post_meta($order_id, 'is_customer_mail_sent') . "<br>";
//     print_r(get_post_meta($order_id, 'is_customer_mail_sent'));
//     echo "<hr>";
//     if ($order_id == 10087) {
//         sendCustomerMailByORDID($order_id);
//     }
// }




// $order = wc_get_order(10087); //10087
// //$data = get_post_meta(1522, 'is_customer_mail_sent', true);
// $data = get_post_meta(1522, '_wc_shipment_tracking_items', true);

// print_r($data);

// // Set up email headers
// $headers = array('Content-Type: text/html; charset=UTF-8');

// $email_template = wc_get_template_html('emails/email-header.php', array(
//     'email_heading'   => __('Your order is complete', 'woocommerce')
// ),);
// $email_template .= wc_get_template_html('emails/email-order-details.php', array(
//     'order'           => $order
// ));
// $email_template .= wc_get_template_html('emails/email-footer.php', array(
//     'order'           => $order
// ));
// // Send the email
// wp_mail($order->get_billing_email(), __('Your order is complete', 'woocommerce'), $email_template, $headers);




// $order = wc_get_order(15562);

// // Get the tracking information
// $tracking_info = $order->get_meta('_tracking_info', true);

// print_r($tracking_info);




// $headers = array('Content-Type: text/html; charset=UTF-8');

// // Get the email template
// $email_template = wc_get_template_html('emails/customer-completed-order.php', array(
//     'order'         => $order,
//     'email_heading' => __('Your order is complete', 'woocommerce'),
// ));

// // Send the email
// wp_mail($order->get_billing_email(), __('Your order is complete', 'woocommerce'), $email_template, $headers);;


//Your TimeGem order has been received!





















// Assuming the plugin stores tracking info in order meta
// $tracking_info = $order->get_meta('_wc_shipment_tracking_items');

// $item_id = 1;

// VI_WOO_ORDERS_TRACKING_ADMIN_IMPORT_CSV::send_mail(10087, array(
//     array(
//         'order_item_id' => $item_id,
//         'order_item_name' => $item_name,
//         'tracking_number' => $tracking_number,
//         'carrier_url' => $carrier_url,
//         'tracking_url' => remove_query_arg(array('woo_orders_tracking_nonce'), $tracking_url_import),
//         'carrier_name' => $carrier_name,
//     )
// ), true);


//VI_WOO_ORDERS_TRACKING_ADMIN_IMPORT_CSV::send_mail("10087", ['dev.team2080@gmail.com'], true);
//print_r($data);
//print_r($tracking_info);

//VI_WOO_ORDERS_TRACKING_ADMIN_IMPORT_CSV::send_mail(10087);


// VI_WOO_ORDERS_TRACKING_ADMIN_IMPORT_CSV::send_mail(10087, array(
//     array(
//         'order_item_id' => $item_id,
//         'order_item_name' => $item_name,
//         'tracking_number' => $tracking_number,
//         'carrier_url' => $carrier_url,
//         'tracking_url' => remove_query_arg(array('woo_orders_tracking_nonce'), $tracking_url_import),
//         'carrier_name' => $carrier_name,
//     )
// ), true);




// Add this code to your theme's functions.php file or a custom plugin

// function send_custom_tracking_email( $order_id ) {
//     // Get the order object
//     $order = wc_get_order( $order_id );

//     // Get the tracking information
//     $tracking_info = $order->get_meta('_tracking_info', true);

//     // Check if tracking information exists
//     if ( $tracking_info ) {
//         // Compose email subject
//         $subject = 'Your Order Tracking Information has been updated';

//         // Compose email message
//         $message = 'Hello ' . $order->get_billing_first_name() . ',<br>';
//         $message .= 'Your order tracking information has been updated. Here are the details:<br>';
//         $message .= '<strong>Tracking Information:</strong><br>';
//         $message .= $tracking_info;

//         // Add additional headers if needed
//         $headers = array('Content-Type: text/html; charset=UTF-8');

//         // Send the email
//         wp_mail( $order->get_billing_email(), $subject, $message, $headers );
//     }
// }

// // Hook into the order tracking update event
// add_action( 'woocommerce_email_after_order_table', 'send_custom_tracking_email' );
