# WP MJ E-commerce Profile

A WordPress plugin that adds a custom Profile post type for WooCommerce products with full English and Persian/Farsi language support.

## Features

- **Custom Post Type**: Profile post type with `/profile` URL slug and no archive page
- **Three Profile Types**: Writer (نویسنده), Translator (مترجم), Publisher (انتشارات)
- **Single Selection**: Each product can select one profile from each type
- **Bilingual Support**: Full support for English and Persian/Farsi with RTL text support
- **WooCommerce Integration**: Seamlessly integrates with WooCommerce products
- **Admin Columns**: Shows selected profile type in profile list admin view
- **Custom Meta Boxes**: User-friendly dropdown selectors in product edit screen

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- WooCommerce plugin installed and activated

## Installation

1. Upload the plugin files to `/wp-content/plugins/wp-mj-ecommerce-profile/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure WooCommerce is installed and activated
4. Navigate to **Profiles** (پروفایل‌ها) in the admin menu to add profiles

## Usage

### Adding Profiles

1. Go to **Profiles > Add New** (پروفایل‌ها > افزودن جدید)
2. Enter the profile name and details
3. Select the **Profile Type** (نوع پروفایل) in the sidebar:
   - Writer (نویسنده)
   - Translator (مترجم)
   - Publisher (انتشارات)
4. Publish the profile

### Assigning Profiles to Products

1. Edit a product in WooCommerce
2. Find the profile meta boxes in the right sidebar:
   - **Writer** (نویسنده) - Select a writer profile
   - **Translator** (مترجم) - Select a translator profile
   - **Publisher** (انتشارات) - Select a publisher profile
3. Each dropdown shows only profiles of that specific type
4. Save/update the product

## Structure

### Custom Post Type

- **Post Type**: `mj_profile`
- **Slug**: `/profile`
- **Archive**: Disabled (no archive page)
- **Public**: Yes (individual profiles are publicly viewable)

### Taxonomy

- **Taxonomy**: `mj_profile_type` (Profile Type)
- **Terms**: 
  - `writer` - Writer (نویسنده)
  - `translator` - Translator (مترجم)
  - `publisher` - Publisher (انتشارات)

### Product Meta

Products store profile selections as post meta:
- `_mj_profile_writer` - Selected writer profile ID
- `_mj_profile_translator` - Selected translator profile ID
- `_mj_profile_publisher` - Selected publisher profile ID

## Development

The plugin follows WordPress coding standards and best practices:
- Proper escaping and sanitization
- Nonce verification for security
- Singleton pattern for the main class
- Translation-ready with text domain support
- Bilingual support: English (default) and Persian/Farsi translations included

## Troubleshooting

### Profile URLs showing 404 error

If profile single pages are showing a 404 error:

1. Make sure WooCommerce is installed and activated (required)
2. Deactivate and reactivate the plugin to flush rewrite rules
3. Alternatively, go to **Settings > Permalinks** and click "Save Changes" to manually flush rewrite rules

The plugin automatically flushes rewrite rules on activation to ensure profile URLs work properly.

## License

GPL v2 or later