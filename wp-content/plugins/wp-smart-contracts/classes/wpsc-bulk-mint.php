<?php

if( ! defined( 'ABSPATH' ) ) die;

add_action('admin_menu', 'wpsc_register_mint_page');
  
/**
 * Add Bulk Mint Function
 */
function wpsc_register_mint_page() {
    add_submenu_page(
        'edit.php?post_type=nft',
        __( 'Batch Mint NFTs', 'wp-smart-contracts' ),
        __( 'Batch Mint NFTs', 'wp-smart-contracts' ),
        'publish_posts',
        'nft-batch-mint',
        'wpsc_nft_batch_mint'
    );
}

function wpsc_get_collection_id_data($collection_id) {

    if ($collection_id) {

        $atts = [];
        $atts["collection_id"] = $collection_id;
        $atts["wpsc_collection_contract"] = get_post_meta($collection_id, 'wpsc_contract_address', true);
        $atts["wpsc_network"] = get_post_meta($collection_id, 'wpsc_network', true);
        $atts['wpsc-short-contract'] = substr($atts["wpsc_collection_contract"], 0, 8) . '...' . substr($atts["wpsc_collection_contract"], -6);
        $atts["wpsc_flavor"] = get_post_meta($collection_id, 'wpsc_flavor', true);
        $atts["wpsc_flavor_color"] = WPSC_helpers::getFlavorColor($atts["wpsc_flavor"]);
        $atts["wpsc_blockie"] = get_post_meta($collection_id, 'wpsc_blockie', true);
        $atts["wpsc_owner"] = get_post_meta($collection_id, 'wpsc_owner', true);
        $atts["wpsc_blockie_owner"] = get_post_meta($collection_id, 'wpsc_blockie_owner', true);
        $atts["wpsc_symbol"] = get_post_meta($collection_id, 'wpsc_symbol', true);
        $atts["wpsc_name"] = get_post_meta($collection_id, 'wpsc_name', true);
        $atts["wpsc_network"] = get_post_meta($collection_id, 'wpsc_network', true);
        list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($atts["wpsc_network"]);
        $atts["wpsc_network_etherscan"] = $etherscan;
        return $atts;

    } else {
        return [];
    }

}

function wpsc_nft_batch_minted($post_id) {
    if (get_post_meta($post_id, 'wpsc_nft_id', true)) return true;
    return false;
}

function wpsc_nft_batch_mint() {
    
    $max_batch_n = 125;
    
    $m = new Mustache_Engine;
    $the_index_of_data = 0;

    $step = 1;
    if (WPSC_helpers::valArrElement($_GET, 'step')) {
        $step = (int) $_GET["step"];
    }
    if (WPSC_helpers::valArrElement($_POST, 'step')) {
        $step = (int) $_POST["step"];
    }    

    $atts['text'] = __('To deploy your Smart Contracts you need to be connected to a Network. Please install and connect to Metamask.', 'wp-smart-contracts');
    $atts['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
    $atts['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');

    if ($step == 1) {

        $atts["unique-id"] = uniqid("wpsc_" . rand(1000000, 9999999) . "_", true);

        $atts["collections"] = WPSC_Queries::nftERC1155Collections();
        $atts["create_erc1155"] = WPSC_assets::getPage('wpsc_is_launcher');
        $atts["action"] = admin_url("admin.php?page=nft-batch-mint");
        $atts["step1"] = true;

        $atts["users"] = get_users();
        $atts["nonce"] = wp_create_nonce( 'wp_rest' );

        echo $m->render(WPSC_Mustache::getTemplate('wpsc-bulk-mint'), $atts);
    
    }

    if ($step == 2) {

        $unique_id = false;
        if (WPSC_helpers::valArrElement($_POST, 'unique-id')) {
            $unique_id = $_POST["unique-id"];
            $atts["unique-id"] = $unique_id;
        }

        if (WPSC_helpers::valArrElement($_POST, 'wpsc-choose-collection-value')) {

            $collection_id = (int) $_POST['wpsc-choose-collection-value'];
            $the_collection = WPSC_Queries::nftERC1155Collections($collection_id);

            if (is_array($the_collection) and array_key_exists(0, $the_collection)) {
                
                $atts = $the_collection[0];

                $atts = array_merge($atts, wpsc_get_collection_id_data($collection_id));

                // Is a reload process?
                $log = '';
                if (WPSC_helpers::valArrElement($_POST, 'reload')) {

                    $atts["step2"] = true;

                    $atts["data"] = get_transient($unique_id . "_data");

                    $ipfs = false;
                    if (WPSC_helpers::valArrElement($_POST, "wpsc-ipfs") and $_POST["wpsc-ipfs"]=="ipfs") $ipfs = $atts["ipfs"] = true;

                    $wpsc_owner_address='';
                    if (WPSC_helpers::valArrElement($_POST, "wpsc-owner-address")) $wpsc_owner_address = $atts["wpsc_owner_address"] = sanitize_text_field($_POST["wpsc-owner-address"]);

                    $wpsc_owner_user='';
                    if (WPSC_helpers::valArrElement($_POST, "wpsc-owner-user")) $wpsc_owner_user = $atts["wpsc_owner_user"] = sanitize_text_field($_POST["wpsc-owner-user"]);

                    $n = $_POST["n"];
                    $reload_number = $_POST["reload"];
                    
                } elseif(isset($_FILES['wpsc-csv'])) {

                    $reload_number = 1;

                    $atts["step2"] = true;

                    global $wp_filesystem;
                    WP_Filesystem();
            
                    $name_file = $_FILES['wpsc-csv']['name'];
                    $tmp_name = $_FILES['wpsc-csv']['tmp_name'];
                    $allow_extensions = ['csv'];
            
                    // File type validation
                    $path_parts = pathinfo($name_file);
                    $ext = $path_parts['extension'];
            
                    if ( ! in_array($ext, $allow_extensions) ) {
                        $atts["errors"][] = "Error - File type not allowed";
                        $atts["there-are-errors"] = true;
                    } else {
        
                        $errors = [];
                        $data = [];
                        if($_FILES["wpsc-csv"]["size"] > 0) {
                          $handle = fopen($tmp_name, "r") or die("Error opening file");
                          $i=0;
                          while(($line = fgetcsv($handle, null, $_POST["csv-field"])) !== false) {
                            $i++;
                            if (!WPSC_helpers::valArrElement($line, 1)) {
                                $errors[] = "Qty not found on line $i, assuming 1";
                                $atts["there-are-errors"] = true;
                                $line[1] = 1;
                            } else {
                                $line[1] = (int) $line[1];
                                if (!$line[1]) {
                                    $errors[] = "Qty not found on line $i, assuming 1";
                                    $atts["there-are-errors"] = true;
                                    $line[1] = 1;
                                }
                            }
                            if (!WPSC_helpers::valArrElement($line, 2)) {
                                $errors[] = "Title not found on line $i, assuming #{id}";
                                $atts["there-are-errors"] = true;
                                $line[2] = "#{id}";
                            }
                            if (WPSC_helpers::valArrElement($line, 4)) $line[4] = array_map('trim', explode($_POST["csv-tax"], $line[4]));
                            if (WPSC_helpers::valArrElement($line, 5)) $line[5] = array_map('trim', explode($_POST["csv-tax"], $line[5]));
                            if (WPSC_helpers::valArrElement($line, 6)) $line[6] = array_map('trim', explode($_POST["csv-tax"], $line[6]));
                            $data[] = $line;
                          }
                          fclose($handle);
        
                          $atts["data"] = $data;
                          $atts["errors"] = $errors;
        
                          // save data for future use
                          set_transient($unique_id . "_data", $data, 24*60*60);

                          $n = sizeof($atts["data"]);

                        }
        
                    }

                    $ipfs = false;
                    if (WPSC_helpers::valArrElement($_POST, "wpsc-ipfs") and $_POST["wpsc-ipfs"]=="ipfs") $ipfs = $atts["ipfs"] = true;

                    $wpsc_owner_address = '';
                    if (WPSC_helpers::valArrElement($_POST, "wpsc-owner-address")) $wpsc_owner_address = $atts["wpsc_owner_address"] = sanitize_text_field($_POST["wpsc-owner-address"]);

                    $wpsc_owner_user = '';
                    if (WPSC_helpers::valArrElement($_POST, "wpsc-owner-user")) $wpsc_owner_user = $atts["wpsc_owner_user"] = sanitize_text_field($_POST["wpsc-owner-user"]);

                    if (WPSC_helpers::valArrElement($_POST, "the_index_of_data") and $_POST["the_index_of_data"]) $the_index_of_data = $_POST["the_index_of_data"];
                    
                } else {
                    $atts["errors"][] = "CSV file not found";
                    $atts["there-are-errors"] = true;
                }
        
            } else {
                $atts["step1"] = true;
            }

        } else {
            $atts["step1"] = true;
        }
    
        $atts['text'] = __('To deploy your Smart Contracts you need to be connected to a Network. Please install and connect to Metamask.', 'wp-smart-contracts');
        $atts['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
        $atts['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');
    
        echo $m->render(WPSC_Mustache::getTemplate('wpsc-bulk-mint'), $atts);

        if ($atts["step2"]) {

            if ($n>0) {

                $chunks = 100 / $n;

                ?>

                <script>

                    function minTwoDigits(n) {
                        return (n < 10 ? '0' : '') + n;
                    }

                    <?php

                    $rest_url = get_rest_url(0, 'wpsc/v1/save-media/');
                    $nonce = wp_create_nonce( 'wp_rest' );

                    echo 'const n = ' . $n . ";";
                    echo 'const chunk = 100 / n;';

                    @$process_index = $_POST["process_index"];
                    if (!$process_index) $process_index = 0;

                    $progress = $process_index * $chunks;

                    $how_many_reloads = ceil($n / $max_batch_n);

                    if ($reload_number > $how_many_reloads) {
                        $reload_number = $how_many_reloads;
                    }

                    echo 'jQuery("#wpsc-step-info").html(" (Batch '.$reload_number.' of '.$how_many_reloads.')");';

                    $reload_form = false;

                    if ($process_index + $max_batch_n > sizeof($atts["data"])) {
                        $max_index = sizeof($atts["data"]);
                    } else {
                        $max_index = $process_index + $max_batch_n;
                        $reload_form = true;
                    }

                    echo 'var the_index_of_data = '.$process_index.';';

                    $the_data = "[";
                    for($j=$process_index; $j<$max_index; $j++) {
                        $the_data .= '"' . $atts["data"][$j][0] . '", ';
                        $process_index++;
                    }
                    $the_data = trim($the_data, ", ") . "]";

                    echo 'var progress = '.$progress.';';

                    echo "const array_data = " . $the_data . ";";

                    ?>

                    for (let index = 0; index < array_data.length; index++) {

                        jQuery.ajax( {
                            url: '<?=$rest_url?>',
                            method: 'POST',
                            beforeSend: function ( xhr ) {
                                xhr.setRequestHeader( 'X-WP-Nonce', '<?=$nonce?>' );
                            },
                            data: {
                                index: the_index_of_data,
                                media: array_data[index],
                                log_id: '<?=$unique_id?>'
                                <?php if ($ipfs) echo ', ipfs: true'; ?>
                            }
                        } ).done( function ( response ) {
                            jQuery("#wpsc-log-div").html(
                                jQuery("#wpsc-log-div").html() + 
                                response[0].message + "<br/>"
                            );
                            var objDiv = document.getElementById("wpsc-log-div");
                            objDiv.scrollTop = objDiv.scrollHeight;
                            progress = progress + chunk;
                            const percent = parseInt(progress);
                            jQuery('#wpsc-progress').progress({percent: percent});
                        } ).fail( function ( xhr, status, error ) {
                            console.log("ERROR!", error);
                            const row = <?=$process_index?> + index + 1;
                        } );

                        the_index_of_data++;

                    }

                    <?php
                    $the_index_of_data += $max_index;
                    
                    if ($reload_form) {
                        $form = '<form id="wpsc_form" action="' . admin_url("admin.php?page=nft-batch-mint") . '" method="post">';
                        $form .= '<input type="hidden" name="wpsc-choose-collection-value" value="' . $collection_id . '">';
                        $form .= '<input type="hidden" name="step" value="2">';
                        $form .= '<input type="hidden" name="wpsc-owner-address" value="'.$wpsc_owner_address.'">';
                        $form .= '<input type="hidden" name="wpsc-owner-user" value="'.$wpsc_owner_user.'">';
                        $form .= '<input type="hidden" name="unique-id" value="'.$unique_id.'">';
                        $form .= '<input type="hidden" name="n" value="'.$n.'">';
                        $form .= '<input type="hidden" name="the_index_of_data" value="'.$the_index_of_data.'">';
                        $reload_number++;
                        $form .= '<input type="hidden" name="reload" value="'.$reload_number.'">';
                        $form .= '<input type="hidden" name="progress" value="'.$progress.'">';
                        $form .= '<input type="hidden" name="process_index" value="'.$process_index.'">';
                        if (WPSC_helpers::valArrElement($atts, 'ipfs')) {
                            $form .= '<input type="hidden" name="wpsc-ipfs" value="ipfs">';
                        }
                        $form .= '<textarea id="wpsc-log-textarea" name="log" style="display:none"></textarea>';
                        $form .= '</form>';
                        echo "</script>" . $form . "<script>";
                    }
                    ?>

                    jQuery(document).ajaxStop(function () {
                        <?php
                        if ($reload_form and $reload_number <= $how_many_reloads) {
                        ?>
                            jQuery("#wpsc-log-textarea").val(
                                jQuery("#wpsc-log-div").html()
                            );
                            jQuery("#wpsc_form").submit();
                        <?php
                        } else {

                            $rest_url = get_rest_url(0, 'wpsc/v1/retrieve-media-log/');
                            $nonce = wp_create_nonce( 'wp_rest' );
        
                        ?>
                            jQuery("#wpsc-wait-process").hide();
                            jQuery("#wpsc-wait-finish").fadeIn(500);
                            jQuery('#wpsc-progress').progress(
                                {
                                    percent: 100,
                                    text: {
                                        success : 'Media processed!'
                                    }
                                }
                            );
                            jQuery("#wpsc-progress").removeClass("active").addClass("success");

                            jQuery("#wpsc-log-wizard").load("<?=admin_url("admin.php?page=nft-batch-mint&step=log&uid=".$unique_id)?>");

                            jQuery("#wpsc-ajax-stop").html(
                                '<p><a href="<?php
                                    echo admin_url("admin.php?page=nft-batch-mint&step=3&uid=".$unique_id."&collection_id=".$collection_id."&wpsc-owner-address=".$wpsc_owner_address."&wpsc-owner-user=".$wpsc_owner_user);
                                ?>" class="ui primary button">Continue</a></p>'
                            ).fadeIn(500);
                        <?php
                        }
                        ?>
                    });

                </script>
                
                <?php

            }

        }

    }

    if ($step==3) {
        
        if (WPSC_helpers::valArrElement($_GET, 'uid')) {
            $unique_id = sanitize_text_field($_GET["uid"]);
        } elseif (WPSC_helpers::valArrElement($_POST, 'unique-id')) {
            $unique_id = $_POST["unique-id"];
            $atts["unique-id"] = $unique_id;
        }
        $collection_id = false;
        if (WPSC_helpers::valArrElement($_GET, 'collection_id')) {
            $collection_id = sanitize_text_field($_GET["collection_id"]);
        } elseif (WPSC_helpers::valArrElement($_POST, 'wpsc-choose-collection-value')) {
            $collection_id = (int) $_POST['wpsc-choose-collection-value'];
        }

        $wpsc_owner_address = '';
        if (WPSC_helpers::valArrElement($_GET, "wpsc-owner-address")) $wpsc_owner_address = $atts["wpsc_owner_address"] = sanitize_text_field($_GET["wpsc-owner-address"]);
        if (empty($wpsc_owner_address)) $wpsc_owner_address = $atts["wpsc_owner_address"] = sanitize_text_field($_POST["wpsc-owner-address"]);

        $wpsc_owner_user = '';
        if (WPSC_helpers::valArrElement($_GET, "wpsc-owner-user")) $wpsc_owner_user = $atts["wpsc_owner_user"] = sanitize_text_field($_GET["wpsc-owner-user"]);
        if (empty($wpsc_owner_user)) $wpsc_owner_user = $atts["wpsc_owner_user"] = sanitize_text_field($_POST["wpsc-owner-user"]);

        if ($unique_id and $collection_id) {

            $the_collection = WPSC_Queries::nftERC1155Collections($collection_id);

            if (is_array($the_collection) and array_key_exists(0, $the_collection)) {
                
                $atts = array_merge($atts, $the_collection[0]);

                if (!WPSC_NFTGallery::db_check()) {	
                    $atts["wpsc-galleries-error-message"] = "The table: " . WPSC_NFTGallery::db_table_name() . " could not be found on the database. Please check that the database user has \"CREATE TABLE\" permission.";	
                    $atts["wpsc-galleries-error"] = true;	
                }

                echo $m->render(
                    WPSC_Mustache::getTemplate('wpsc-bulk-mint'), 
                    array_merge(
                        $atts,
                        ["step3"=>true], 
                        wpsc_get_collection_id_data($collection_id)
                    )
                );

                $atts["data"] = get_transient($unique_id . "_data");

                $n = 0;
                if (is_array($atts["data"])) {
                    $n = sizeof($atts["data"]);
                }
                if (WPSC_helpers::valArrElement($_POST, 'reload')) {
                    $reload_number = $_POST["reload"];
                    if (!$reload_number) {
                        $reload_number = 1;
                    }
                } else {
                    $reload_number = 1;
                }

                if ($n>0) {

                    $chunks = 100 / $n;
    
                    ?>
    
                    <script>
    
                        <?php
    
                        $rest_url = get_rest_url(0, 'wpsc/v1/insert-nft/' . $collection_id);
                        $nonce = wp_create_nonce( 'wp_rest' );
    
                        echo 'const n = ' . $n . ";";
                        echo 'const chunk = 100 / n;';
    
                        @$process_index = $_POST["process_index"];
                        if (!$process_index) $process_index = 0;
    
                        $progress = $process_index * $chunks;
    
                        $how_many_reloads = ceil($n / $max_batch_n);
    
                        if ($reload_number > $how_many_reloads) {
                            $reload_number = $how_many_reloads;
                        }
    
                        echo 'jQuery("#wpsc-step-info").html(" (Batch '.$reload_number.' of '.$how_many_reloads.')");';
    
                        $reload_form = false;
    
                        if ($process_index + $max_batch_n > sizeof($atts["data"])) {
                            $max_index = sizeof($atts["data"]);
                        } else {
                            $max_index = $process_index + $max_batch_n;
                            $reload_form = true;
                        }

                        echo 'var the_index_of_data = '.$process_index.';';

                        $the_data = null;
                        for($j=$process_index; $j<$max_index; $j++) {
                            $the_data[] = $atts["data"][$j];
                            $process_index++;
                        }
    
                        echo 'var progress = '.$progress.';' . PHP_EOL;
    
                        echo "const array_data = " . json_encode($the_data) . ";" . PHP_EOL;

                        ?>
    
                        for (let index = 0; index < array_data.length; index++) {
    
                            if (typeof(array_data[index].media_mime)!=="undefined") {

                                var media_type = '';
                                if (array_data[index].media_mime.indexOf("application") !== -1) media_type = "document";
                                if (array_data[index].media_mime.indexOf("model/gltf-binary") !== -1) media_type = "3dmodel";
                                if (array_data[index].media_mime.indexOf("image") !== -1) media_type = "image";
                                if (array_data[index].media_mime.indexOf("video") !== -1) media_type = "video";
                                if (array_data[index].media_mime.indexOf("audio") !== -1) media_type = "audio";

                                jQuery.ajax( {
                                    url: '<?=$rest_url?>',
                                    method: 'POST',
                                    beforeSend: function ( xhr ) {
                                        xhr.setRequestHeader( 'X-WP-Nonce', '<?=$nonce?>' );
                                    },
                                    dataType: "html",
                                    data: {
                                        'log_index'     : the_index_of_data,
                                        'log_id'        : '<?=$unique_id?>',
                                        'nft_id'        : 0,
                                        'collection_id' : <?=$collection_id?>,
                                        'title'         : array_data[index][2],
                                        'supply'        : array_data[index][1],
                                        'custom_atts'   : array_data[index][5],
                                        'custom_tax'    : array_data[index][4],
                                        'custom_gals'   : array_data[index][6],
                                        'skip_owner'    : true,
                                        'description'   : array_data[index][3],
                                        'media'         : '[{"id": ' + array_data[index].media_id + ', "url": "' + array_data[index].media_url + '", "mime": "' + array_data[index].media_mime + '"}]',
                                        'media_type'    : media_type,
                                        'overwrite'     : true,
                                        'author'        : <?=$wpsc_owner_user?>
                                    }
                                } ).done( function ( response ) {
                                    progress = progress + chunk;
                                    const percent = parseInt(progress);
                                    jQuery('#wpsc-progress').progress({percent: percent});

                                    console.log("response", response);

                                    if (isNaN(response)) {
                                        jQuery("#wpsc-wait-finish-errors").fadeIn();
                                        jQuery("#wpsc-wait-finish-errors").html(
                                            jQuery("#wpsc-wait-finish-errors").html() + 
                                            response + "<br>"
                                        );
                                    }

                                } ).fail( function ( xhr, status, error ) {
                                    
                                    jQuery("#wpsc-wait-finish-errors").fadeIn();
                                    jQuery("#wpsc-wait-finish-errors").html(
                                        jQuery("#wpsc-wait-finish-errors").html() + 
                                        error + "<br>"
                                    );

                                    const row = <?=$process_index?> + index + 1;
                                } );

                            }

                            the_index_of_data++;
    
                        }
    
                        <?php
                        $the_index_of_data += $max_index;
                        
                        if ($reload_form) {
                            $form = '<form id="wpsc_form" action="' . admin_url("admin.php?page=nft-batch-mint") . '" method="post">';
                            $form .= '<input type="hidden" name="wpsc-choose-collection-value" value="' . $collection_id . '">';
                            $form .= '<input type="hidden" name="step" value="3">';
                            $form .= '<input type="hidden" name="wpsc-owner-address" value="'.$wpsc_owner_address.'">';
                            $form .= '<input type="hidden" name="wpsc-owner-user" value="'.$wpsc_owner_user.'">';
                            $form .= '<input type="hidden" name="unique-id" value="'.$unique_id.'">';
                            $form .= '<input type="hidden" name="n" value="'.$n.'">';
                            $form .= '<input type="hidden" name="the_index_of_data" value="'.$the_index_of_data.'">';
                            $reload_number++;
                            $form .= '<input type="hidden" name="reload" value="'.$reload_number.'">';
                            $form .= '<input type="hidden" name="progress" value="'.$progress.'">';
                            $form .= '<input type="hidden" name="process_index" value="'.$process_index.'">';
                            if (WPSC_helpers::valArrElement($atts, 'ipfs')) {
                                $form .= '<input type="hidden" name="wpsc-ipfs" value="ipfs">';
                            }
                            $form .= '<textarea id="wpsc-log-textarea" name="log" style="display:none"></textarea>';
                            $form .= '</form>';
                            echo "</script>" . $form . "<script>";
                        }
                        ?>
    
                        jQuery(document).ajaxStop(function () {
                            <?php
                            if ($reload_form and $reload_number <= $how_many_reloads) {
                            ?>
                                jQuery("#wpsc-log-textarea").val(
                                    jQuery("#wpsc-log-div").html()
                                );
                                jQuery("#wpsc_form").submit();
                            <?php
                            } else {
    
                                $rest_url = get_rest_url(0, 'wpsc/v1/retrieve-media-log/');
                                $nonce = wp_create_nonce( 'wp_rest' );
            
                            ?>
                                jQuery("#wpsc-wait-process").hide();
                                jQuery("#wpsc-wait-finish").fadeIn(500);
                                jQuery('#wpsc-progress').progress(
                                    {
                                        percent: 100,
                                        text: {
                                            success : 'NFT Items processed!'
                                        }
                                    }
                                );
                                jQuery("#wpsc-progress").removeClass("active").addClass("success");
                                jQuery("#wpsc-log-wizard").load("<?=admin_url("admin.php?page=nft-batch-mint&step=log&uid=".$unique_id."&show_post_id=true")?>");
                                jQuery("#wpsc-ajax-stop").html(
                                    '<p><a href="<?php
                                        echo admin_url("admin.php?page=nft-batch-mint&step=4&uid=".$unique_id."&collection_id=".$collection_id."&wpsc-owner-address=".$wpsc_owner_address."&wpsc-owner-user=".$wpsc_owner_user);
                                    ?>" class="ui primary button">Continue</a></p>'
                                ).fadeIn(500);
                            <?php
                            }
                            ?>
                        });
    
                    </script>
                    
                    <?php
    
                }
    
            } else {
                echo $m->render(WPSC_Mustache::getTemplate('wpsc-bulk-mint'), ["step3"=>true, "there-are-errors"=>true, "errors" => ["There was an error processing the data"]]);
            }
        } else {
            echo $m->render(WPSC_Mustache::getTemplate('wpsc-bulk-mint'), ["step3"=>true, "there-are-errors"=>true, "errors" => ["There was an error processing the data"]]);
        }
    }

    if ($step==4) {
        
        if (WPSC_helpers::valArrElement($_GET, 'uid')) {
            $unique_id = sanitize_text_field($_GET["uid"]);
        } elseif (WPSC_helpers::valArrElement($_POST, 'unique-id')) {
            $unique_id = $_POST["unique-id"];
            $atts["unique-id"] = $unique_id;
        }
        $collection_id = false;
        if (WPSC_helpers::valArrElement($_GET, 'collection_id')) {
            $collection_id = sanitize_text_field($_GET["collection_id"]);
        } elseif (WPSC_helpers::valArrElement($_POST, 'wpsc-choose-collection-value')) {
            $collection_id = (int) $_POST['wpsc-choose-collection-value'];
        }

        $wpsc_owner_address='';
        if (WPSC_helpers::valArrElement($_GET, "wpsc-owner-address")) $wpsc_owner_address = $atts["wpsc_owner_address"] = sanitize_text_field($_GET["wpsc-owner-address"]);

        $wpsc_owner_user='';
        if (WPSC_helpers::valArrElement($_GET, "wpsc-owner-user")) $wpsc_owner_user = $atts["wpsc_owner_user"] = sanitize_text_field($_GET["wpsc-owner-user"]);

        $atts['animated'] = plugins_url( "assets/img/animated.gif", dirname(__FILE__) );

        if ($unique_id and $collection_id) {

            $the_collection = WPSC_Queries::nftERC1155Collections($collection_id);

            if (is_array($the_collection) and array_key_exists(0, $the_collection)) {
                
                $atts = array_merge($atts, $the_collection[0]);

                $data = get_transient($unique_id . "_data");

                $ids = [];
                $qty = [];

                $batch = 1;

                if (is_countable($data)) {
                    
                    $batch_number = ceil(sizeof($data) / $max_batch_n);
                    $chunk = (int) (100 / $batch_number);

                    $percent = 0;
                    $first = true;
                    $button_index = 0;
                    $i = 0;
                    $there_is_something_to_mint = false;
                    foreach($data as $loop_i => $row) {

                        // check already minted items
                        if (WPSC_helpers::valArrElement($row, 'post_id') and is_numeric($row["post_id"]) and !wpsc_nft_batch_minted($row["post_id"])) {

                            $ids[] = $row["post_id"];
                            $qty[] = $row[1];
        
                            if (($i + 1) % $max_batch_n === 0) {
                                $button_index++;
                                $percent += $chunk;
                                if ($loop_i + 1 == sizeof($data)) {
                                    $percent = 100;
                                }
                                $record = [
                                    "title"   => "Mint Batch $batch / $batch_number</h3>",
                                    "ids"     => json_encode($ids),
                                    "qty"     => json_encode($qty),
                                    "percent" => $percent,
                                    "index"   => $button_index
                                ];
                                if ($first) $record["first"] = true;
                                $first = false;
                                if ($loop_i + 1 < sizeof($data)) {
                                    $record["next"] = $button_index + 1;
                                }
                                $atts["buttons"][] = $record;
                                $ids = [];
                                $qty = [];
                                $batch++;
                            }

                            $i++; // real index of the batch, skipping the already minted items

                            $there_is_something_to_mint = true;
        
                        }

                    }

                } else {
                    $there_is_something_to_mint = false;
                }

                if (sizeof($ids)) {
                    $button_index++;
                    $percent = 100;
                    $record = [
                        "title"   => "Mint Batch $batch / $batch_number</h3>",
                        "ids"     => json_encode($ids),
                        "qty"     => json_encode($qty),
                        "percent" => $percent,
                        "index"   => $button_index
                    ];
                    if ($first) $record["first"] = true;
                    $atts["buttons"][] = $record;
                }

                $atts["rest_url"] = get_rest_url(0, 'wpsc/v1/update-nft-1155/');
                $atts["nonce"] = wp_create_nonce( 'wp_rest' );
                $atts["wpsc_owner_user"] = $wpsc_owner_user;
                $atts["collection-url"] = get_permalink($collection_id);

                if (!$there_is_something_to_mint) {
                    $atts["show-collection-url"] = true;
                }

                $atts = array_merge($atts, wpsc_get_collection_id_data($collection_id));

                if ($atts["wpsc_flavor"] == "ikasumi" or $atts["wpsc_flavor"] == "azuki") {
                    $atts["show-lazy-info"] = true;
                }

                echo $m->render(
                    WPSC_Mustache::getTemplate('wpsc-bulk-mint'), 
                    array_merge(
                        $atts,
                        ["step4"=>true],
                        wpsc_get_collection_id_data($collection_id)
                    )
                );

            } else {
                echo $m->render(WPSC_Mustache::getTemplate('wpsc-bulk-mint'), ["step3"=>true, "there-are-errors"=>true, "errors" => ["There was an error processing the data: $collection_id not found"]]);
            }
        } else {
            echo $m->render(WPSC_Mustache::getTemplate('wpsc-bulk-mint'), ["step3"=>true, "there-are-errors"=>true, "errors" => ["There was an error processing the data. Unique ID: $unique_id or Collection ID: $collection_id not found"]]);
        }
    }

    if (WPSC_helpers::valArrElement($_GET, 'step') and $_GET["step"] == "log") {
        $uid = $_GET["uid"];
        if (WPSC_helpers::valArrElement($_GET, 'show_post_id')) {
            $atts["show_post_id"] = true;
            $atts["log_content"] = get_transient($uid."_data");
            $atts["edit_url"] = admin_url("post.php?action=edit&post=");
        } else {
            $atts = ["log_content"=>get_transient($uid)];
        }
            
        echo $m->render(WPSC_Mustache::getTemplate('wpsc-bulk-mint'), $atts);
    }
}
