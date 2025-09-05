window.onSpotifyWebPlaybackSDKReady = () => {
    const token_url = spotifyConnectPlayer.ajaxurl + '?action=spotify_connect_get_access_token';
    const device_name = spotifyConnectPlayer.deviceName;
    console.log('Site Icon URL:', spotifyConnectPlayer.siteIconUrl); // Debugging line
    const albumArt = document.getElementById('album-art');
    const trackTitle = document.getElementById('track-title');
    const trackArtist = document.getElementById('track-artist');
    const previousTrackButton = document.getElementById('previous-track');
    const togglePlayButton = document.getElementById('togglePlay');
    const nextTrackButton = document.getElementById('next-track');
    const currentTimeSpan = document.getElementById('current-time');
    const progressBar = document.getElementById('progress-bar');
    const totalTimeSpan = document.getElementById('total-time');

    let player = null;
    let currentDeviceId = null;

    function formatTime(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = ((ms % 60000) / 1000).toFixed(0);
        return minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
    }

    fetch(token_url)
        .then(response => {
            if (!response.ok) {
                console.error('Failed to fetch access token. Status:', response.status);
                return response.text().then(text => { throw new Error(text); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const token = data.data;
                console.log('Access Token received:', token);

                player = new Spotify.Player({
                    name: device_name,
                    getOAuthToken: cb => { cb(token); },
                    volume: 1.0
                });

                // Ready
                player.addListener('ready', ({ device_id }) => {
                    console.log('Ready with Device ID', device_id);
                    currentDeviceId = device_id;
                    console.log('Player object:', player);
                    trackTitle.textContent = 'Connect to Spotify';
                    trackArtist.textContent = 'Use Spotify Connect to play music here.';
                    albumArt.src = spotifyConnectPlayer.siteIconUrl; // Set album art to site icon
                });

                // Not Ready
                player.addListener('not_ready', ({ device_id }) => {
                    console.log('Device ID has gone offline', device_id);
                    trackTitle.textContent = 'Connect to Spotify';
                    trackArtist.textContent = 'Use Spotify Connect to play music here.';
                    albumArt.src = spotifyConnectPlayer.siteIconUrl; // Set album art to site icon
                });

                // Player State Changed
                player.addListener('player_state_changed', state => {
                    if (!state || !state.track_window) {
                        trackTitle.textContent = 'Connect to Spotify';
                        trackArtist.textContent = 'Use Spotify Connect to play music here.';
                        albumArt.src = spotifyConnectPlayer.siteIconUrl; // Set album art to site icon
                        return;
                    }

                    albumArt.src = state.track_window.current_track.album.images[0].url;
                    trackTitle.textContent = state.track_window.current_track.name;
                    trackArtist.textContent = state.track_window.current_track.artists.map(artist => artist.name).join(', ');

                    progressBar.max = state.duration;
                    progressBar.value = state.position;
                    currentTimeSpan.textContent = formatTime(state.position);
                    totalTimeSpan.textContent = formatTime(state.duration);

                    if (state.paused) {
                        togglePlayButton.innerHTML = '<svg role="img" height="16" width="16" viewBox="0 0 16 16" fill="currentColor"><path d="M3 1.713a.7.7 0 011.05-.607l10.89 6.288a.7.7 0 010 1.214L4.05 14.894A.7.7 0 013 14.288V1.713z"></path></svg>'; // Play icon
                    } else {
                        togglePlayButton.innerHTML = '<svg role="img" height="16" width="16" viewBox="0 0 16 16" fill="currentColor"><path d="M3 2.667v10.666c0 .8.647 1.45 1.448 1.45h.604c.798 0 1.448-.65 1.448-1.45V2.667c0-.8-.65-1.45-1.448-1.45h-.604C3.647 1.217 3 1.867 3 2.667zm7 0v10.666c0 .8.647 1.45 1.448 1.45h.604c.798 0 1.448-.65 1.448-1.45V2.667c0-.8-.65-1.45-1.448-1.45h-.604C10.647 1.217 10 1.867 10 2.667z"></path></svg>'; // Pause icon
                    }
                });

                player.addListener('initialization_error', ({ message }) => {
                    console.error('Initialization Error:', message);
                });

                player.addListener('authentication_error', ({ message }) => {
                    console.error('Authentication Error:', message);
                });

                player.addListener('account_error', ({ message }) => {
                    console.error('Account Error:', message);
                });

                player.addListener('playback_error', ({ message }) => {
                    console.error('Playback Error:', message);
                });

                // Control Buttons
                previousTrackButton.onclick = () => { player.previousTrack(); };
                togglePlayButton.onclick = () => { player.togglePlay(); };
                nextTrackButton.onclick = () => { player.nextTrack(); };

                // Seek Control
                progressBar.oninput = (event) => {
                    player.seek(Number(event.target.value));
                };

                player.connect().then(success => {
                    if (success) {
                        console.log('Spotify Player successfully connected!');
                    } else {
                        console.log('Spotify Player connection failed.');
                    }
                });
            } else {
                console.error('Failed to get access token from AJAX. Data:', data);
            }
        })
        .catch(error => {
            console.error('Error during access token fetch:', error);
        });
};
