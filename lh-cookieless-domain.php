<?php
/**
 * Plugin Name: LH Cookieless Domain
 * Plugin URI: https://lhero.org/portfolio/lh-cookieless-domain/
 * Description: Makes multisites rock by serving scripts from cookieless domain
 * Version: 1.02
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com/
 * Text Domain: lh_cookieless_domain
 * Domain Path: /languages
*/

// If this file is called directly, abandon ship.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if (!class_exists('LH_Cookieless_domain_plugin')) {


class LH_Cookieless_domain_plugin {

var $filename;
var $options;
var $url_field_name = 'lh_cookieless_domain-url';
var $path = 'lh-cookieless-domain/lh-cookieless-domain.php';
var $opt_name = 'lh_cookieless_domain-options';
var $namespace = 'lh_cookieless_domain';


private static $instance;


private function is_this_plugin_network_activated(){

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( is_plugin_active_for_network( $this->path ) ) {
    // Plugin is activated

return true;

} else  {


return false;


}

}

private function isValidURL($url){ 

return (bool)parse_url($url);
}

private function return_local_host(){
         
         $url = parse_url(get_site_url());
         
         return $url['host'];
         
     }
     
private function return_cookieless_host(){
         
         $url = parse_url($this->options[ $this->url_field_name ]);
         
         return $url['host'];
         
     }
     
private function build_url(array $parts) {
    return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') . 
        ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') . 
        (isset($parts['user']) ? "{$parts['user']}" : '') . 
        (isset($parts['pass']) ? ":{$parts['pass']}" : '') . 
        (isset($parts['user']) ? '@' : '') . 
        (isset($parts['host']) ? "{$parts['host']}" : '') . 
        (isset($parts['port']) ? ":{$parts['port']}" : '') . 
        (isset($parts['path']) ? "{$parts['path']}" : '') . 
        (isset($parts['query']) ? "?{$parts['query']}" : '') . 
        (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
}


  
  
public function get_plugin_options() {

if ($this->is_this_plugin_network_activated()){

$options = get_site_option($this->opt_name);

} else {



$options = get_option($this->opt_name);

}

return $options;

}
  
  

public function network_plugin_menu() {
add_submenu_page('settings.php', 'LH Cookieless Domain', 'Cookieless Domain', 'manage_options', $this->filename, array($this,"plugin_options"));


}



public function plugin_menu() {
add_options_page('LH Cookieless Domain', 'Cookieless Domain', 'manage_options', $this->filename, array($this,"plugin_options"));

}

public function plugin_options() {

if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

   
 // See if the user has posted us some information
    // If they did, the nonce will be set

	if( isset($_POST[ $this->namespace."-backend_nonce" ]) && wp_verify_nonce($_POST[ $this->namespace."-backend_nonce" ], $this->namespace."-backend_nonce" )) {


if (isset($_POST[$this->url_field_name]) and !empty($_POST[$this->url_field_name]) and $this->isValidURL($_POST[$this->url_field_name])){

$options[$this->url_field_name] = sanitize_text_field($_POST[$this->url_field_name]);


}
  
  
  
  
  
 if ($this->is_this_plugin_network_activated()){


if (update_site_option( $this->opt_name, $options )){

$this->options = get_site_option($this->opt_name);


?>
<div class="updated"><p><strong><?php _e('Settings saved', $this->namespace ); ?></strong></p></div>
<?php

} 


} else {

if (update_option( $this->opt_name, $options )){

$this->options = get_option($this->opt_name);


?>
<div class="updated"><p><strong><?php _e('Settings saved', $this->namespace ); ?></strong></p></div>
<?php

} 

}
  
  
  

}

// Now display the settings editing screen
include ('partials/option-settings.php');


}



public function move_domain( $src, $handle ){ 
        
$localhost = $this->return_local_host();

$cookielesshost = $this->return_cookieless_host();

if (($url = parse_url($src)) && isset($url['host'])){
    
$url['host'] = str_replace ( $localhost , $cookielesshost , $url['host'] );

return $this->build_url($url);
    
} else {

return $src; 

}

        
    } 
    
    
public function wp_get_attachment_url($src, $postid){
    
$localhost = $this->return_local_host();

$cookielesshost = $this->return_cookieless_host();

if (($url = parse_url($src)) && isset($url['host']) && !is_admin() && !is_feed()){
    
$url['host'] = str_replace ( $localhost , $cookielesshost , $url['host'] );

return $this->build_url($url);
    
} else {

return $src; 

}

    
    
}

public function add_hooks(){
    
add_filter( 'script_loader_src', array($this,'move_domain'), 99, 2 );
        
add_filter( 'style_loader_src', array($this,'move_domain'), 99, 2 );


//use cdn where possible
add_filter('wp_get_attachment_url', array($this, 'wp_get_attachment_url'), 10, 2 );
    
}

public function plugins_loaded(){
    
if ( $this->is_this_plugin_network_activated() ) {
add_action('network_admin_menu', array($this,"network_plugin_menu"));
} else {
add_action('admin_menu', array($this,"plugin_menu"));
}


//add on body open so that it only runs when needed
add_action( 'get_header', array($this,'add_hooks'));

    
}
    
    
    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }
    

public function __construct() {

$this->filename = plugin_basename( __FILE__ );
$this->options = $this->get_plugin_options();

//run our hooks on plugins loaded to as we may need checks       
add_action( 'plugins_loaded', array($this,'plugins_loaded'));

}

}

$lh_cookieless_domain_instance = LH_Cookieless_domain_plugin::get_instance();


}



?>