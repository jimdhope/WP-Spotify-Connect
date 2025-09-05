<?php
/**
 * Plugin Name: Spotify Connect Player
 * Description: Adds a Spotify Connect player to your WordPress site.
 * Version: 2.0
 * Author: Jabi Hope
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'SPOTIFY_CONNECT_PLAYER_VERSION' ) ) {
    define( 'SPOTIFY_CONNECT_PLAYER_VERSION', '2.0' );
}
if ( ! defined( 'SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR' ) ) {
    define( 'SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'SPOTIFY_CONNECT_PLAYER_PLUGIN_URL' ) ) {
    define( 'SPOTIFY_CONNECT_PLAYER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

require_once SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'includes/class-spotify-connect-admin.php';
require_once SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'includes/class-spotify-connect-oauth.php';
require_once SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'includes/class-spotify-connect-block.php';

new Spotify_Connect_Admin();
new Spotify_Connect_OAuth();
new Spotify_Connect_Block();
