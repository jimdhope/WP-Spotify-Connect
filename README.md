# WP Spotify Connect Player

The **WP Spotify Connect Player** plugin lets you turn your WordPress site into a Spotify Connect device, streaming Spotify audio directly in the browser.  It embeds a web player on your pages that displays the current track’s album artwork, title, and provides playback controls (play/pause, next, previous) and a seek timeline.  Under the hood, it uses Spotify’s Web Playback SDK, which means a logged-in Spotify user (Premium account) must authorize the player to stream music.

## Features

* **Spotify Connect Integration** – When activated, the site appears as a Spotify Connect device in the user’s Spotify app. Users can select it from the Connect menu to stream music through the browser player.  All playback controls (play/pause, next/previous track) are supported via Spotify’s Web Playback SDK.
* **Web Player UI** – The player shows the track’s album cover and title, and includes a seekable timeline.  Users can skip forward or back between tracks and click on the timeline to seek within the current track.
* **Premium Required** – Note that the Spotify Web Playback SDK requires the user have a Spotify Premium subscription.  (Free accounts cannot play tracks through this player.)
* **Easy Gutenberg Block** – After setup, you can insert the player into any page or post via the provided Gutenberg block.  The player then appears inline where you place it.
* **Customizable Device Name** – In plugin settings you can name the “device” so it shows up with a friendly name in Spotify’s Connect list (e.g. your site’s name or hostname).

## Installation

1. **Download and install the plugin.** Either clone or download the ZIP from this repository, then in your WordPress admin navigate to **Plugins > Add New > Upload Plugin**.  Select the `.zip` file and click **Install Now**.

2. **Activate the plugin.** After installation, click **Activate Plugin** to enable it on your site.  You should now see a new **Spotify Connect** settings page or menu in your admin dashboard.

3. **Configure your Spotify credentials.**

   * Create a free Spotify Developer account at [developer.spotify.com](https://developer.spotify.com/).  In the Dashboard, click **Create an App**, give it a name/description, and accept the terms.  You will then see your **Client ID** and **Client Secret** on the app’s overview page.
   * In your plugin’s settings page, paste the **Client ID** and **Client Secret** you obtained.  These are the “App credentials” needed for the Spotify API.
   * Save your settings.  The plugin will usually prompt you to log into Spotify (OAuth) to authorize the player.  Follow the on-screen instructions to grant permission.  Once authorized, the plugin can use Spotify’s Web API and Web Playback SDK to stream music on your site.

4. **Verify setup.** Your site should now appear as a device in your Spotify app (desktop or mobile) under the same account.  Play a track in Spotify and click **Devices**, then select your site as the playback target.  The web player interface on your site should then show the track’s artwork and allow full playback control.

## Usage

To use the player in your content, insert it with the provided **Gutenberg block**:

1. Edit any Page or Post in the WordPress editor.
2. Click the **+** button to add a new block.
3. Search for **Spotify Connect Player** and select it.
4. The block will be inserted into your content, and when published, the Spotify web player will appear there.

When the page is viewed, the block will render the Spotify web player. A logged-in Premium user can then control playback from your site.  The player will display album art and a progress bar (seek timeline).  Users can click **Play/Pause** and **Next/Previous** to control the music.  (Behind the scenes, the plugin calls Spotify’s `pause()`, `resume()`, `nextTrack()`, `previousTrack()` and `seek()` functions of the Web Playback SDK.)

Keep in mind that only Spotify Premium accounts can stream via the Web Playback SDK.  If a non-premium user tries to use the player, Spotify will refuse playback.  Also note that the user must authenticate via Spotify (the OAuth step above) so that the plugin can obtain an access token for the Web Playback SDK.

## License

This plugin is released under the GNU General Public License (GPL v2 or later).  All code and assets in this repository comply with WordPress’s requirement that plugins use a GPL-compatible license.  By default, WordPress recommends using “GPLv2 or later” for plugins to ensure compatibility.

## Contributing

Contributions and feedback are welcome!  To help you submit useful pull requests or issues, we include a `CONTRIBUTING.md` file that outlines the guidelines for this project.  Please fork the repository, make your changes on a feature branch, and submit a pull request.  We will review and merge useful improvements.

**Note:** This plugin is open-source. Please ensure any code you add follows WordPress best practices and that you have the right to use any third-party code or assets.  (As per WordPress rules, all code distributed with this plugin must be GPL-compatible.)

**Happy coding!**
