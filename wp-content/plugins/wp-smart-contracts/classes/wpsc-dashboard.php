<?php

if( ! defined( 'ABSPATH' ) ) die;

add_action('admin_menu', function() {
    add_menu_page(__('WPSmartContracts Dashboard'), __('Smart Contracts Dashboard'), 'publish_posts', 'wpsc_dashboard', 'wpsc_dashboard', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/icon-wpsc.png', 2); 
    add_submenu_page(
        'wpsc_dashboard',
        __( 'Affiliate Program Banners', 'wp-smart-contracts' ),
        __( 'Affiliate Program Banners', 'wp-smart-contracts' ),
        'publish_posts',
        'wpsc-dashboard-affp',
        'wpsc_dashboard_affp'
    );
    add_submenu_page(
        'wpsc_dashboard',
        __( 'Batch Mint NFTs', 'wp-smart-contracts' ),
        __( 'Batch Mint NFTs', 'wp-smart-contracts' ),
        'publish_posts',
        'nft-batch-mint',
        'wpsc_nft_batch_mint'
    );
});

function wpsc_dashboard() {
    
    if (isset($_POST["wpsc-go-wallet"])) {

        $affp_wallet_1 = (isset($_POST["affp_wallet_1"]))?sanitize_text_field($_POST["affp_wallet_1"]):"";
        update_option("wpsc_affp_wallet_1", $affp_wallet_1);

        $affp_wallet_42161 = (isset($_POST["affp_wallet_42161"]))?sanitize_text_field($_POST["affp_wallet_42161"]):"";
        update_option("wpsc_affp_wallet_42161", $affp_wallet_42161);
        
        $affp_wallet_56 = (isset($_POST["affp_wallet_56"]))?sanitize_text_field($_POST["affp_wallet_56"]):"";
        update_option("wpsc_affp_wallet_56", $affp_wallet_56);
    
        $affp_wallet_137 = (isset($_POST["affp_wallet_137"]))?sanitize_text_field($_POST["affp_wallet_137"]):"";
        update_option("wpsc_affp_wallet_137", $affp_wallet_137);
    
        $affp_wallet_43114 = (isset($_POST["affp_wallet_43114"]))?sanitize_text_field($_POST["affp_wallet_43114"]):"";
        update_option("wpsc_affp_wallet_43114", $affp_wallet_43114);

        $affp_wallet_250 = (isset($_POST["affp_wallet_250"]))?sanitize_text_field($_POST["affp_wallet_250"]):"";
        update_option("wpsc_affp_wallet_250", $affp_wallet_250);

    }

    $m = new Mustache_Engine;
    $atts["logo"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/wpsmartcontracts.png';
    $atts["40"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/40.png';
    $atts["wizard01"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/wizard01.png';
    $atts["wizard02"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/wizard02.png';
    $atts["wizard01_link"] = WPSC_assets::getPage('wpsc_is_launcher');
    $atts["wizard02_link"] = admin_url('admin.php?page=nft-batch-mint');
    $atts["wizard03_link"] = admin_url('admin.php?page=wpsc-admin-setup');
    $atts["affp_link"] = admin_url('admin.php?page=wpsc-dashboard-affp');
    $atts["settings-page"] = admin_url('admin.php?page=wpsc_dashboard');
    $atts["link-launcher"] = WPSC_assets::getPage('wpsc_is_launcher');

    $atts["affp_wallet_1"] = get_option("wpsc_affp_wallet_1");
    $atts["affp_wallet_42161"] = get_option("wpsc_affp_wallet_42161");
    $atts["affp_wallet_56"] = get_option("wpsc_affp_wallet_56");
    $atts["affp_wallet_137"] = get_option("wpsc_affp_wallet_137");
    $atts["affp_wallet_43114"] = get_option("wpsc_affp_wallet_43114");
    $atts["affp_wallet_250"] = get_option("wpsc_affp_wallet_250");

    if (isset($_GET["welcome"])) {
        $atts["welcome"] = __( 'Setup done! Welcome to WP Smart Contracts', 'wp-smart-contracts' );
    }

    $atts["rest_url"] = get_rest_url(null, 'wpsc/v1/');
    
    $atts["banners-path"] = plugin_dir_url( dirname(__FILE__) ) . 'assets/banners/';
    switch(rand(1,3)) {
        case 1:
            $atts["html-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
            $atts["html"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/medium-300x250.png" style="max-width:100%"></a>';
            break;
        case 2:
            $atts["html-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
            $atts["html"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/medium-300x250.png" style="max-width:100%"></a>';
            break;
        default:
            $atts["html-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
            $atts["html"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/medium-300x250.png" style="max-width:100%"></a>';
            break;
    }

    if (!$atts["link-launcher"]) {
        $atts["warning-launcher"] = true;
    }

    $options = get_option( 'etherscan_api_key_option' );
    if ($atts["link-launcher"] && !isset($options["wpsc_activate_launcher"])) {
        $atts["warning-remove-pages"] = true;
    }

    if (
        $atts["link-launcher"] && isset($options["wpsc_activate_launcher"]) &&
        (
            !$atts["affp_wallet_1"] or
            !$atts["affp_wallet_42161"] or
            !$atts["affp_wallet_56"] or
            !$atts["affp_wallet_137"] or
            !$atts["affp_wallet_43114"] or
            !$atts["affp_wallet_250"]
        )
    ) {
        $atts["no-wallets"] = true;
    }

    echo $m->render(WPSC_Mustache::getTemplate('wpsc-dashboard-home'), $atts);

}

function wpsc_dashboard_affp() {

    $m = new Mustache_Engine;
    $atts["logo"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/wpsmartcontracts.png';
    $atts["40"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/40.png';
    $atts["settings-page"] = admin_url('admin.php?page=wpsc-dashboard-affp');
    $atts["link-launcher"] = WPSC_assets::getPage('wpsc_is_launcher');

    $atts["affp_wallet_1"] = get_option("wpsc_affp_wallet_1");
    $atts["affp_wallet_42161"] = get_option("wpsc_affp_wallet_42161");
    $atts["affp_wallet_56"] = get_option("wpsc_affp_wallet_56");
    $atts["affp_wallet_137"] = get_option("wpsc_affp_wallet_137");
    $atts["affp_wallet_43114"] = get_option("wpsc_affp_wallet_43114");
    $atts["affp_wallet_250"] = get_option("wpsc_affp_wallet_250");

    $logo_data = WPSC_helpers::getLogoAffP();
    $atts["wps_logo_id"] = $logo_data["id"];
    $atts["the-logo"] = $logo_data["logo"];
    
    $atts["banners-path"] = plugin_dir_url( dirname(__FILE__) ) . 'assets/banners/';

    $atts["set1"] = $atts["banners-path"].'set-01/medium-300x250.png';

    $atts["html1-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/large-leaderboard-970x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html1"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/large-leaderboard-970x90.png" style="max-width:100%"></a>';
    
    $atts["html2-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/leaderboard-728x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html2"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/leaderboard-728x90.png" style="max-width:100%"></a>';
    
    $atts["html3-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/half-page-300x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html3"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/half-page-300x600.png" style="max-width:100%"></a>';
    
    $atts["html4-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/wide-sky-scraper-160x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html4"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/wide-sky-scraper-160x600.png" style="max-width:100%"></a>';
    
    $atts["html5-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html5"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/medium-300x250.png" style="max-width:100%"></a>';
    
    $atts["set2"] = $atts["banners-path"].'set-02/medium-300x250.png';

    $atts["html1b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/large-leaderboard-970x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html1b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/large-leaderboard-970x90.png" style="max-width:100%"></a>';

    $atts["html2b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/leaderboard-728x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html2b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/leaderboard-728x90.png" style="max-width:100%"></a>';

    $atts["html3b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/half-page-300x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html3b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/half-page-300x600.png" style="max-width:100%"></a>';

    $atts["html4b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/wide-sky-scraper-160x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html4b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/wide-sky-scraper-160x600.png" style="max-width:100%"></a>';

    $atts["html5b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html5b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/medium-300x250.png" style="max-width:100%"></a>';

    $atts["set3"] = $atts["banners-path"].'set-03/medium-300x250.png';

    $atts["html1c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/large-leaderboard-970x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html1c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/large-leaderboard-970x90.png" style="max-width:100%"></a>';

    $atts["html2c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/leaderboard-728x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html2c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/leaderboard-728x90.png" style="max-width:100%"></a>';

    $atts["html3c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/half-page-300x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html3c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/half-page-300x600.png" style="max-width:100%"></a>';

    $atts["html4c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/wide-sky-scraper-160x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html4c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/wide-sky-scraper-160x600.png" style="max-width:100%"></a>';

    $atts["html5c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html5c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/medium-300x250.png" style="max-width:100%"></a>';

    $atts["logo-ethereum"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/ethereum-network.png";
    $atts["logo-arbitrum"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/arbitrum-network.png";
    $atts["logo-bsc"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/bsc-network.png";
    $atts["logo-matic"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/matic-network.png";
    $atts["logo-avax"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/avax-network.png";
    $atts["logo-fantom"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/fantom-network.png";

    $atts["dashboard_link"] = admin_url('admin.php?page=wpsc_dashboard');

    echo $m->render(WPSC_Mustache::getTemplate('wpsc-dashboard-affp'), $atts);

}
