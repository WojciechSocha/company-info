<?php
/*
Plugin Name: Company Info Plugin
Description: Plugin for entering company contact info and for displaying it on page.
Author: Pracownie Inżynierskie Socha
Version: 1.0
Author URI: http://socha-studio.pl/
License: GPLv2 or later
*/

/*  
Copyright 2012 Pracownie Inżynierskie Socha (admin@socha-pi.pl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if(!function_exists('company_info_menu'))
	{
	function company_info_menu()
		{
		add_submenu_page('options-general.php', __( 'Company Info', 'company_info' ), __( 'Company Info', 'company_info' ), 'manage_options', "company_info.php", 'company_info_page');
		
		//call register settings function
		add_action( 'admin_init', 'company_info_data' );
		}
	}
	
// Register settings for Company Info page
if(!function_exists('company_info_data'))
	{
	function company_info_data()
		{
		global $company_info_data;

		$company_info_defaults = array(
			'full_name' => '',
			'suffix' => '',
			'tax_id' => '',
			'reg_id' => '',
			'street_address' => '',
			'post_code' => '',
			'city' => '',
			'geo_latitude' => '',
			'geo_longitude' => '',
			'phone1' => '',
			'phone2' => '',
			'phone3' => '',
			'fax1' => '',
			'fax2' => '',
			'email1' => '',
			'email2' => ''
		);
		if(!get_option('company_info_data'))
			add_option('company_info_data', $company_info_defaults, '', 'yes');

		$company_info_data=get_option('company_info_data');
		$company_info_data=array_merge($company_info_defaults, $company_info_data);
		update_option('company_info_data', $company_info_data);
		}
	}

// Add settings page in admin area
if(!function_exists('company_info_page'))
	{
	function company_info_page()
		{
		global $company_info_data;
		$error="";
		$message="";
		
		if (isset($_REQUEST['company_info_submit']) && check_admin_referer(plugin_basename(__FILE__), 'company_info_nonce_name'))
			{
			$company_info_submit['full_name']=$_REQUEST['full_name'];
			$company_info_submit['suffix']=$_REQUEST['suffix'];
			$company_info_submit['tax_id']=$_REQUEST['tax_id'];
			$company_info_submit['reg_id']=$_REQUEST['reg_id'];
			$company_info_submit['street_address']=$_REQUEST['street_address'];
			$company_info_submit['post_code']=$_REQUEST['post_code'];
			$company_info_submit['city']=$_REQUEST['city'];
			$company_info_submit['geo_latitude']=$_REQUEST['geo_latitude'];
			$company_info_submit['geo_longitude']=$_REQUEST['geo_longitude'];
			$company_info_submit['phone1']=$_REQUEST['phone1'];
			$company_info_submit['phone2']=$_REQUEST['phone2'];
			$company_info_submit['phone3']=$_REQUEST['phone3'];
			$company_info_submit['fax1']=$_REQUEST['fax1'];
			$company_info_submit['fax2']=$_REQUEST['fax2'];
			$company_info_submit['email1']=$_REQUEST['email1'];
			$company_info_submit['email2']=$_REQUEST['email2'];
			
			$company_info_data=array_merge($company_info_data, $company_info_submit);
			
			$is_email1_valid=preg_match("/^((?:[a-z0-9]+(?:[a-z0-9\-_\.]+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})[, ]*)+$/i", trim($company_info_submit['email1']));
			$is_email2_valid=preg_match("/^((?:[a-z0-9]+(?:[a-z0-9\-_\.]+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})[, ]*)+$/i", trim($company_info_submit['email2']));
			
			if ($company_info_submit['email1']!="" && !$is_email1_valid)
				$error.=__("Please enter valid e-mail #1.", 'company_info');

			if ($company_info_submit['email2']!="" && !$is_email2_valid)
				$error.=__("Please enter valid e-mail #2.", 'company_info');
				
			if (update_option('company_info_data', $company_info_data, '', 'yes'))
				$message.=__("Options saved.", 'company_info');

			wp_nonce_field( plugin_basename(__FILE__), 'company_info_nonce_name' );
			}
	
	// Display Company Info Page
	?>
		<div class="wrap">
		<div class="icon32 companyInfo" id="icon-options-general"></div>
		<h2><?php _e("Company Info", 'company_info'); ?></h2>
		<?php
			if ($error!='') echo '<div class="error">'.$error.'</div>';
			if ($message!='') echo '<div class="updated">'.$message.'</div>';
		?>
		<form method="post" action="admin.php?page=company_info.php">
			<table class="form-table">
				<tr><th colspan="2"><h3><?php _e("Basic information", 'company_info'); ?></h3></th></tr>
				<tr valign="top">
					<th scope="row"><label for="full_name"><?php _e("Full company name:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="full_name" name="full_name" value="<?php echo $company_info_data['full_name']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="suffix"><?php _e("Suffix for the company name (slogan fo example)", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="suffix" name="suffix" value="<?php echo $company_info_data['suffix']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="tax_id"><?php _e("Tax ID:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="tax_id" name="tax_id" value="<?php echo $company_info_data['tax_id']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="reg_id"><?php _e("Registration ID:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="reg_id" name="reg_id" value="<?php echo $company_info_data['reg_id']; ?>" />
					</td>
				</tr>
				<tr><th colspan="2"><h3><?php _e("Address information", 'company_info'); ?></h3></th></tr>
				<tr valign="top">
					<th scope="row"><label for="street_address"><?php _e("Street address:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="street_address" name="street_address" value="<?php echo $company_info_data['street_address']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="post_code"><?php _e("Post code:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="post_code" name="post_code" value="<?php echo $company_info_data['post_code']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="city"><?php _e("City:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="city" name="city" value="<?php echo $company_info_data['city']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="geo_latitude"><?php _e("Geographical latitude:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="geo_latitude" name="geo_latitude" value="<?php echo $company_info_data['geo_latitude']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="geo_longitude"><?php _e("Geographical longitude:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="geo_longitude" name="geo_longitude" value="<?php echo $company_info_data['geo_longitude']; ?>" />
					</td>
				</tr>
				<tr><th colspan="2"><h3><?php _e("Contact information", 'company_info'); ?></h3></th></tr>
				<tr valign="top">
					<th scope="row"><label for="phone1"><?php _e("Phone #1:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="phone1" name="phone1" value="<?php echo $company_info_data['phone1']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="phone2"><?php _e("Phone #2:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="phone2" name="phone2" value="<?php echo $company_info_data['phone2']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="phone3"><?php _e("Phone #3:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="phone3" name="phone3" value="<?php echo $company_info_data['phone3']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fax1"><?php _e("Fax #1:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="fax1" name="fax1" value="<?php echo $company_info_data['fax1']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fax2"><?php _e("Fax #2:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="fax2" name="fax2" value="<?php echo $company_info_data['fax2']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="email1"><?php _e("E-mail #1:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="email1" name="email1" value="<?php echo $company_info_data['email1']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="email2"><?php _e("E-mail #2:", 'company_info'); ?></label></th>
					<td>
						<input type="text" id="email2" name="email2" value="<?php echo $company_info_data['email2']; ?>" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="company_info_submit" value="submit" />
			<?php submit_button(); ?>
			<?php wp_nonce_field(plugin_basename(__FILE__), 'company_info_nonce_name'); ?>
		</form>
	<?php
		}
	}

// Use '_plugin_init' to add i18n
if (!function_exists('company_info_plugin_init'))
	{
	function company_info_plugin_init()
		{
		if (!session_id())
			session_start();
		load_plugin_textdomain('company_info', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
		}
	}
	
// Function generating Setting link in plugin's actions
function company_info_plugin_action_links($links, $file)
	{
	//Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if (!$this_plugin) $this_plugin=plugin_basename(__FILE__);

	if ($file==$this_plugin)
		{
		$settings_link='<a href="admin.php?page=company_info.php">' . __('Settings', 'company_info') . '</a>';
		array_unshift($links, $settings_link);
		}
	return $links;
	}
	
function company_info_register_plugin_links($links, $file)
	{
	//Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if (!$this_plugin) $this_plugin=plugin_basename(__FILE__);
	
	if ($file==$this_plugin)
		{
		$links[] = '<a href="admin.php?page=company_info.php">' . __('Settings','company_info') . '</a>';
		$links[] = '<a href="Mailto:admin@socha-pi.pl">' . __('Support','company_info') . '</a>';
		}
	return $links;
	}
	
// Function to generate Company Info with microformats
if(!function_exists('company_info_shortcode'))
	{
	function company_info_shortcode($atts)
		{
		$options=shortcode_atts(array(
			'company_name' => true,
			'address' => true,
			'phones' => true,
			'fax' => true,
			'email' => true,
			'geo' => false
		), $atts);

		$content.='<div class="companyInfo" itemtype="http://data-vocabulary.org/Organization" itemscope="">'."\n";
		$company_info=get_option('company_info_data');

		if ($options['company_name'])
			{
			if (isset($company_info['full_name']) && $company_info['full_name']!='')
				$content.="\t".'<h3 itemprop="name">'.$company_info['full_name'].' '.$company_info['suffix'].'</h3>'."\n";	
				
			if (isset($company_info['tax_id']) && $company_info['tax_id']!='')
				$content.="\t".'<dt>NIP:</dt> <dd>'.$company_info['tax_id'].'</dd><br />'."\n";
				
			if (isset($company_info['reg_id']) && $company_info['reg_id']!='')
				$content.="\t".'<dt>REGON:</dt> <dd>'.$company_info['reg_id'].'</dd>'."\n";
			}

		if ($options['address'])
			{
			if (isset($company_info['street_address']) && $company_info['street_address']!='') $has_street_address=true;
			if (isset($company_info['post_code']) && $company_info['post_code']!='') $has_post_code=true;
			if (isset($company_info['city']) && $company_info['city']!='') $has_city=true;

			if ($has_street_address || $has_post_code || $has_city)
				{
				$content.="\t".'<div class="address" itemtype="http://data-vocabulary.org/Address" itemscope="" itemprop="address" title="'.__( "Address", 'company_info' ).'">'."\n";
				if ($has_street_address) 
					$content.="\t\t".'<span itemprop="street-address" class="street-address">'.$company_info['street_address'].'</span>'."\n";
					
				if ($has_post_code)
					$content.="\t\t".'<span itemprop="postal-code" class="postal-code">'.$company_info['post_code'].'</span>';
					
				if ($has_city)
					$content.='<span itemprop="locality" class="locality">'.$company_info['city'].'</span>'."\n";
				$content.="\t".'</div>'."\n";
				}
			}
			
		if ($options['phones'])
			{
			if (isset($company_info['phone1']) && $company_info['phone1']!='') $has_phone1=true;
			if (isset($company_info['phone2']) && $company_info['phone2']!='') $has_phone2=true;
			if (isset($company_info['phone3']) && $company_info['phone3']!='') $has_phone3=true;

			if ($has_phone1 || $has_phone2 || $has_phone3)
				{
				$content.="\t".'<div class="phones" title="'.__( "Phones", 'company_info' ).'">'."\n";
				
				if ($has_phone1)
					$content.="\t\t".'<span itemprop="tel" class="phone">'.$company_info['phone1'].'</span>'."\n";
					
				if ($has_phone2)
					$content.="\t\t".'<span itemprop="tel" class="phone">'.$company_info['phone2'].'</span>'."\n";

				if ($has_phone3)
					$content.="\t\t".'<span itemprop="tel" class="phone">'.$company_info['phone3'].'</span>'."\n";
				
				$content.="\t".'</div>'."\n";
				}
			}

		if ($options['fax'])
			{
			if (isset($company_info['fax1']) && $company_info['fax1']!='') $has_fax1=true;
			if (isset($company_info['fax2']) && $company_info['fax2']!='') $has_fax2=true;
		
			if ($has_fax1 || $has_fax2)
				{
				$content.="\t".'<div class="faxs" title="'.__( "Fax", 'company_info' ).'">'."\n";
				
				if ($has_fax1)
					$content.="\t\t".'<span itemprop="fax-number" class="fax">'.$company_info['fax1'].'</span>'."\n";
					
				if ($has_fax2)
					$content.="\t\t".'<span itemprop="fax-number" class="fax">'.$company_info['fax2'].'</span>'."\n";

				$content.="\t".'</div>'."\n";
				}
			}

		if ($options['email'])
			{
			if (isset($company_info['email1']) && $company_info['email1']!='') $has_email1=true;
			if (isset($company_info['email2']) && $company_info['email2']!='') $has_email2=true;
		
			if ($has_email1 || $has_email2)
				{
				$content.="\t".'<div class="emails" title="'.__( "E-mail", 'company_info' ).'">'."\n";
				
				if ($has_email1)
					$content.="\t\t".'<a href="mailto:'.$company_info['email1'].'"><span itemprop="email" class="email">'.$company_info['email1'].'</span></a>'."\n";
					
				if ($has_email2)
					$content.="\t\t".'<a href="mailto:'.$company_info['email2'].'"><span itemprop="email" class="email">'.$company_info['email2'].'</span></a>'."\n";

				$content.="\t".'</div>'."\n";
				}
			}
		
		if ($options['geo'])
			{
			if (isset($company_info['geo_latitude']) && $company_info['geo_latitude']!='' && isset($company_info['geo_longitude']) && $company_info['geo_longitude']!='')
				{
				$content.="\t".'<div class="geo" title="'.__( "Geographical", 'company_info' ).'" itemprop="geo" itemscope
			itemtype="http://data-vocabulary.org/Geo">'."\n";
				$content.="\t\t".'<span class="latitude" title="'.__( "Latitude", 'company_info' ).'>'.$company_info['geo_latitude'].'</span>'."\n";
				$content.="\t\t".'<meta itemprop="latitude" content="'.$company_info['geo_latitude'].'" />';
				$content.="\t\t".'<span class="longitude" title="'.__( "Longitude", 'company_info' ).'>'.$company_info['geo_longitude'].'</span>'."\n";
				$content.="\t\t".'<meta itemprop="longitude" content="'.$company_info['geo_longitude'].'" />';
				}
			}
			
		$content.='</div>'."\n";
		return $content;
		}
	}

// Inits i18n
add_action('init', 'company_info_plugin_init');

// Adds Settings link to the plugin action page
add_filter('plugin_action_links', 'company_info_plugin_action_links',10,2);

add_filter('plugin_row_meta', 'company_info_register_plugin_links',10,2);

// Register shortcode to display company info
add_shortcode('company_info', 'company_info_shortcode');

// Add menu option for settings page
add_action( 'admin_menu', 'company_info_menu' );
?>