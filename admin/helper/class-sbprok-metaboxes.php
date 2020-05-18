<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 * 
 * @package    Sbprok
 * @subpackage Sbprok/admin/helper
 */

class Sbprok_Metaboxes {
    private $boxes;
	private $action;
    private $nouce;
    private $meta_prefix = "_";
    private $time_slots = array(
        "01:00",
        "02:00",
        "03:00",
        "04:00",
        "05:00",
        "06:00",
        "07:00",
        "08:00",
        "09:00",
        "10:00",
        "11:00",
        "12:00",
    );
    
    public function create_meta( $args, $action, $nouce )
    {
        $this->boxes  = $args;
		$this->action = $action;
		$this->nouce  = $nouce;
        $this->add_metabox();
    }

    public function add_metabox()
    {
        $prefix = $this->meta_prefix;
        foreach( $this->boxes as $box )
            add_meta_box(
                $prefix.$box['id'], 
                $box['title'], 
                array( $this, 'mb_callback' ), 
                $box['post_type'], 
                isset( $box['context'] ) ? $box['context'] : 'normal', 
                isset( $box['priority'] ) ? $box['priority'] : 'default', 
                $box['args']
            );
    }

     /**
    * Callback function, uses helper function to print each meta box
    * @since: 1.0
    */
    public function mb_callback( $post, $box )
    {
		wp_nonce_field( $this->action, $this->nouce );
		
        switch( $box['args']['field'] )
        {
            case 'textfield':
                $this->textfield( $box, $post->ID );
            break;
            case 'checkbox':
                $this->checkbox($box, $post->ID );
            break;
            case 'dropdown':
                $this->columnDropdown( $box, $post->ID );
            break;
			case 'numeric':
                $this->numeric( $box, $post->ID );
            break;
            case 'service_details':
                $this->service_details( $box, $post->ID );
            break;
            case 'booking_schedule':
            $this->booking_schedule( $box, $post->ID );
            break;
            case 'customer_selection':
            $this->customer_selection( $box, $post->ID );
            break;
            case 'service_selection':
            $this->service_selection( $box, $post->ID );
            break;
            case 'service_cat_selection':
            $this->service_cat_selection( $box, $post->ID );
            break;
            case 'employee_selection':
            $this->employee_selection( $box, $post->ID );
            break;
            case 'employees_selection':
            $this->employees_selection( $box, $post->ID );
            break;
        }
    }
    /**
    * Text box field
    * @since: 1.0
    */
    private function textfield( $box, $post_id )
    {
        $post_meta = $this->meta_value($box, $post_id);
        printf(
            '<label><input type="text" id="'.$box['id'].'" name="%s" value="%s" /></label> <br/><small>%s</small><br/>',
            $box['id'],
            $post_meta,
            $box['args']['desc']
        );
    }
    /**
    * Numeric
    * @since: 1.0
    */
    private function numeric($box, $post_id){
       
        $post_meta = $this->meta_value($box, $post_id);
        printf(
            '<label><input type="number" name="%s" value="%s" /></label> <br/><small>%s</small><br/>',
                $box['id'],
                $post_meta,
                $box['args']['desc']
        );
    }

    /**
    * CheckBox
    * @since: 1.0
    */
    private function checkbox($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
       // print_r($post_meta);
            printf(
                   '<label>%s: </label><br/><input type="checkbox" name="%s" %s /> <small>%s</small><br/>',
                   $box['title'],
                   $box['id'],
                   checked( 1, $post_meta, false ),
                   $box['args']['desc']
               );
    }
    /**
    * Service details
    * @since: 1.0
    */  
    private function service_details($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
        if(empty($post_meta)) {
            //set defaults
            $post_meta = array(
                '_price' => '',
                '_duration' => ''
            );
        }
        //print_r($post_meta);
        ?>
        <div class="sbprok-row">
            <div class="sbprok-col-4">
            <label><?php echo __('Price', 'sbprok') ?></label>
            <input class="sbprok-input" type="text" name="<?php echo $box['id'] ?>[_price]" value="<?php echo $post_meta['_price'] ?>" />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Duration', 'sbprok') ?></label>
            <select class="sbprok-select" name="<?php echo $box['id'] ?>[_duration]" id="<?php echo $box['id'] ?>[_duration]"><?php __("Select Duration", 'sbprok') ?>
            <?php foreach($this->time_slots as $slot) :?>
                    <option value="<?php echo $slot ?>" <?php echo ($post_meta["_duration"] == $slot ? "selected" : "")?>><?php echo $slot; ?></option>
            <?php endforeach; ?>
            </select>
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Maximum allowed person per booking', 'sbprok') ?></label>
            <input class="sbprok-input" type="number" name="<?php echo $box['id'] ?>[_max_capacity]" value="<?php echo $post_meta['_max_capacity'] ?>" />
            </div>
        </div>
        
        <?php   
    }
    /**
    * Service details
    * @since: 1.0
    */  
    private function booking_schedule($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);

        if(empty($post_meta)){
            $post_meta = array(
                '_date'=> '',
                '_time' => '',
                '_customer' => ''
            );
        }
        ?>
        <div class="sbprok-row">
            <div class="sbprok-col-4">
            <label><?php echo __('Date', 'sbprok') ?></label>
            <input class="sbprok-input datepic" data-sbprok="datepicker" type="text" value="<?php echo $post_meta['_date'] ?>" name="<?php echo $box['id'] ?>[_date]"  />
           <div class="errorMsg">
           </div>
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Time', 'sbprok') ?></label>
            <input class="sbprok-input time_pic" required="required" data-sbprok="timepicker" type="text" value="<?php echo $post_meta['_time'] ?>" name="<?php echo $box['id'] ?>[_time]"  />
            <div class="errorMsgtime">
           </div>
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Customer', 'sbprok') ?></label>
            <?php
                $blogusers = get_users( array( 
                    'fields' => array( 'display_name','id' ),
                    'role__in'     => array('sbprok_customer'),
                     )
                );
                echo '<select class="cst" data-sbprok="select2" name="'.$box['id'].'[_customer]" id="'.$post_meta['_customer'].'">';  
                echo '<option value="">Select Customer</option>';
                foreach ($blogusers as $user) { 
                    echo '<option value="'.$user->id.'" '.(in_array($user->id, $post_meta) ? "selected" : "").'>'.$user->display_name.'</option>';   
                }  
                echo '</select>'; 
            ?>
            </div>

        </div>
       <?php 
       
    }
     /**
    * Customer Selection
    * @since: 1.0
    */  
    private function customer_selection($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
       // print_r($post_meta);
        $blogusers = get_users( array( 
                    'fields' => array( 'display_name','id' ),
                    'role__in'     => array('sbprok_customer'),
                     )
                );
        echo '<select data-sbprok="select2" name="'.$box['id'].'" id="'.$box['id'].'">';  
        foreach ($blogusers as $user) { 
            echo '<option value="'.$user->id.'">'.$user->display_name.'</option>';   
        }  
        echo '</select>';  ?>
        <input type="button" id="sbprok-form-toggle" value="Create New" style="display:none">
       
         <div class="sbprok-row" id="sbprok-cust-form" style="display:none">
            
            <div class="sbprok-col-4">
            <label><?php echo __('First name', 'sbprok') ?></label>
            <input class="sbprok-input" type="text" name="_sbprok_fname"  />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Last name', 'sbprok') ?></label>
            <input class="sbprok-input"  type="text" name="_sbprok_lname"  />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Email', 'sbprok') ?></label>
            <input class="sbprok-input" type="email" name="_sbprok_email"  />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Phone', 'sbprok') ?></label>
            <input class="sbprok-input" type="text" name="_sbprok_phone"  />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Address', 'sbprok') ?></label>
            <input class="sbprok-input" type="text" name="_sbprok_address"  />
            </div>
            <input tppe="button" value="Save"/>
        </div>
        <?php
    }

    /**
    * Service employees selection
    * @since: 1.0
    */  
    private function employees_selection($box, $post_id){ 
        $post_meta = $this->meta_value($box, $post_id);
        //print_r($post_meta);
        $employees = get_users( array( 
            'fields' => array( 'display_name','id' ),
            'role__in'     => array('sbprok_employee'),
             )
        );
        echo '<select name="_sbprok_employees[]" data-sbprok="select2" name="'.$box['id'].'[]" id="'.$box['id'].'" multiple>';     
        foreach ($employees as $service) {    
            echo '<option value="'.$service->id.'" '.(in_array($service->id, $post_meta) ? "selected" : "").'>'.$service->display_name.'</option>';   
        }  
        echo '</select>';  
    }

    /**
    * Service category selection
    * @since: 1.0
    */  
    private function service_cat_selection($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
        $terms = get_terms(
            array(
                'taxonomy'   => 'sbprok_category',
                'hide_empty' => false,
            )
        );
        if ( ! empty( $terms ) && is_array( $terms ) ) {
            echo '<select data-sbprok="select2" class="sbprok_service_cat" name="'.$box['id'].'" id="'.$box['id'].'" >';
            echo '<option value="">Select Category</option>';  
            foreach ( $terms as $term ) { 
                echo '<option value="'.$term->term_id.'" '.($term->term_id == $post_meta ? "selected" : "").'>'.$term->name.'</option>'; 
            }
            echo '</select>';  
        }  
       
    }

     /**
    * Service selection
    * @since: 1.0
    */  
    private function service_selection($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
        //print_r($post_meta);
        $services = get_posts( array( 
            'numberposts' => -1,
            'post_type'   => 'sbprok_services'
         )
        );

        echo '<select  class="sbprok_srvice" data-sbprok="select2" name="'.$box['id'].'" id="'.$box['id'].'" >';
        echo '<option value="">Select Service</option>';     
        foreach ($services as $service) {    
            echo '<option value="'.$service->ID.'" '.($service->ID == $post_meta ? "selected" : "").'>'.$service->post_title.'</option>';   
        }  
        echo '</select>';  
    }
    /**
    * Employee selection for booking
    * @since: 1.0
    */  
    private function employee_selection($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
        //print_r($post_meta);
        $blogusers = get_users( array( 
            'fields' => array( 'display_name','id' ),
            'role__in'     => array('sbprok_employee'),
             )
        ); ?>
        <div class ="sbprok_employee">
    </div>
            <?php 
        echo '<select class="sbprok_employees"  data-sbprok="select2" name="'.$box['id'].'" id="'.$box['id'].'">';  
        echo "<option value=''>Select Employee</option>";
        foreach ($blogusers as $user) { 
            echo '<option value="'.$user->id.'" '.($user->id == $post_meta ? "selected" : "").'>'.$user->display_name.'</option>';   
        } 
        echo '</select>';
        ?>
        <style type="test/css" id="cssID">
        .calendar-container #footer1 {
            display: none !important;
        }
        #td-print-image-id{
            display: none;
        }
        #td-print-text-id{
            display: none;
        }

        </style>
        <?php
        echo '<div class="show_calendar">';
        echo '</div>';  
    }
    /**
    * get meta value 
    * @since: 1.0
    */  
    private function meta_value($box, $post_id){
        if(array_key_exists("value", $box)){
            $post_meta = $box['value'];
        } else {
            $post_meta = get_post_meta( $post_id, $box['id'], true );
        }
        return $post_meta;
    }
    function wporg_settings_section_cb()
{
    echo '<p>WPOrg Section Introduction.</p>';
}
 
// field content cb
function wporg_settings_field_cb()
{
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('wporg_setting_name');
    // output the field
    ?>
    <input type="text" name="wporg_setting_name" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <?php
}

}