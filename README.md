# ManiaExchange Integration

ManiaExchange Integration is a WordPress plugin that allows you to integrate with the ManiaExchange API. It provides functionality to fetch and display Trackmania maps and tags directly within your WordPress site.

## Features

- Custom post type `Maps` for storing Trackmania maps.
- Non-hierarchical taxonomy `Tags` for categorizing maps.
- Admin settings page for configuring API details.
- Fetch and save tags from the ManiaExchange API.
- Fetch map details by Track ID and create new map posts.

## Installation

1. Download or clone this repository into your WordPress plugins directory:
   ```
   /wp-content/plugins/maniaexchange-integration/
   ```

2. Activate the plugin from the WordPress admin dashboard under **Plugins**.

3. Navigate to **Settings > ManiaExchange** to configure the plugin.

## Usage

### Configure API Details

1. Go to **Settings > ManiaExchange**.
2. Enter your ManiaExchange API Key and API URL.
3. Save the settings.

### Fetch Tags

1. On the settings page, click the **Fetch and Save Tags** button.
2. This will fetch all tags from the ManiaExchange API and save them to the `map_tags` taxonomy.

### Fetch and Create Maps

1. Enter a Track ID in the **Fetch Track Data** section on the settings page.
2. Click the **Fetch and Create Map** button.
3. The plugin will fetch map details from the API and create a new post in the `Maps` post type.

## Custom Post Type

- **Maps**: A custom post type for storing Trackmania maps.
  - Supports title, editor, thumbnail, and custom fields.
  - Accessible via the WordPress REST API.

## Taxonomy

- **Tags**: A non-hierarchical taxonomy for categorizing maps.
  - Tags are fetched from the ManiaExchange API and stored with their corresponding names and slugs.

## Development

The plugin is structured using an Object-Oriented Programming (OOP) approach and split into multiple files for better maintainability:

- `class-maniaexchange-plugin.php`: Main plugin class for initialization.
- `class-maniaexchange-admin.php`: Handles admin settings and forms.
- `class-maniaexchange-post-type.php`: Registers the custom post type and taxonomy.
- `class-maniaexchange-api.php`: Handles API interactions for fetching maps and tags.

## License

This plugin is open-source and distributed under the MIT License.

## Author

Developed by **Miikka Niemeläinen**.