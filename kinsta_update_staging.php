<?php
/**
 * Update site to use test mode in local and staging environments
 */
function update_staging_env() {
	// If settings have already been updated, return early, unless we are forcing update with query string &update-staging=true
	if ( 1 == get_transient( 'staging-settings-updated' ) && 'true' != $_GET['update-staging'] ) {
		return;
	}
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
	// If site url is a kinsta staging environment
	if ( preg_match( '/staging-\w*.kinsta.cloud/', site_url() ) ) {
		// Use Stripe in test mode
		$woocommerce_stripe_settings = get_option( 'woocommerce_stripe_settings', array() );
		/*if ( 'yes' != $woocommerce_stripe_settings['testmode'] ) {
			$woocommerce_stripe_settings['testmode'] = 'yes';
			update_option( 'woocommerce_stripe_settings', $woocommerce_stripe_settings );
		}*/
		// Disable outbound emails
		//deactivate_plugins( '/sendgrid-email-delivery-simplified/wpsendgrid.php' );
		activate_plugins( '/disable-emails/disable-emails.php' );
		// Transient is set for 24 hours
		set_transient( 'staging-settings-updated', 1, ( 60 * 60 * 24 ) );
		error_log( 'staging-settings-updated' );
	}
}
add_action( 'init', 'update_staging_env' );
