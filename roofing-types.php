<?php
/*
   Plugin Name:  Roofing Types
   Plugin URI: https://adam.thehoick.com/
   Version: 0.0.1
   Author: Adam Sommer
   Description: Update the Roofing Types carousel on the home page.
 */

defined( 'ABSPATH' ) or die( 'No please!' );

//
// Add the new post type.
//
function roofingtypes_create_post_type() {
  register_post_type( 'roofingtypes',
    [
      'labels' => ['name' => __( 'Roofing Types' ), 'singular_name' => __( 'Roofing Type')],
      'public' => true,
      'has_archive' => true,
      'show_ui' => true,
      'menu_icon' => 'dashicons-admin-home',
      'query_var' => true,
      'capability_type' => 'post',
      'hierarchical' => true,
      'supports' => [
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
      ],
      'rewrite' => ['slug' => 'roofingtypes'],
    ]
  );
}
add_action('init', 'roofingtypes_create_post_type');

//
// Add link to Roofing Types on Plugins page.
//
function add_roofingtypes_link($links, $file) {
  static $this_plugin;
  if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

  if ($file == $this_plugin){
    http://localhost/wp-admin/edit.php?post_type=roofingtypes
  $settings_link = '<a href="/wp-admin/edit.php?post_type=roofingtypes">Roofing Types</a>';
  array_unshift($links, $settings_link);
  }
  return $links;
}
add_filter('plugin_action_links', 'add_roofingtypes_link', 10, 2 );

//
// Create a meta box in the Admin for link url.
//
function roofingtypes_add_custom_box() {
  global $current_screen;
  add_meta_box(
      'rft_link',
      'Roofing Type Settings',
      'roofingtypes_meta_box_html',
      ['roofingtypes']
  );
}
add_action('add_meta_boxes', 'roofingtypes_add_custom_box');

// HTML for the meta box.
function roofingtypes_meta_box_html($post) {
  $link = get_post_meta($post->ID, 'roofingtypes_link', true);
  ?>

  <label for="roofingtypes-link-url">Link URL</label>
  <br/>
  <input id="roofingtypes-link-url" name="roofingtypes-link-url" type="text" class="roofingtypes-input" placeholder="http://..." value="<?php echo $link; ?>" />

  <br/><br/>
<?php
}

// Save the meta box entries.
function roofingtypes_save_postdata($post_id) {
  if (array_key_exists('roofingtypes-link-url', $_POST)) {
    update_post_meta($post_id, 'roofingtypes_link', $_POST['roofingtypes-link-url']);
  }
}
add_action('save_post', 'roofingtypes_save_postdata');

// Add columns to the screen options of the admin index page.
function roofingtypes_columns_head($defaults) {
  $defaults['roofingtypes_order'] = 'Order';
  $defaults['featured_image'] = 'Featured Image';
  $defaults['roofingtypes_link'] = 'Link';
  return $defaults;
}

// Featured image HTML.
function roofingtypes_columns_content($column_name, $post_ID) {
  if ($column_name == 'roofingtypes_order') {
    $post = get_post($post_ID);
    echo $post->menu_order;
  }

  if ($column_name == 'featured_image') {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');

    echo '<img style="width: 150px;" src="' . $post_thumbnail_img[0] . '" />';
  }

  if ($column_name == 'roofingtypes_link') {
    $link = get_post_meta($post_ID, 'roofingtypes_link', true);
    $parts = explode('/', $link);
    $last = end($parts);

    if (!isset($last) || empty($last) || $last == "\n") {
      $sl = count($parts) - 2;
      $last = $parts[count($parts) - 2];
    }

    echo '<a href="'. $link .'">'. $last .'</a>';
  }
}
add_filter('manage_roofingtypes_posts_columns', 'roofingtypes_columns_head', 10);
add_action('manage_roofingtypes_posts_custom_column', 'roofingtypes_columns_content', 10, 2);

//
// Allow do_action for embedding in the theme.
//
function roofingtypes_action() {
  include(__DIR__ .'/template.php');
}
add_action( 'roofingtypes', 'roofingtypes_action', 10, 1 );
