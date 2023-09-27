<?php
/**
 * Burst Statistics endpoint for collecting hits
 */
define( 'SHORTINIT', true );
require_once __DIR__ . '/wp-load.php';
require_once trailingslashit(WP_CONTENT_DIR) . 'plugins/burst-statistics/helpers/php-user-agent/UserAgentParser.php';
require_once trailingslashit(WP_CONTENT_DIR) . 'plugins/burst-statistics/tracking/tracking.php';

burst_beacon_track_hit();