<?php
/**
 * Plugin Name: WP MJ E-commerce Profile
 * Plugin URI: https://github.com/MajideND/wp-mj-ecommerce-profile
 * Description: Adds custom profile taxonomies for WooCommerce products including نویسنده (Writer), مترجم (Translator), and انتشارات (Publisher) with Farsi support
 * Version: 1.0.0
 * Author: MajideND
 * Author URI: https://github.com/MajideND
 * Text Domain: wp-mj-ecommerce-profile
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 */
class WP_MJ_Ecommerce_Profile {
    
    /**
     * Plugin version
     */
    const VERSION = '1.0.0';
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', array($this, 'register_taxonomies'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        
        // Add meta boxes for better UX
        add_action('add_meta_boxes', array($this, 'add_profile_meta_boxes'));
    }
    
    /**
     * Load plugin text domain for translations
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'wp-mj-ecommerce-profile',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }
    
    /**
     * Enqueue admin styles for RTL support
     */
    public function enqueue_admin_styles($hook) {
        if ('post.php' === $hook || 'post-new.php' === $hook) {
            $css_file = plugin_dir_path(__FILE__) . 'assets/css/admin.css';
            if (file_exists($css_file)) {
                wp_enqueue_style(
                    'wp-mj-ecommerce-profile-admin',
                    plugins_url('assets/css/admin.css', __FILE__),
                    array(),
                    self::VERSION
                );
            }
        }
    }
    
    /**
     * Register custom taxonomies for profiles
     */
    public function register_taxonomies() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        // Register نویسنده (Writer/Author) taxonomy
        $this->register_writer_taxonomy();
        
        // Register مترجم (Translator) taxonomy
        $this->register_translator_taxonomy();
        
        // Register انتشارات (Publisher) taxonomy
        $this->register_publisher_taxonomy();
    }
    
    /**
     * Register Writer taxonomy
     */
    private function register_writer_taxonomy() {
        $labels = array(
            'name'              => _x('نویسنده', 'taxonomy general name', 'wp-mj-ecommerce-profile'),
            'singular_name'     => _x('نویسنده', 'taxonomy singular name', 'wp-mj-ecommerce-profile'),
            'search_items'      => __('جستجوی نویسنده', 'wp-mj-ecommerce-profile'),
            'all_items'         => __('همه نویسندگان', 'wp-mj-ecommerce-profile'),
            'parent_item'       => __('نویسنده والد', 'wp-mj-ecommerce-profile'),
            'parent_item_colon' => __('نویسنده والد:', 'wp-mj-ecommerce-profile'),
            'edit_item'         => __('ویرایش نویسنده', 'wp-mj-ecommerce-profile'),
            'update_item'       => __('به‌روزرسانی نویسنده', 'wp-mj-ecommerce-profile'),
            'add_new_item'      => __('افزودن نویسنده جدید', 'wp-mj-ecommerce-profile'),
            'new_item_name'     => __('نام نویسنده جدید', 'wp-mj-ecommerce-profile'),
            'menu_name'         => __('نویسنده', 'wp-mj-ecommerce-profile'),
        );
        
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'nevisandeh'),
            'meta_box_cb'       => false, // We'll use custom meta box
        );
        
        register_taxonomy('mj_writer', array('product'), $args);
    }
    
    /**
     * Register Translator taxonomy
     */
    private function register_translator_taxonomy() {
        $labels = array(
            'name'              => _x('مترجم', 'taxonomy general name', 'wp-mj-ecommerce-profile'),
            'singular_name'     => _x('مترجم', 'taxonomy singular name', 'wp-mj-ecommerce-profile'),
            'search_items'      => __('جستجوی مترجم', 'wp-mj-ecommerce-profile'),
            'all_items'         => __('همه مترجمان', 'wp-mj-ecommerce-profile'),
            'parent_item'       => __('مترجم والد', 'wp-mj-ecommerce-profile'),
            'parent_item_colon' => __('مترجم والد:', 'wp-mj-ecommerce-profile'),
            'edit_item'         => __('ویرایش مترجم', 'wp-mj-ecommerce-profile'),
            'update_item'       => __('به‌روزرسانی مترجم', 'wp-mj-ecommerce-profile'),
            'add_new_item'      => __('افزودن مترجم جدید', 'wp-mj-ecommerce-profile'),
            'new_item_name'     => __('نام مترجم جدید', 'wp-mj-ecommerce-profile'),
            'menu_name'         => __('مترجم', 'wp-mj-ecommerce-profile'),
        );
        
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'motarjem'),
            'meta_box_cb'       => false, // We'll use custom meta box
        );
        
        register_taxonomy('mj_translator', array('product'), $args);
    }
    
    /**
     * Register Publisher taxonomy
     */
    private function register_publisher_taxonomy() {
        $labels = array(
            'name'              => _x('انتشارات', 'taxonomy general name', 'wp-mj-ecommerce-profile'),
            'singular_name'     => _x('انتشارات', 'taxonomy singular name', 'wp-mj-ecommerce-profile'),
            'search_items'      => __('جستجوی انتشارات', 'wp-mj-ecommerce-profile'),
            'all_items'         => __('همه انتشارات', 'wp-mj-ecommerce-profile'),
            'parent_item'       => __('انتشارات والد', 'wp-mj-ecommerce-profile'),
            'parent_item_colon' => __('انتشارات والد:', 'wp-mj-ecommerce-profile'),
            'edit_item'         => __('ویرایش انتشارات', 'wp-mj-ecommerce-profile'),
            'update_item'       => __('به‌روزرسانی انتشارات', 'wp-mj-ecommerce-profile'),
            'add_new_item'      => __('افزودن انتشارات جدید', 'wp-mj-ecommerce-profile'),
            'new_item_name'     => __('نام انتشارات جدید', 'wp-mj-ecommerce-profile'),
            'menu_name'         => __('انتشارات', 'wp-mj-ecommerce-profile'),
        );
        
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'entesharat'),
            'meta_box_cb'       => false, // We'll use custom meta box
        );
        
        register_taxonomy('mj_publisher', array('product'), $args);
    }
    
    /**
     * Add custom meta boxes for single selection
     */
    public function add_profile_meta_boxes() {
        // Only add for product post type
        if (get_post_type() !== 'product') {
            return;
        }
        
        // Add nonce field once for all meta boxes
        add_action('edit_form_after_title', array($this, 'render_profile_nonce'));
        
        // نویسنده (Writer) meta box
        add_meta_box(
            'mj_writer_metabox',
            __('نویسنده', 'wp-mj-ecommerce-profile'),
            array($this, 'render_writer_meta_box'),
            'product',
            'side',
            'default'
        );
        
        // مترجم (Translator) meta box
        add_meta_box(
            'mj_translator_metabox',
            __('مترجم', 'wp-mj-ecommerce-profile'),
            array($this, 'render_translator_meta_box'),
            'product',
            'side',
            'default'
        );
        
        // انتشارات (Publisher) meta box
        add_meta_box(
            'mj_publisher_metabox',
            __('انتشارات', 'wp-mj-ecommerce-profile'),
            array($this, 'render_publisher_meta_box'),
            'product',
            'side',
            'default'
        );
    }
    
    /**
     * Render nonce field once for all profile meta boxes
     */
    public function render_profile_nonce() {
        if (get_post_type() === 'product') {
            wp_nonce_field('mj_profile_nonce_action', 'mj_profile_nonce');
        }
    }
    
    /**
     * Render Writer meta box
     */
    public function render_writer_meta_box($post) {
        $this->render_single_select_taxonomy($post, 'mj_writer');
    }
    
    /**
     * Render Translator meta box
     */
    public function render_translator_meta_box($post) {
        $this->render_single_select_taxonomy($post, 'mj_translator');
    }
    
    /**
     * Render Publisher meta box
     */
    public function render_publisher_meta_box($post) {
        $this->render_single_select_taxonomy($post, 'mj_publisher');
    }
    
    /**
     * Render single select taxonomy dropdown
     */
    private function render_single_select_taxonomy($post, $taxonomy) {
        $terms = get_terms(array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ));
        
        $current_terms = wp_get_object_terms($post->ID, $taxonomy);
        $current_term_id = !empty($current_terms) && !is_wp_error($current_terms) ? $current_terms[0]->term_id : 0;
        
        echo '<select name="' . esc_attr($taxonomy) . '" class="widefat">';
        echo '<option value="">' . __('انتخاب کنید', 'wp-mj-ecommerce-profile') . '</option>';
        
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                printf(
                    '<option value="%d" %s>%s</option>',
                    $term->term_id,
                    selected($current_term_id, $term->term_id, false),
                    esc_html($term->name)
                );
            }
        }
        
        echo '</select>';
        
        // Add link to add new term
        $taxonomy_obj = get_taxonomy($taxonomy);
        if ($taxonomy_obj) {
            echo '<p class="howto">';
            printf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('edit-tags.php?taxonomy=' . $taxonomy . '&post_type=product'),
                __('مدیریت موارد', 'wp-mj-ecommerce-profile')
            );
            echo '</p>';
        }
    }
}

// Save taxonomy terms when product is saved
add_action('save_post_product', 'wp_mj_save_profile_taxonomies', 10, 2);

function wp_mj_save_profile_taxonomies($post_id, $post) {
    // Check nonce
    if (!isset($_POST['mj_profile_nonce']) || !wp_verify_nonce($_POST['mj_profile_nonce'], 'mj_profile_nonce_action')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save Writer
    if (isset($_POST['mj_writer'])) {
        $term_id = intval($_POST['mj_writer']);
        if ($term_id > 0) {
            wp_set_object_terms($post_id, $term_id, 'mj_writer', false);
        } else {
            wp_delete_object_term_relationships($post_id, 'mj_writer');
        }
    }
    
    // Save Translator
    if (isset($_POST['mj_translator'])) {
        $term_id = intval($_POST['mj_translator']);
        if ($term_id > 0) {
            wp_set_object_terms($post_id, $term_id, 'mj_translator', false);
        } else {
            wp_delete_object_term_relationships($post_id, 'mj_translator');
        }
    }
    
    // Save Publisher
    if (isset($_POST['mj_publisher'])) {
        $term_id = intval($_POST['mj_publisher']);
        if ($term_id > 0) {
            wp_set_object_terms($post_id, $term_id, 'mj_publisher', false);
        } else {
            wp_delete_object_term_relationships($post_id, 'mj_publisher');
        }
    }
}

// Initialize the plugin
add_action('plugins_loaded', array('WP_MJ_Ecommerce_Profile', 'get_instance'));
