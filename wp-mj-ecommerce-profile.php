<?php
/**
 * Plugin Name: WP MJ E-commerce Profile
 * Plugin URI: https://github.com/MajideND/wp-mj-ecommerce-profile
 * Description: Adds a custom Profile post type for WooCommerce products with types نویسنده (Writer), مترجم (Translator), and انتشارات (Publisher) with Farsi support
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
        add_action('init', array($this, 'register_post_type_and_taxonomy'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        
        // Add meta boxes for better UX
        add_action('add_meta_boxes', array($this, 'add_profile_meta_boxes'));
        add_action('add_meta_boxes_mj_profile', array($this, 'add_profile_type_meta_box'));
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
     * Register custom post type and taxonomy for profiles
     */
    public function register_post_type_and_taxonomy() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        // Register Profile custom post type
        $this->register_profile_post_type();
        
        // Register Profile Type taxonomy
        $this->register_profile_type_taxonomy();
        
        // Check if we need to flush rewrite rules after activation
        if (get_transient('wp_mj_ecommerce_profile_flush_rewrite_rules')) {
            flush_rewrite_rules();
            delete_transient('wp_mj_ecommerce_profile_flush_rewrite_rules');
        }
    }
    
    /**
     * Register Profile custom post type
     */
    private function register_profile_post_type() {
        $labels = array(
            'name'                  => _x('پروفایل‌ها', 'post type general name', 'wp-mj-ecommerce-profile'),
            'singular_name'         => _x('پروفایل', 'post type singular name', 'wp-mj-ecommerce-profile'),
            'menu_name'             => _x('پروفایل‌ها', 'admin menu', 'wp-mj-ecommerce-profile'),
            'name_admin_bar'        => _x('پروفایل', 'add new on admin bar', 'wp-mj-ecommerce-profile'),
            'add_new'               => _x('افزودن جدید', 'profile', 'wp-mj-ecommerce-profile'),
            'add_new_item'          => __('افزودن پروفایل جدید', 'wp-mj-ecommerce-profile'),
            'new_item'              => __('پروفایل جدید', 'wp-mj-ecommerce-profile'),
            'edit_item'             => __('ویرایش پروفایل', 'wp-mj-ecommerce-profile'),
            'view_item'             => __('مشاهده پروفایل', 'wp-mj-ecommerce-profile'),
            'all_items'             => __('همه پروفایل‌ها', 'wp-mj-ecommerce-profile'),
            'search_items'          => __('جستجوی پروفایل', 'wp-mj-ecommerce-profile'),
            'parent_item_colon'     => __('پروفایل والد:', 'wp-mj-ecommerce-profile'),
            'not_found'             => __('پروفایلی یافت نشد', 'wp-mj-ecommerce-profile'),
            'not_found_in_trash'    => __('پروفایلی در زباله‌دان یافت نشد', 'wp-mj-ecommerce-profile'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'profile', 'with_front' => false),
            'capability_type'       => 'post',
            'has_archive'           => false, // No archive page
            'hierarchical'          => false,
            'menu_position'         => 56, // After WooCommerce
            'menu_icon'             => 'dashicons-id',
            'show_in_rest'          => true,
            'supports'              => array('title', 'editor', 'thumbnail'),
        );
        
        register_post_type('mj_profile', $args);
    }
    
    /**
     * Register Profile Type taxonomy
     */
    private function register_profile_type_taxonomy() {
        $labels = array(
            'name'              => _x('نوع پروفایل', 'taxonomy general name', 'wp-mj-ecommerce-profile'),
            'singular_name'     => _x('نوع پروفایل', 'taxonomy singular name', 'wp-mj-ecommerce-profile'),
            'search_items'      => __('جستجوی نوع', 'wp-mj-ecommerce-profile'),
            'all_items'         => __('همه انواع', 'wp-mj-ecommerce-profile'),
            'edit_item'         => __('ویرایش نوع', 'wp-mj-ecommerce-profile'),
            'update_item'       => __('به‌روزرسانی نوع', 'wp-mj-ecommerce-profile'),
            'add_new_item'      => __('افزودن نوع جدید', 'wp-mj-ecommerce-profile'),
            'new_item_name'     => __('نام نوع جدید', 'wp-mj-ecommerce-profile'),
            'menu_name'         => __('نوع پروفایل', 'wp-mj-ecommerce-profile'),
        );
        
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'profile-type'),
            'meta_box_cb'       => false, // We'll use custom meta box
        );
        
        register_taxonomy('mj_profile_type', array('mj_profile'), $args);
        
        // Add default terms if they don't exist
        $this->add_default_profile_types();
    }
    
    /**
     * Add default profile type terms
     */
    private function add_default_profile_types() {
        $default_types = array(
            'writer'     => 'نویسنده',
            'translator' => 'مترجم',
            'publisher'  => 'انتشارات',
        );
        
        foreach ($default_types as $slug => $name) {
            if (!term_exists($slug, 'mj_profile_type')) {
                $result = wp_insert_term($name, 'mj_profile_type', array('slug' => $slug));
                if (is_wp_error($result)) {
                    error_log('WP MJ E-commerce Profile: Failed to create term ' . $slug . ': ' . $result->get_error_message());
                }
            }
        }
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
     * Add meta box for profile type selection on profile post type
     */
    public function add_profile_type_meta_box() {
        add_meta_box(
            'mj_profile_type_metabox',
            __('نوع پروفایل', 'wp-mj-ecommerce-profile'),
            array($this, 'render_profile_type_meta_box'),
            'mj_profile',
            'side',
            'high'
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
        $this->render_profile_selector($post, 'writer');
    }
    
    /**
     * Render Translator meta box
     */
    public function render_translator_meta_box($post) {
        $this->render_profile_selector($post, 'translator');
    }
    
    /**
     * Render Publisher meta box
     */
    public function render_publisher_meta_box($post) {
        $this->render_profile_selector($post, 'publisher');
    }
    
    /**
     * Render profile type meta box for profile post type
     */
    public function render_profile_type_meta_box($post) {
        wp_nonce_field('mj_profile_type_nonce_action', 'mj_profile_type_nonce');
        
        $profile_types = get_terms(array(
            'taxonomy'   => 'mj_profile_type',
            'hide_empty' => false,
        ));
        
        $current_types = wp_get_object_terms($post->ID, 'mj_profile_type');
        $current_type_id = !empty($current_types) && !is_wp_error($current_types) ? $current_types[0]->term_id : 0;
        
        echo '<select name="mj_profile_type" class="widefat" required>';
        echo '<option value="">' . __('انتخاب کنید', 'wp-mj-ecommerce-profile') . '</option>';
        
        if (!is_wp_error($profile_types) && !empty($profile_types)) {
            foreach ($profile_types as $type) {
                printf(
                    '<option value="%d" %s>%s</option>',
                    $type->term_id,
                    selected($current_type_id, $type->term_id, false),
                    esc_html($type->name)
                );
            }
        }
        
        echo '</select>';
        echo '<p class="howto">' . __('این پروفایل از چه نوعی است؟', 'wp-mj-ecommerce-profile') . '</p>';
    }
    
    /**
     * Render profile selector for products (select profiles by type)
     */
    private function render_profile_selector($post, $profile_type) {
        // Get the term ID for this profile type
        $type_term = get_term_by('slug', $profile_type, 'mj_profile_type');
        
        if (!$type_term || is_wp_error($type_term)) {
            echo '<p>' . __('نوع پروفایل یافت نشد', 'wp-mj-ecommerce-profile') . '</p>';
            if (is_wp_error($type_term)) {
                error_log('WP MJ E-commerce Profile: Error getting term for ' . $profile_type . ': ' . $type_term->get_error_message());
            }
            return;
        }
        
        // Get all profiles of this type (limit to 500 for performance)
        $profiles = get_posts(array(
            'post_type'      => 'mj_profile',
            'posts_per_page' => 500,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'mj_profile_type',
                    'field'    => 'term_id',
                    'terms'    => $type_term->term_id,
                ),
            ),
        ));
        
        // Get current selection
        $current_profile = get_post_meta($post->ID, '_mj_profile_' . $profile_type, true);
        
        echo '<select name="mj_profile_' . esc_attr($profile_type) . '" class="widefat">';
        echo '<option value="">' . __('انتخاب کنید', 'wp-mj-ecommerce-profile') . '</option>';
        
        if (!empty($profiles)) {
            foreach ($profiles as $profile) {
                printf(
                    '<option value="%d" %s>%s</option>',
                    $profile->ID,
                    selected($current_profile, $profile->ID, false),
                    esc_html($profile->post_title)
                );
            }
        }
        
        echo '</select>';
        
        // Add link to add new profile
        echo '<p class="howto">';
        printf(
            '<a href="%s" target="_blank">%s</a>',
            admin_url('post-new.php?post_type=mj_profile'),
            __('افزودن پروفایل جدید', 'wp-mj-ecommerce-profile')
        );
        echo '</p>';
    }
}

// Save profile type when profile is saved
add_action('save_post_mj_profile', 'wp_mj_save_profile_type', 10, 2);

function wp_mj_save_profile_type($post_id, $post) {
    // Check nonce
    if (!isset($_POST['mj_profile_type_nonce']) || !wp_verify_nonce($_POST['mj_profile_type_nonce'], 'mj_profile_type_nonce_action')) {
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
    
    // Validate and save profile type
    if (isset($_POST['mj_profile_type'])) {
        $type_id = intval($_POST['mj_profile_type']);
        if ($type_id > 0) {
            $term = get_term($type_id, 'mj_profile_type');
            if (!is_wp_error($term) && $term) {
                wp_set_object_terms($post_id, $type_id, 'mj_profile_type', false);
            } else {
                error_log('WP MJ E-commerce Profile: Invalid profile type ID: ' . $type_id);
            }
        } else {
            // If publishing without a type, show an error
            if ($post->post_status === 'publish') {
                wp_die(__('یک نوع پروفایل باید انتخاب شود', 'wp-mj-ecommerce-profile'), __('خطا', 'wp-mj-ecommerce-profile'), array('back_link' => true));
            }
            wp_delete_object_term_relationships($post_id, 'mj_profile_type');
        }
    } elseif ($post->post_status === 'publish') {
        // If publishing without selecting a type, prevent it
        wp_die(__('یک نوع پروفایل باید انتخاب شود', 'wp-mj-ecommerce-profile'), __('خطا', 'wp-mj-ecommerce-profile'), array('back_link' => true));
    }
}

// Save profile selections when product is saved
add_action('save_post_product', 'wp_mj_save_product_profiles', 10, 2);

function wp_mj_save_product_profiles($post_id, $post) {
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
    
    // Save Writer profile
    if (isset($_POST['mj_profile_writer'])) {
        $profile_id = intval($_POST['mj_profile_writer']);
        if ($profile_id > 0) {
            update_post_meta($post_id, '_mj_profile_writer', $profile_id);
        } else {
            delete_post_meta($post_id, '_mj_profile_writer');
        }
    }
    
    // Save Translator profile
    if (isset($_POST['mj_profile_translator'])) {
        $profile_id = intval($_POST['mj_profile_translator']);
        if ($profile_id > 0) {
            update_post_meta($post_id, '_mj_profile_translator', $profile_id);
        } else {
            delete_post_meta($post_id, '_mj_profile_translator');
        }
    }
    
    // Save Publisher profile
    if (isset($_POST['mj_profile_publisher'])) {
        $profile_id = intval($_POST['mj_profile_publisher']);
        if ($profile_id > 0) {
            update_post_meta($post_id, '_mj_profile_publisher', $profile_id);
        } else {
            delete_post_meta($post_id, '_mj_profile_publisher');
        }
    }
}

// Initialize the plugin
add_action('plugins_loaded', array('WP_MJ_Ecommerce_Profile', 'get_instance'));

/**
 * Plugin activation hook
 * Set a transient to flush rewrite rules on next init
 */
function wp_mj_ecommerce_profile_activate() {
    // Set a transient to trigger rewrite flush on next init
    // This ensures post types are registered before flushing
    set_transient('wp_mj_ecommerce_profile_flush_rewrite_rules', 1, 60);
}
register_activation_hook(__FILE__, 'wp_mj_ecommerce_profile_activate');

/**
 * Plugin deactivation hook
 * Flush rewrite rules to clean up
 */
function wp_mj_ecommerce_profile_deactivate() {
    // Flush rewrite rules to clean up profile URLs
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'wp_mj_ecommerce_profile_deactivate');
