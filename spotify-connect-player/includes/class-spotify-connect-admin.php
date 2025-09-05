<?php

class Spotify_Connect_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_admin_menu() {
        add_menu_page(
            'Spotify Connect Player',
            'Spotify Connect',
            'manage_options',
            'spotify-connect-player',
            array( $this, 'settings_page' ),
            'dashicons-spotify',
            90
        );
    }

    public function register_settings() {
        register_setting( 'spotify_connect_player_settings', 'spotify_connect_player_client_id' );
        register_setting( 'spotify_connect_player_settings', 'spotify_connect_player_client_secret' );
        register_setting( 'spotify_connect_player_settings', 'spotify_connect_player_device_name' );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>Spotify Connect Player Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'spotify_connect_player_settings' );
                do_settings_sections( 'spotify_connect_player_settings' );
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Client ID</th>
                        <td><input type="text" name="spotify_connect_player_client_id" value="<?php echo esc_attr( get_option( 'spotify_connect_player_client_id' ) ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Client Secret</th>
                        <td><input type="password" name="spotify_connect_player_client_secret" value="<?php echo esc_attr( get_option( 'spotify_connect_player_client_secret' ) ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Device Name</th>
                        <td><input type="text" name="spotify_connect_player_device_name" value="<?php echo esc_attr( get_option( 'spotify_connect_player_device_name', 'Spotify Connect Player' ) ); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <hr />
            <h2>Connect to Spotify</h2>
            <p>Click the button below to connect your Spotify account.</p>
            <p><a href="<?php echo admin_url( 'admin-post.php?action=spotify_connect_authorize' ); ?>" class="button button-primary">Connect to Spotify</a></p>
        </div>
        <?php
    }
}
