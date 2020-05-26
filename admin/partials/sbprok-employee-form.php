<?php

/**
 * Provide a admin form for adding Employee
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Sbprok
 * @subpackage Sbprok/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<form class = "sbprok-employee-containerbox" method="post" enctype="multipart/form-data">
    <h2 class = "sbprok-employee-center"> Employee Form </h2>
        <div class="sbprok-employeeform_row">
            <div class="sbprok-col-25">
                <label> Enter Username*</label>
            </div>
            <div class="sbprok-col-75">
                <input type="text" name="user_name" placeholder="User Name" required/>
            </div>
        </div>
        <div class = "sbprok-employeeform_row">
            <div class = "sbprok-col-25">
                <label> Enter E-mail* </label>
            </div>
            <div class = "sbprok-col-75">
                <input type = "email" name = "user_email" placeholder = "User Email" required/>
            </div>
        </div>
        
        <div class = "sbprok-employeeform_row">
            <div class = "sbprok-col-25">
                <label> Enter Password* </label>
            </div>
            <div class = "sbprok-col-75">
                <input type="password" name="user_password" placeholder="Enter Your Password" required />
            </div>
        </div>
        
        <div class = "sbprok-employeeform_row">
            <div class="sbprok-col-25">
                <label> Confirm Password*</label>
            </div>
            <div class="sbprok-col-75">
                <input type="password" name="user_confirm_password" placeholder="Confirm Password" required/>
            </div>
        </div>

        <div class = "sbprok-employeeform_row">
            <div class = "sbprok-col-25">
                <label> Enter Google Calendar ID* </label>
            </div>
            <div class = "sbprok-col-75">
            <input type="text" name="calendar_id" placeholder="Google Calendar ID" required/>
            </div>
        </div>
        
        <div class="sbprok-employeeform_row">
            <div class="sbprok-col-25">
                <label> Address </label>
            </div>
            <div class="sbprok-col-75">
                <input type="text" name="employee_address" />
            </div>
        </div>
        <div class = "sbprok-employeeform_row">
            <div class = "sbprok-col-25">    
                <label> Phone </label>
            </div>
            <div class = "sbprok-col-75">
                <input type="text" name="employee_phone" />
            </div>
        </div>
        <div class = "sbprok-employeeform_row">
            <div class = "sbprok-col-25"> 
                <label>Active</label>
            </div>
            <div class="sbprok-col-75">
                <input type="checkbox" name="active_status" value ="active"/>
            </div>
        </div>
        <div class='sbprok-employeeform_row'>
            <div class = "sbprok-col-25"> 
                <label>Profile Image</label>
            </div>
            <div class="sbprok-col-75">
                <img id='sbprok-img-preview' src="" height='100'>
                <input id="sbprok-img-upload" class ="sbprok-employee-form-button" type="button" class="button" value = "<?php _e( 'Upload image' ); ?>" />
                <input type='hidden' name='sbprok_img_id' id='sbprok-img-id' value =""><br></br>
            </div>
        </div>
        <div class='sbprok-employeeform_row'>
            <div class = "sbprok-col-25"> 
                
            </div>
            <div class="sbprok-col-75">
                <input type ="submit" class ="sbprok-employee-form-button  sbprok-display-right" name ="user_form_submit" value ="Save"/>
            </div>
    </form>


