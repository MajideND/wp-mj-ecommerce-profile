# WP MJ E-commerce Profile

A WordPress plugin that adds custom profile taxonomies for WooCommerce products with full Farsi/Persian language support.

## Features

- **Three Custom Taxonomies**: نویسنده (Writer), مترجم (Translator), انتشارات (Publisher)
- **Single Selection**: Each product can select one item from each taxonomy
- **Farsi Support**: Full RTL and Farsi text support
- **WooCommerce Integration**: Seamlessly integrates with WooCommerce products
- **Admin Columns**: Shows selected profiles in product list admin view
- **Custom Meta Boxes**: User-friendly dropdown selectors in product edit screen

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- WooCommerce plugin installed and activated

## Installation

1. Upload the plugin files to `/wp-content/plugins/wp-mj-ecommerce-profile/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure WooCommerce is installed and activated
4. Navigate to Products > نویسنده, Products > مترجم, or Products > انتشارات to add profile terms

## Usage

### Adding Profile Terms

1. Go to **Products > نویسنده** to add writers/authors
2. Go to **Products > مترجم** to add translators
3. Go to **Products > انتشارات** to add publishers

### Assigning Profiles to Products

1. Edit a product in WooCommerce
2. Find the profile meta boxes in the right sidebar
3. Select one نویسنده (writer), one مترجم (translator), and/or one انتشارات (publisher)
4. Save/update the product

## Taxonomies

The plugin registers three custom taxonomies:

- **mj_writer** (نویسنده) - Writer/Author profile
- **mj_translator** (مترجم) - Translator profile
- **mj_publisher** (انتشارات) - Publisher profile

All taxonomies:
- Are non-hierarchical
- Support REST API
- Show in admin columns
- Have custom meta boxes for single selection

## Development

The plugin follows WordPress coding standards and best practices:
- Proper escaping and sanitization
- Nonce verification for security
- Singleton pattern for the main class
- Translation-ready with text domain support

## License

GPL v2 or later