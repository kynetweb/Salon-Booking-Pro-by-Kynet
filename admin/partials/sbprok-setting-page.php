
<div class="wrap">  
        <div id="icon-themes" class="icon32"></div>  
        <h2>Settings</h2>  
        <?php settings_errors(); ?>  
		<?php  
            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';  
        ?>  
		<h2 class="nav-tab-wrapper">  
            <a href="?page=saloon-setting-options&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>  
            <a href="?page=saloon-setting-options&tab=display" class="nav-tab <?php echo $active_tab == 'display' ? 'nav-tab-active' : ''; ?>">Display</a>  
			<a href="?page=saloon-setting-options&tab=availability" class="nav-tab <?php echo $active_tab == 'availability' ? 'nav-tab-active' : ''; ?>">Availability</a>
		</h2>  
		<form method="post" action="options.php">  
			<?php 
            if( $active_tab == 'general' ) {  // general tab function.
                settings_fields( 'sbprok_general' );
           		do_settings_sections( 'sbprok_general' );
            } else if( $active_tab == 'display' ) { // display tab function.
                settings_fields( 'sbprok_display' );
                do_settings_sections( 'sbprok_display' ); 
			}
			else if( $active_tab == 'availability' ) { //availability
				settings_fields( 'sbprok_availbility' );
				do_settings_sections( 'sbprok_availbility' ); 
			}
            ?>             
            <?php submit_button(); ?>  
        </form> 
		</div> 
<?php 

/** General tab fields callback functions */

/**
* company_name_callback
* @since: 1.0
*/
function company_name_callback() {
    $option = get_option( 'sbprok_general' );
    if(isset($option['company_name'])){
        $company_name   = esc_attr( $option['company_name'] );
    }
    else{$company_name = "";}
    echo "<input type='text' name='sbprok_general[company_name]' value='$company_name' />";
}

/**
* company_logo_callback
* @since: 1.0
*/
function company_logo_callback(){
    $option = get_option( 'sbprok_general' );
    if(isset($option['company_logo_id'])){
    $company_logo   = esc_attr( $option['company_logo_id'] );
    }else{$company_logo = "";}
    ?>
    <img id='image-preview' src='<?php echo wp_get_attachment_url($company_logo); ?>' height='50'>
    <input id="upload_image_button" class ="sbprok-employee-form-button" type="button" class="button" value = "<?php _e( 'Upload image' ); ?>" />
    <input type='hidden' name='sbprok_general[company_logo_id]' id='image_attachment_id' value ='<?php echo $company_logo; ?>'><br></br><?php
}

/**
* company_email_callback
* @since: 1.0
*/
function company_email_callback(){
    $option = get_option( 'sbprok_general' );
    if(isset($option['company_email'])){
        $company_email   = esc_attr( $option['company_email'] );
    }else{$company_email = "";}
    echo "<input type='email' name='sbprok_general[company_email]' value='$company_email' />";
}

/**
* company_phn_callback
* @since: 1.0
*/
function company_phn_callback(){
    $option = get_option( 'sbprok_general' );
    if(isset($option['company_phn'])){
        $company_phn   = esc_attr( $option['company_phn'] );
    }else{$company_phn = "";}
    echo "<input type='text' name='sbprok_general[company_phn]' value='$company_phn' />";
}

/**
* company_address_callback
* @since: 1.0
*/
function company_address_callback(){
    $option = get_option( 'sbprok_general' );
    if(isset($option['company_phn'])){
        $company_address   = esc_attr( $option['company_address'] );
    }else{$company_address = "";}
    
    echo "<textarea name='sbprok_general[company_address]' />" .$company_address. "</textarea>";
    
}

/** availability tab fields callback functions */
function monday_callback(){
    $option = get_option( 'sbprok_availbility' );
    if(isset($option['monday']) && $option['monday']){
        $checked = 'checked';
    }else{$checked = "";}
    echo "<input $checked type='checkbox' name='sbprok_availbility[monday]' />";
}

function tuesday_callback(){
    $option = get_option( 'sbprok_availbility' );
    if(isset($option['tuesday']) && $option['tuesday']){
        $checked = 'checked';
}else{$checked = "";}
    echo "<input $checked type='checkbox' name='sbprok_availbility[tuesday]'  />";
}

function wednesday_callback(){
    $option = get_option( 'sbprok_availbility' );
    if(isset($option['wednesday']) && $option['wednesday']){
        $checked = 'checked';
}else{$checked = "";}
    echo "<input $checked type='checkbox' name='sbprok_availbility[wednesday]'/>";
}

function thursday_callback(){
    $option = get_option( 'sbprok_availbility' );
    if(isset($option['thursday']) && $option['thursday']){
        $checked = 'checked';
}else{$checked = "";}
    echo "<input $checked type='checkbox' name='sbprok_availbility[thursday]'  />";
}

function friday_callback(){
    $option = get_option( 'sbprok_availbility' );
    if(isset($option['friday']) && $option['friday']){
        $checked = 'checked';
}else{$checked = "";}
    echo "<input $checked type='checkbox' name='sbprok_availbility[friday]' />";
}

function saturday_callback(){
    $option = get_option( 'sbprok_availbility' );
    if(isset($option['saturday']) && $option['saturday']){
        $checked = 'checked';
}else{$checked = "";}
    echo "<input $checked type='checkbox' name='sbprok_availbility[saturday]'  />";
}

function sunday_callback(){
    $option = get_option( 'sbprok_availbility' );
    if(isset($option['sunday']) && $option['sunday']){
        $checked = 'checked';
    }else{$checked = "";}
    echo "<input $checked type='checkbox' name='sbprok_availbility[sunday]'  />";
}

?>