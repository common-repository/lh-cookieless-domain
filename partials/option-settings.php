<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<form name="<?php echo $this->hidden_field_name; ?>-backend_form" method="post" action="">
<?php wp_nonce_field( $this->namespace.'-backend_nonce', $this->namespace.'-backend_nonce' ); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="<?php echo $this->url_field_name; ?>"><?php _e("Cookieless URL;", $this->namespace ); ?></label></th>
<td><input type="url" name="<?php echo $this->url_field_name; ?>" id="<?php echo $this->url_field_name; ?>" value="<?php echo $this->options[ $this->url_field_name ]; ?>" size="30" /></td>
</tr>
</table>
<?php submit_button( 'Save Changes' ); ?>
</form>