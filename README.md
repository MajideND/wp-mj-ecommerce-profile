# WP MJ E-commerce Profile

A WordPress plugin that adds a custom Profile post type for WooCommerce products with full Farsi/Persian language support.

## Features

- **Custom Post Type**: Profile post type with `/profile` URL slug and no archive page
- **Three Profile Types**: نویسنده (Writer), مترجم (Translator), انتشارات (Publisher)
- **Single Selection**: Each product can select one profile from each type
- **Farsi Support**: Full RTL and Farsi text support
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
4. Navigate to **پروفایل‌ها** (Profiles) in the admin menu to add profiles

## Usage

### Adding Profiles

1. Go to **پروفایل‌ها > افزودن جدید** (Profiles > Add New)
2. Enter the profile name and details
3. Select the **نوع پروفایل** (Profile Type) in the sidebar:
   - نویسنده (Writer)
   - مترجم (Translator)
   - انتشارات (Publisher)
4. Publish the profile

### Assigning Profiles to Products

1. Edit a product in WooCommerce
2. Find the profile meta boxes in the right sidebar:
   - **نویسنده** - Select a writer profile
   - **مترجم** - Select a translator profile
   - **انتشارات** - Select a publisher profile
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
  - `writer` - نویسنده (Writer)
  - `translator` - مترجم (Translator)
  - `publisher` - انتشارات (Publisher)

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

## License

GPL v2 or later