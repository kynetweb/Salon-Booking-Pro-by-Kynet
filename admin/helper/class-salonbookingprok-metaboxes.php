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
    
    public function create_meta( $args, $action, $nouce )
    {
        $this->boxes  = $args;
		$this->action = $action;
		$this->nouce  = $nouce;
        $this->add_metabox();
    }

    public function add_metabox()
    {
        foreach( $this->boxes as $box )
            add_meta_box(
 
                $box['id'], 
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
                $this->checkbox(
 $box, $post->ID );
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
            case 'layouts':
            $this->teamlayouts( $box, $post->ID );
            break;
        }
    }

    /**
    * Text box field
    * @since: 1.0
    */
    private function textfield( $box, $post_id )
    {
        $post_meta = get_post_meta( $post_id, "_".$box['id'], true );
        printf(
            '<label><input type="text" name="%s" value="%s" /></label> <br/><small>%s</small><br/>',
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
            $post_meta = get_post_meta( $post_id, "_".$box['id'], true );
            printf(
                   '<label>%s: </label><input type="checkbox" name="%s" %s /> <small>%s</small><br/>',
                   $box['title'],
                   $box['id'],
                   checked( 1, $post_meta, false ),
                   $box['args']['desc']
               );
           }
           
           
    private function columnDropdown($box, $post_id){
            $meta_id   =   "_".$box['id'];
            $post_meta = get_post_meta( $post_id, "_".$box['id'], true );
            $blogusers = get_users( array( 'fields' => array( 'display_name' ) ) );
            echo '<select name="'.$meta_id.'[]" id="'.$meta_id.'" multiple="multiple">';  
            foreach ($blogusers as $user) { 
                echo '<option', $post_meta == $user->display_name ? ' selected="selected"' : '', '>'.$user->display_name.'</option>';   
                }  
                echo '</select>'; 
            }

    private function numeric($box, $post_id){
            $post_meta = get_post_meta( $post_id, "_".$box['id'], true );
            printf(
                '<label><input type="number" name="%s" value="%s" /></label> <br/><small>%s</small><br/>',
                    $box['id'],
                    $post_meta,
                    $box['args']['desc']
                );
            }
    private function switch($box, $post_id){
            $post_meta = get_post_meta( $post_id, "_".$box['id'], true );
            printf(
                rwmb_the_value( $field_id )
                );
            }
    

}