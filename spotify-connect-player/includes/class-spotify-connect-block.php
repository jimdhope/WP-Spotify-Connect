<?php

class Spotify_Connect_Block {

    public function __construct() {
        add_action( 'init', array( $this, 'register_block' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
    }

    public function register_block() {
        register_block_type( SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'block.json', array(
            'render_callback' => array( $this, 'render_block' ),
        ) );
    }

    public function enqueue_frontend_scripts() {
        wp_enqueue_script(
            'spotify-web-playback-sdk',
            'https://sdk.scdn.co/spotify-player.js',
            array(),
            null,
            true
        );

        wp_enqueue_script(
            'spotify-connect-player-frontend',
            SPOTIFY_CONNECT_PLAYER_PLUGIN_URL . 'js/spotify-connect-player.js',
            array( 'spotify-web-playback-sdk' ),
            SPOTIFY_CONNECT_PLAYER_VERSION,
            true
        );

        wp_localize_script( 'spotify-connect-player-frontend', 'spotifyConnectPlayer', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'deviceName' => get_option( 'spotify_connect_player_device_name', 'Spotify Connect Player' ),
            'siteIconUrl' => get_site_icon_url( 512 ), // Get site icon URL, 512 is a common size
        ) );

        // Enqueue Dashicons if the block is registered and uses Dashicons
        // This assumes that if the block is registered, and the user has selected a Dashicon,
        // then Dashicons should be enqueued.
        // A more robust solution would involve checking the block's attributes on the frontend,
        // but that requires more complex JavaScript to pass the attributes.
        // For now, we'll enqueue Dashicons if the block is registered.
        if ( wp_script_is( 'spotify-connect-player-editor', 'registered' ) ) {
            wp_enqueue_style( 'dashicons' );
        }
    }

    private function get_svg_icon( $icon_key ) {
        $icons = [
            'prev' => file_get_contents( SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'svg/backward.svg' ),
            'play' => file_get_contents( SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'svg/play.svg' ),
            'pause' => file_get_contents( SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'svg/pause.svg' ),
            'next' => file_get_contents( SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'svg/forward.svg' ),
            'volume' => file_get_contents( SPOTIFY_CONNECT_PLAYER_PLUGIN_DIR . 'svg/volume.svg' ),
        ];
        return isset( $icons[ $icon_key ] ) ? $icons[ $icon_key ] : '';
    }

    public function render_block( $attributes ) {
        ob_start();
        $color_scheme = isset( $attributes['colorScheme'] ) ? $attributes['colorScheme'] : '#181818';
        $volume_control_style = isset( $attributes['volumeControlStyle'] ) ? $attributes['volumeControlStyle'] : 'horizontal';

        ?>
        <div id="spotify-player-container" style="background-color: <?php echo esc_attr( $color_scheme ); ?>;" data-volume-control-style="<?php echo esc_attr( $volume_control_style ); ?>">
            <div class="player-left">
                <div class="album-art">
                    <img id="album-art" src="" alt="Album Art">
                </div>
                <div class="track-info">
                    <h3 id="track-title"></h3>
                    <p id="track-artist"></p>
                </div>
            </div>
            <div class="player-center">
                <div class="controls">
                    <button id="previous-track"><?php echo $this->get_svg_icon( 'prev' ); ?></button>
                    <button id="togglePlay"><?php echo $this->get_svg_icon( 'play' ); ?></button>
                    <button id="next-track"><?php echo $this->get_svg_icon( 'next' ); ?></button>
                </div>
                <div class="timeline">
                    <span id="current-time">0:00</span>
                    <input type="range" id="progress-bar" value="0" max="100">
                    <span id="total-time">0:00</span>
                </div>
            </div>
            
        </div>
        <?php
        return ob_get_clean();
    }
}
