
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
        <div class="sbprok-employeeform_row">
            <div class="sbprok-col-25">
                <label> Enter E-mail*</label>
            </div>
            <div class="sbprok-col-75">
                <input type="email" name="user_email" placeholder="User Email" required/>
            </div>
        </div>
        <div class="sbprok-employeeform_row">
            <div class="sbprok-col-25">
                <label> Enter Password*</label>
            </div>
            <div class="sbprok-col-75">
                <input type="password" name="user_password" placeholder="Enter Your Password" required />
            </div>
        </div>
        <div class="sbprok-employeeform_row">
            <div class="sbprok-col-25">
                <label> Confirm Password*</label>
            </div>
            <div class="sbprok-col-75">
                <input type="password" name="user_confirm_password" placeholder="Confirm Password" required/>
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
                <img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>' height='100'>
                <input id="upload_image_button" class ="sbprok-employee-form-button" type="button" class="button" value = "<?php _e( 'Upload image' ); ?>" />
                <input type='hidden' name='image_attachment_id' id='image_attachment_id' value ='<?php echo get_option( 'media_selector_attachment_id' ); ?>'><br></br>
            </div>
        </div>
        <div class='sbprok-employeeform_row'>
            <div class = "sbprok-col-25"> 
                
            </div>
            <div class="sbprok-col-75">
                <input type ="submit" class ="sbprok-employee-form-button  sbprok-display-right" name ="user_form_submit" value ="Save"/>
            </div>
    </form>

