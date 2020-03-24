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
            case 'multiple_fields':
                $this->multiple_fields( $box, $post->ID );
            break;
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
            case 'radio':
                $this->switch( $box, $post->ID );
            break;
            case 'customer_dropdown':
                $this->customer_dropdown( $box, $post->ID );
            break;
            case 'appointment_dropdown':
                $this->appointment_dropdown( $box, $post_id );
            break; 
        }
    }
    /**
    * Service Details
    * @since: 1.0
    */
    private function multiple_fields( $box, $post_id )
    {
        $post_meta  =     get_post_meta( $post_id, $box['id'], true );
        $fields     =     $box['args']['fields'];

        if(empty($post_meta)){
            $post_meta = array();
            foreach($fields as $field){
                $id  =  $this->meta_prefix.$field['id'];
                $post_meta[$id] = NULL;
            }

        }
        foreach($fields as $field){

            $field_id   =  $this->meta_prefix.$field['id'];
            $box['id']  =  $field_id;
            $box['args']['desc']    =  $field['desc'];
            $box['value'] = $post_meta[$field_id];

            switch( $field['type'])
            {
                case 'textfield':
                    $this->textfield( $box, $post_id );
                break;
                case 'checkbox':
                    $this->checkbox($box, $post_id );
                break;
                case 'dropdown':
                    $this->columnDropdown( $box, $post_id );
                break;
                case 'numeric':
                    $this->numeric( $box, $post_id );
                break;
                case 'radio':
                    $this->switch( $box, $post_id );
                break;
                case 'customer_dropdown':
                    $this->customer_dropdown( $box, $post_id );
                break;
                case 'appointment_dropdown':
                    $this->appointment_dropdown( $box, $post_id );
                break;   
            }
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
            printf(
                   '<label>%s: </label><input type="checkbox" name="%s" %s /> <small>%s</small><br/>',
                   $box['title'],
                   $box['id'],
                   checked( 1, $post_meta, false ),
                   $box['args']['desc']
               );
           }

           
    private function columnDropdown($box, $post_id){
            $post_meta = get_post_meta( $post_id, "_".$box['id'], true );
            $blogusers = get_users([ 'role__in' => [ 'author', 'subscriber' ] ] );
            echo '<select name="'.$box['id'].'[]" id="'.$box['id'].'" multiple="multiple">';  
            foreach ($blogusers as $user) { 
                echo '<option', $post_meta == $user->display_name ? ' selected="selected"' : '', '>'.$user->display_name.'</option>';   
                }  
                echo '</select>'; 
            }

    private function customer_dropdown($box, $post_id){
            $blogusers = get_users( array( 'fields' => array( 'display_name' ) ) );
            echo '<select name="'.$box['id'].'[]" id="'.$box['id'].'">';  
            foreach ($blogusers as $user) { 
                echo '<option>'.$user->display_name.'</option>';   
                }  
                echo '</select>'; 
                echo '<label><input type="button" id="'.$box['id'].'" class="js-toggle" name="_sbprok_create_new" value="Create New" /></label> <br/><small></small><br/>';
                echo '<div class ="hidden_fields" style="display:none;"> <label><input type="text" id="_sbprok_first_name" name="_sbprok_first_name" value=""> <br><small>First Name</small><br></label>
                <label ><input type="text" id="_sbprok_last_name" name="_sbprok_last_name" value=""> <br><small>Last Name</small><br></label>
                <label ><input type="text" id="_sbprok_email" name="_sbprok_email" value=""> <br><small>Email</small><br></label>
                <label ><input type="text" id="_sbprok_phone" name="_sbprok_phone" value=""> <br><small>Phone</small><br></label>
                <label ><input type="text" id="_sbprok_address" name="_sbprok_address" value=""> <br><small>Address</small><br></label>
                </div>';
    } 

    private function appointment_dropdown($box, $post_id){
            if($box['id'] == "_sbprok_services"){
                $post_meta = get_post_meta( $post_id,$box['id'], true );
                $args = array(
                    'post_type'=> 'sbprok_services',
                    'order'    => 'ASC'
                    );              
                
                $the_query = new WP_Query( $args );
                echo '<select name="'.$box['id'].'[]" id="'.$box['id'].'">';  
                if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                       <option> <?php the_title(); ?></option>    
                     <?php  endwhile; 
                endif;  
                echo '</select>';  
            }else{
                $post_meta = get_post_meta( $post_id, $box['id'], true );
                $args = array(
                    'role' => 'administrator',
                    'orderby' => 'user_nicename',
                    'order' => 'ASC'
                 ); 
                $employees = get_users($args);
                echo '<select name="'.$box['id'].'[]" id="'.$box['id'].'">';  
                foreach ($employees as $employee) { 
                    echo '<option>'.$employee->display_name.'</option>';   
                    }  
                    echo '</select>'; 
            }   
    } 


    private function switch($box, $post_id){
            $post_meta = get_post_meta( $post_id, "_".$box['id'], true );
            printf(
                rwmb_the_value( $field_id )
                );
    }
    
   
    private function meta_value($box, $post_id){
        if(array_key_exists("value", $box)){
            $post_meta = $box['value'];
        } else {
            $post_meta = get_post_meta( $post_id, $box['id'], true );
        }
        return $post_meta;
    }
    

}