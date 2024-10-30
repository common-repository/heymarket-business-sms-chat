<?php
/**
 * Plugin Name:     Heymarket Business SMS Chat
 * Plugin URI:      https://heymarket.zendesk.com/hc/en-us/articles/115003173711-Heymarket-Website-Widget
 * Description:     This plugin allows you to insert the Heymarket web widget into your Wordpress site.
 * Author:          Heymarket
 * Author URI:      https://www.heymarket.com
 * License:         GPL2
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Version:         1.0.2
 * 
 * Heymarket Business SMS Chat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * Heymarket Business SMS Chat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Heymarket Business SMS Chat. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 *
 * @package         Heymarket_Widget
 */

function heymk_widget() {
  $options = get_option( 'heymk_options' );
  ?>
  <script type='text/javascript'>
  (function(_a,id,a,_) {
    function Modal(){
      var h = a.createElement('script'); h.type = 'text/javascript'; h.async = true;
      var e = id; h.src = e+(e.indexOf("?")>=0?"&":"?")+'ref='+_;
      var y = a.getElementsByTagName('script')[0]; y.parentNode.insertBefore(h, y);
      h.onload = h.onreadystatechange = function() {
        var r = this.readyState; if (r && r != 'complete' && r != 'loaded') return;
        try { HeymarketWidget.construct(_); } catch (e) {}
      };
    };
    (_a.attachEvent ? _a.attachEvent('onload', Modal) : _a.addEventListener('load', Modal, false));
  })(window,'https://widget.heymarket.com/heymk-widget.bundle.js',document,{
    CLIENT_ID: "<?php echo $options['heymk_field_cid'] ?>"
  });
  </script>
  <?php
}
add_action( 'wp_head', 'heymk_widget', 10 );

function heymk_settings_init() {
  register_setting( 'heymk', 'heymk_options' );
  add_settings_section(
    'heymk_section_developers',
    __( 'Heymarket Web Widget', 'heymk' ),
    'heymk_section_developers_cb',
    'heymk'
  );
  add_settings_field(
    'heymk_field_cid',
    __( 'Client ID', 'heymk' ),
    'heymk_field_cid_cb',
    'heymk',
    'heymk_section_developers',
    [
      'label_for' => 'heymk_field_cid',
      'class' => 'heymk_row',
      'heymk_custom_data' => 'custom',
    ]
  );
}
  
add_action( 'admin_init', 'heymk_settings_init' );
function heymk_section_developers_cb( $args ) {?>
  <p id="<?php echo esc_attr( $args['id'] ); ?>">
  <?php esc_html_e( 'Please enter the Heymarket Widget Client ID. You can find this on the Widget setup page in the Heymarket app:', 'heymk' ); ?> 
  <a href="https://app.heymarket.com/team/integrations/widget" target="_blank" alt="Heymarket Web App">Heymarket Web App</a></p><?php
}
  
function heymk_field_cid_cb( $args ) {
  $options = get_option( 'heymk_options' );?>
  <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" 
  data-custom="<?php echo esc_attr( $args['heymk_custom_data'] ); ?>" 
  name="heymk_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
  value="<?php echo esc_attr($options['heymk_field_cid']) ?>"
  style="width: 25em;"
  /><?php
}
  
function heymk_options_page() {
  add_menu_page(
  'Heymarket',
  'Heymarket',
  'manage_options',
  'heymk',
  'heymk_options_page_html'
  );
}
  
add_action( 'admin_menu', 'heymk_options_page' );

function heymk_options_page_html() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  
  if ( isset( $_GET['settings-updated'] ) ) {
    add_settings_error( 'heymk_messages', 'heymk_message', __( 'Settings Saved', 'heymk' ), 'updated' );
  }
  
  settings_errors( 'heymk_messages' );?>
  <div class="wrap">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <form action="options.php" method="post">
  <?php
  settings_fields( 'heymk' );
  do_settings_sections( 'heymk' );
  submit_button( 'Save Settings' );
  ?>
  </form>
  </div>
  <?php
}