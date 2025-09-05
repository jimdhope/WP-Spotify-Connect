<?php

class Spotify_Connect_OAuth {

    public function __construct() {
        add_action( 'admin_post_spotify_connect_authorize', array( $this, 'authorize' ) );
        add_action( 'wp_ajax_spotify_connect_callback', array( $this, 'callback' ) );
        add_action( 'wp_ajax_spotify_connect_get_access_token', array( $this, 'get_access_token' ) );
    }

    public function authorize() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have permission to do this.' );
        }

        $client_id = get_option( 'spotify_connect_player_client_id' );
        $redirect_uri = admin_url( 'admin-ajax.php?action=spotify_connect_callback' );
        $scopes = 'user-read-playback-state user-modify-playback-state user-read-currently-playing streaming app-remote-control user-read-email user-read-private';

        $authorize_url = 'https://accounts.spotify.com/authorize?' . http_build_query( array(
            'response_type' => 'code',
            'client_id' => $client_id,
            'scope' => $scopes,
            'redirect_uri' => $redirect_uri,
        ) );

        wp_redirect( $authorize_url );
        exit;
    }

    public function callback() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have permission to do this.' );
        }

        $code = $_GET['code'];
        $client_id = get_option( 'spotify_connect_player_client_id' );
        $client_secret = get_option( 'spotify_connect_player_client_secret' );
        $redirect_uri = admin_url( 'admin-ajax.php?action=spotify_connect_callback' );

        $response = wp_remote_post( 'https://accounts.spotify.com/api/token', array(
            'method'    => 'POST',
            'headers'   => array(
                'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
            ),
            'body'      => array(
                'grant_type'   => 'authorization_code',
                'code'         => $code,
                'redirect_uri' => $redirect_uri,
            ),
        ) );

        if ( is_wp_error( $response ) ) {
            wp_die( 'Error getting access token.' );
        }

        $body = json_decode( wp_remote_retrieve_body( $response ) );

        if ( isset( $body->access_token ) ) {
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'spotify_connect_player_access_token', $body->access_token );
            update_user_meta( $user_id, 'spotify_connect_player_refresh_token', $body->refresh_token );
            update_user_meta( $user_id, 'spotify_connect_player_token_expiry', time() + $body->expires_in );

            wp_redirect( admin_url( 'admin.php?page=spotify-connect-player' ) );
            exit;
        } else {
            wp_die( 'Error getting access token.' );
        }
    }

    public function get_access_token() {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( 'You must be logged in.' );
        }

        $user_id = get_current_user_id();
        $access_token = get_user_meta( $user_id, 'spotify_connect_player_access_token', true );

        if ( ! $access_token ) {
            wp_send_json_error( 'No access token found.' );
        }

        // Check if the access token is expired and refresh it if necessary.
        if ( $this->is_token_expired( $user_id ) ) {
            $access_token = $this->refresh_access_token( $user_id );
        }

        wp_send_json_success( $access_token );
    }

    private function is_token_expired( $user_id ) {
        $expiry = get_user_meta( $user_id, 'spotify_connect_player_token_expiry', true );
        return time() > $expiry;
    }

    private function refresh_access_token( $user_id ) {
        $refresh_token = get_user_meta( $user_id, 'spotify_connect_player_refresh_token', true );
        $client_id = get_option( 'spotify_connect_player_client_id' );
        $client_secret = get_option( 'spotify_connect_player_client_secret' );

        $response = wp_remote_post( 'https://accounts.spotify.com/api/token', array(
            'method'    => 'POST',
            'headers'   => array(
                'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
            ),
            'body'      => array(
                'grant_type'   => 'refresh_token',
                'refresh_token' => $refresh_token,
            ),
        ) );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ) );

        if ( isset( $body->access_token ) ) {
            update_user_meta( $user_id, 'spotify_connect_player_access_token', $body->access_token );
            update_user_meta( $user_id, 'spotify_connect_player_token_expiry', time() + $body->expires_in );

            return $body->access_token;
        } else {
            return false;
        }
    }
}
