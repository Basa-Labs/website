<?php

if( ! defined( 'ABSPATH' ) ) die;

/*
delete_option('wpsc_plugin_redirected');
die;
*/

add_action('admin_menu', 'wpsc_register_admin_setup');

/**
 * Add setup wizard function
 */
function wpsc_register_admin_setup() {
    add_submenu_page(
        'wpsc_dashboard',
        __( 'Admin Setup Wizard', 'wp-smart-contracts' ),
        __( 'Admin Setup Wizard', 'wp-smart-contracts' ),
        'publish_posts',
        'wpsc-admin-setup',
        'wpsc_admin_setup'
    );
}

function wpscAddSkinAttrs($atts) {

    $options = get_option( 'etherscan_api_key_option' );

    $wpsc_skin = WPSC_helpers::valArrElement($options, "wpsc-skin")?$options["wpsc-skin"]:'';
      
    switch ($wpsc_skin) {
      case 'light1':
        $wpsc_skin_selected = "wpsc_skin_1";
        break;
      case 'light2':
        $wpsc_skin_selected = "wpsc_skin_2";
        break;
      case 'dark':
        $wpsc_skin_selected = "wpsc_skin_3";
        break;
      case '20':
        $wpsc_skin_selected = "wpsc_skin_4";
        break;
      case '20red':
        $wpsc_skin_selected = "wpsc_skin_5";
        break;
      case '20green':
        $wpsc_skin_selected = "wpsc_skin_6";
        break;
      case '20black':
        $wpsc_skin_selected = "wpsc_skin_7";
        break;
      case '20cream':
        $wpsc_skin_selected = "wpsc_skin_11";
        break;
      case '20pink':
        $wpsc_skin_selected = "wpsc_skin_9";
        break;
      case '20orange':
        $wpsc_skin_selected = "wpsc_skin_10";
        break;
      case '20purple':
        $wpsc_skin_selected = "wpsc_skin_8";
        break;
      case 'default':
        $wpsc_skin_selected = "wpsc_skin_0";
        break;
      default:
        $wpsc_skin_selected = "wpsc_skin_4";
        break;
    }

    $atts["default"] = plugins_url( "assets/img/default.png", dirname(__FILE__) );
    $atts["light1"] = plugins_url( "assets/img/light1.png", dirname(__FILE__) );
    $atts["light2"] = plugins_url( "assets/img/light2.png", dirname(__FILE__) );
    $atts["cream"] = plugins_url( "assets/img/cream.png", dirname(__FILE__) );
    $atts["dark"] = plugins_url( "assets/img/dark.png", dirname(__FILE__) );
    $atts["red"] = plugins_url( "assets/img/red.png", dirname(__FILE__) );
    $atts["purple"] = plugins_url( "assets/img/purple.png", dirname(__FILE__) );
    $atts["pink"] = plugins_url( "assets/img/pink.png", dirname(__FILE__) );
    $atts["orange"] = plugins_url( "assets/img/orange.png", dirname(__FILE__) );
    $atts["blue"] = plugins_url( "assets/img/blue.png", dirname(__FILE__) );
    $atts["green"] = plugins_url( "assets/img/green.png", dirname(__FILE__) );
    $atts["dark2"] = plugins_url( "assets/img/dark2.png", dirname(__FILE__) );

    $atts[$wpsc_skin_selected] = true;

    $atts["wpsc-skin"] = $wpsc_skin;

    return $atts;

}

function wpsc_admin_setup() {
    
    $m = new Mustache_Engine;
    $step = 1;
    if (WPSC_helpers::valArrElement($_POST, 'step')) {
        $step = (int) $_POST["step"];
    }    

    if (isset($_GET["fs"]) or isset($_POST["fs"])) {
        $atts["full_screen"] = true;
        echo "<style>div#wpcontent {margin-left: 0;}#wpadminbar, #adminmenumain {display: none}</style>";
    }

    $atts["step".$step] = true;

    $atts["skin"] = __("Skin", "wp-smart-contracts");
    $atts["skin-desc"] = __("Appearance of your user interface", "wp-smart-contracts");
    $atts["launcher"] = __("Launcher", "wp-smart-contracts");
    $atts["launcher-desc"] = __("Launcher & Affiliate Program", "wp-smart-contracts");
    $atts["nft"] = __("NFT Settings", "wp-smart-contracts");
    $atts["nft-desc"] = __("Configure your NFT Marketplace", "wp-smart-contracts");
    $atts["coin"] = __("Are you planning to create your own cryptocurrencies?", "wp-smart-contracts");
    $atts["coin-desc"] = __("Configure your Cryptocurrencies settings", "wp-smart-contracts");
    $atts["other"] = __("Other Settings", "wp-smart-contracts");
    $atts["other-desc"] = __("APIs and general settings", "wp-smart-contracts");
    $atts["forward-url"] = get_admin_url() . "admin.php?page=wpsc_dashboard";

    if ($step == 1) {

        $atts["img"] = plugins_url( "launcher/img/welcome-01.jpg", dirname(__FILE__) );
        $atts = wpscAddSkinAttrs($atts);
        $atts["n"] = "four";
        $atts["skins"] = $m->render(WPSC_Mustache::getTemplate('settings-skin'), $atts);
        echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);

    }

    if ($step == 2) {
        
      // update skin
      $options = get_option( 'etherscan_api_key_option' );
      $option = sanitize_text_field($_POST["wpsc_skin_radio"]);
      if (!in_array($option, ['light1', 'light2', 'dark', '20', '20red', '20green', '20black', '20cream', '20pink', '20orange', '20purple', 'default'])) {
          $option = "20";
      }
      $options["wpsc-skin"] = $option;
      update_option("etherscan_api_key_option", $options);

      $options = get_option( 'etherscan_api_key_option' );
      if (isset($options["wpsc_activate_launcher"]) and $options["wpsc_activate_launcher"] == "on") {
          $atts["launcher-checked"] = true;
      }

      $logo_data = WPSC_helpers::getLogoAffP();
      $atts["wps_logo_id"] = $logo_data["id"];
      $atts["the-logo"] = $logo_data["logo"];
  
      $atts["tosaffp"] = WPSC_helpers::tosaffp();

      $atts["img"] = plugins_url( "launcher/img/welcome-02.jpg", dirname(__FILE__) );
      $atts["affp_wallet_1"] = get_option("wpsc_affp_wallet_1");
      $atts["affp_wallet_42161"] = get_option("wpsc_affp_wallet_42161");
      $atts["affp_wallet_56"] = get_option("wpsc_affp_wallet_56");
      $atts["affp_wallet_137"] = get_option("wpsc_affp_wallet_137");
      $atts["affp_wallet_43114"] = get_option("wpsc_affp_wallet_43114");
      $atts["affp_wallet_250"] = get_option("wpsc_affp_wallet_250");

      $disabled_ethereum = get_option("disabled_ethereum");
      if ($disabled_ethereum) $atts["disabled_ethereum"] = true;

      $disabled_arbitrum = get_option("disabled_arbitrum");
      if ($disabled_arbitrum) $atts["disabled_arbitrum"] = true;

      $disabled_bsc = get_option("disabled_bsc");
      if ($disabled_bsc) $atts["disabled_bsc"] = true;

      $disabled_polygon = get_option("disabled_polygon");
      if ($disabled_polygon) $atts["disabled_polygon"] = true;

      $disabled_avax = get_option("disabled_avax");
      if ($disabled_avax) $atts["disabled_avax"] = true;

      $disabled_fantom = get_option("disabled_fantom");
      if ($disabled_fantom) $atts["disabled_fantom"] = true;

      $disabled_test = get_option("disabled_test");
      if ($disabled_test) $atts["disabled_test"] = true;

      echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);

    }

    if ($step == 3) {

      $disabled_ethereum = isset($_POST["disabled-ethereum"])?true:false;
      update_option("disabled_ethereum", $disabled_ethereum);

      $disabled_arbitrum = isset($_POST["disabled-arbitrum"])?true:false;
      update_option("disabled_arbitrum", $disabled_arbitrum);

      $disabled_bsc = isset($_POST["disabled-bsc"])?true:false;
      update_option("disabled_bsc", $disabled_bsc);

      $disabled_polygon = isset($_POST["disabled-polygon"])?true:false;
      update_option("disabled_polygon", $disabled_polygon);

      $disabled_avax = isset($_POST["disabled-avax"])?true:false;
      update_option("disabled_avax", $disabled_avax);

      $disabled_fantom = isset($_POST["disabled-fantom"])?true:false;
      update_option("disabled_fantom", $disabled_fantom);

      $disabled_test = isset($_POST["disabled-test"])?true:false;
      update_option("disabled_test", $disabled_test);

      $options = get_option( 'etherscan_api_key_option' );

      if (isset($_POST["wpsc_activate_launcher"])) {
          $options["wpsc_activate_launcher"] = "on";
      } else {
          unset($options["wpsc_activate_launcher"]);
      }

      $wps_logo_id = (isset($_POST["wps-logo-id"]))?sanitize_text_field($_POST["wps-logo-id"]):"";
      if ($wps_logo_id) {
          update_option("wps_logo_id", $wps_logo_id);
      }
      if (isset($_POST["wpsc-remove-logo"])) {
          update_option("wps_logo_id", 0);
      }

      update_option("etherscan_api_key_option", $options);

      $affp_wallet_1 = sanitize_text_field($_POST["affp_wallet_1"]);
      $affp_wallet_42161 = sanitize_text_field($_POST["affp_wallet_42161"]);
      $affp_wallet_56 = sanitize_text_field($_POST["affp_wallet_56"]);
      $affp_wallet_137 = sanitize_text_field($_POST["affp_wallet_137"]);
      $affp_wallet_43114 = sanitize_text_field($_POST["affp_wallet_43114"]);
      $affp_wallet_250 = sanitize_text_field($_POST["affp_wallet_250"]);

      update_option("wpsc_affp_wallet_1", $affp_wallet_1);
      update_option("wpsc_affp_wallet_42161", $affp_wallet_42161);
      update_option("wpsc_affp_wallet_56", $affp_wallet_56);
      update_option("wpsc_affp_wallet_137", $affp_wallet_137);
      update_option("wpsc_affp_wallet_43114", $affp_wallet_43114);
      update_option("wpsc_affp_wallet_250", $affp_wallet_250);    

      $atts["img"] = plugins_url( "launcher/img/welcome-03.jpg", dirname(__FILE__) );
      if (isset($options["login_redirection"])) $atts["login_redirection"] = $options["login_redirection"];
      if (isset($options["nft_storage_key"])) $atts["nft_storage_key"] = $options["nft_storage_key"];
      if (isset($options["nft_moralis_key"])) $atts["nft_moralis_key"] = $options["nft_moralis_key"];
      if (isset($options["nft_items_per_page"])) $atts["nft_items_per_page"] = $options["nft_items_per_page"];
      if (!isset($atts["nft_items_per_page"])) $atts["nft_items_per_page"] = 12;

      $options = get_option('etherscan_api_key_option');
      $wpsc_role = (WPSC_helpers::valArrElement($options, "wpsc_role") and !empty($options["wpsc_role"]))?$options["wpsc_role"]:false;

      if (!$wpsc_role or $wpsc_role=="deactivated") {
        $roles[] = ["role"=>"deactivated", "name"=>"Deactivate Web3 User Registration and Login", "checked"=>true];
      } else {
        $roles[] = ["role"=>"deactivated", "name"=>"Deactivate Web3 User Registration and Login"];
      }

      foreach(get_editable_roles() as $role => $data) {
        $pre = "<span style=\"color: #ccc\">";
        $pos = " (Not recommended)</span>";
        if ($role == "subscriber") {
          $pre = "";
          $pos = " <span style=\"color: #ccc\">(This is the recommended setting)</span>";
        }
        if ($wpsc_role and $wpsc_role==$role) {
          $roles[] = ["role"=>$role, "name" => $pre . $data["name"] . $pos, "checked"=>true];
        } else {
          $roles[] = ["role"=>$role, "name" => $pre . $data["name"] . $pos];
        }
      }

      $atts["roles"] = $roles;

      if (WPSC_helpers::valArrElement($options, "wpsc_email_registration") and !empty($options["wpsc_email_registration"])) {
        $atts["wpsc_email_registration_checked"] = true;
      }

      if (WPSC_helpers::valArrElement($options, "wpsc_add_upload") and !empty($options["wpsc_add_upload"])) {
        $atts["wpsc_add_upload_".$options["wpsc_add_upload"]."_checked"] = true;
      } else {
        $atts["wpsc_add_upload__checked"] = true;
      }
      
      echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);

    }

    if ($step == 4) {

        $options = get_option( 'etherscan_api_key_option' );
        $options["nft_storage_key"] = sanitize_text_field($_POST["nft_storage_key"]);
        $options["login_redirection"] = sanitize_text_field($_POST["login_redirection"]);        
        $options["nft_moralis_key"] = sanitize_text_field($_POST["nft_moralis_key"]);
        $options["wpsc_role"] = sanitize_text_field($_POST["wpsc_role"]);
        $wpsc_email_registration = WPSC_helpers::valArrElement($_POST, "wpsc_email_registration")?'yes':'';
        $options["wpsc_email_registration"] = $wpsc_email_registration;
        
        $wpsc_add_upload = WPSC_helpers::valArrElement($_POST, "wpsc_add_upload")?sanitize_text_field($_POST["wpsc_add_upload"]):'';
        $options["wpsc_add_upload"] = $wpsc_add_upload;

        $options["nft_items_per_page"] = sanitize_text_field($_POST["nft_items_per_page"]);
        update_option("etherscan_api_key_option", $options);

        $options = get_option( 'etherscan_api_key_option' );

        $atts["img"] = plugins_url( "launcher/img/welcome-04.jpg", dirname(__FILE__) );

        if (isset($options["api_key"])) $atts["api_etherscan"] = $options["api_key"];
        if (isset($options["arbiscan_api_key"])) $atts["api_arbiscan"] = $options["arbiscan_api_key"];
        if (isset($options["polygonscan_api_key"])) $atts["api_polygonscan"] = $options["polygonscan_api_key"];
        if (isset($options["bscscan_api_key"])) $atts["api_bscscan"] = $options["bscscan_api_key"];
        if (isset($options["avax_api_key"])) $atts["api_snowtrace"] = $options["avax_api_key"];
        if (isset($options["fantom_api_key"])) $atts["api_fantom"] = $options["fantom_api_key"];

        echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);

    }

    if ($step == 5) {
        $options = get_option( 'etherscan_api_key_option' );
        $options["api_key"] = sanitize_text_field($_POST["api_etherscan"]);
        $options["arbiscan_api_key"] = sanitize_text_field($_POST["api_arbiscan"]);
        $options["polygonscan_api_key"] = sanitize_text_field($_POST["api_polygonscan"]);
        $options["bscscan_api_key"] = sanitize_text_field($_POST["api_bscscan"]);
        $options["avax_api_key"] = sanitize_text_field($_POST["api_snowtrace"]);
        $options["fantom_api_key"] = sanitize_text_field($_POST["api_fantom"]);
        update_option("etherscan_api_key_option", $options);
        echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);
    }

}

/**
 * Call the setup on plugin activation
 */
function wpsc_can_redirect_on_activation() {
	if ( is_network_admin() ) {
		return false;
	}
	if ( filter_input( INPUT_GET, 'activate-multi', FILTER_VALIDATE_BOOLEAN ) ) {
		return false;
	}
	return true;
}

add_action( 'admin_init', function() {
	if ( wpsc_can_redirect_on_activation() && is_admin() && !get_option('wpsc_plugin_redirected')) {
        update_option('wpsc_plugin_redirected', true);
        $redirect_url = admin_url('admin.php') . "?page=wpsc-admin-setup&fs=1";
        wp_safe_redirect($redirect_url);
        exit;
    }
});
