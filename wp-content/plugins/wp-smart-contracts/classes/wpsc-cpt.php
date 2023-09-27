<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Coins CPT
 */
require_once("wpsc-cpt-coin.php");

/**
 * Staking
 */
require_once("wpsc-cpt-staking.php");

/**
 * Crowdfunding CPT
 */
// deprecated
require_once("wpsc-cpt-crowdfunding.php");

/**
 * NFT CPT
 */
require_once("wpsc-cpt-nft-collection.php");
require_once("wpsc-cpt-nft.php");
require_once("wpsc-cpt-nft-galleries.php");

