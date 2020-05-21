
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