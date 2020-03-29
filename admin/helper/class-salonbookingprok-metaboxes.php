<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 * 
 * @package    Salonbookingprok
 * @subpackage Salonbookingprok/admin/helper
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Salonbookingprok
 * @subpackage Salonbookingprok/admin
 * @author     kynet Web <contact@kynetweb.com>
 */
class Salonbookingprok_Metaboxes {
    private $boxes;
	private $action;
    private $nouce;
    private $meta_prefix = "_";
    
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
            case 'appointment_schedule':
            $this->appointment_schedule( $box, $post->ID );
            break;
            case 'customer_selection':
            $this->customer_selection( $box, $post->ID );
            break;
            case 'service_selection':
            $this->service_selection( $box, $post->ID );
            break;
            case 'employee_selection':
            $this->employee_selection( $box, $post->ID );
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
        print_r($post_meta);
            printf(
                   '<label>%s: </label><input type="checkbox" name="%s" %s /> <small>%s</small><br/>',
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
        print_r($post_meta);
        ?>
        <div class="sbprok-row">
            <div class="sbprok-col-4">
            <label><?php echo __('Price', 'salonbookingprok') ?></label>
            <input class="sbprok-input" type="text" name="<?php echo $box['id'] ?>[_price]" value="<?php echo $post_meta['_price'] ?>" />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Duration', 'salonbookingprok') ?></label>
            <input class="sbprok-input" type="text" name="<?php echo $box['id'] ?>[_duration]" value="<?php echo $post_meta['_duration'] ?>" />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Maximum allowed person per booking', 'salonbookingprok') ?></label>
            <input class="sbprok-input" type="number" name="<?php echo $box['id'] ?>[_max_capacity]" value="<?php echo $post_meta['_max_capacity'] ?>" />
            </div>
        </div>
        
        <?php   
    }
    /**
    * Service details
    * @since: 1.0
    */  
    private function appointment_schedule($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
        print_r($post_meta);
        ?>
        <div class="sbprok-row">
            <div class="sbprok-col-6">
            <label><?php echo __('Date', 'salonbookingprok') ?></label>
            <input class="sbprok-input" data-sbprok="datepicker" type="text" name="<?php echo $box['id'] ?>[_date]"  />
            </div>

            <div class="sbprok-col-6">
            <label><?php echo __('Time', 'salonbookingprok') ?></label>
            <input class="sbprok-input" data-sbprok="timepicker" type="text" name="<?php echo $box['id'] ?>[_time]"  />
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
        print_r($post_meta);
        $blogusers = get_users( array( 
                    'fields' => array( 'display_name','id' ),
                    'role__in'     => array('salonbookingprok_customer'),
                     )
                );
        echo '<select data-sbprok="select2" name="'.$box['id'].'" id="'.$box['id'].'">';  
        foreach ($blogusers as $user) { 
            echo '<option value="'.$user->id.'">'.$user->display_name.'</option>';   
        }  
        echo '</select>';  ?>
        <input type="button" id="sbprok-form-toggle" value="Create New">
       
         <div class="sbprok-row" id="sbprok-cust-form" style="display:none">
            
            <div class="sbprok-col-4">
            <label><?php echo __('First name', 'salonbookingprok') ?></label>
            <input class="sbprok-input" type="text" name="_sbprok_fname"  />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Last name', 'salonbookingprok') ?></label>
            <input class="sbprok-input"  type="text" name="_sbprok_lname"  />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Email', 'salonbookingprok') ?></label>
            <input class="sbprok-input" type="email" name="_sbprok_email"  />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Phone', 'salonbookingprok') ?></label>
            <input class="sbprok-input" type="text" name="_sbprok_phone"  />
            </div>

            <div class="sbprok-col-4">
            <label><?php echo __('Address', 'salonbookingprok') ?></label>
            <input class="sbprok-input" type="text" name="_sbprok_address"  />
            </div>
            <input tppe="button" value="Save"/>
        </div>
        <?php
    }
     /**
    * Service selection
    * @since: 1.0
    */  
    private function service_selection($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
        print_r($post_meta);
        $services = get_posts( array( 
                        'numberposts' => -1,
                        'post_type'   => 'sbprok_services'
                     )
                );
        echo '<select data-sbprok="select2" name="'.$box['id'].'[]" id="'.$box['id'].'" multiple>';     
        foreach ($services as $service) { 
            echo '<option value="'.$service->ID.'">'.$service->post_title.'</option>';   
        }  
        echo '</select>';  
    }
    /**
    * Employee details
    * @since: 1.0
    */  
    private function employee_selection($box, $post_id){
        $post_meta = $this->meta_value($box, $post_id);
        print_r($post_meta);
        $blogusers = get_users( array( 
            'fields' => array( 'display_name','id' ),
            'role__in'     => array('salonbookingprok_employee'),
             )
        );
        echo '<select  data-sbprok="select2" name="'.$box['id'].'" id="'.$box['id'].'">';  
        foreach ($blogusers as $user) { 
            echo '<option value="'.$user->id.'">'.$user->display_name.'</option>';   
        } 
        echo '</select>';  
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
    

}