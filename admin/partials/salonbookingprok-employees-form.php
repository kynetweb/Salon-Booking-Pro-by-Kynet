<form method="post" enctype="multipart/form-data">
    <p>
        <label> Enter Username*</label>
        <div>
            <input type="text" name="user_name" placeholder="User Name" required/>
        </div>
    </p>
    <p>
        <label> Enter E-mail*</label>
        <div>
            <input type="email" name="user_email" placeholder="User Email" required/>
        </div>
    </p>
    <p>
        <label> Enter Password*</label>
        <div>
            <input type="password" name="user_password" placeholder="Enter Your Password" required />
        </div>
    </p>
    <p>
        <label> Confirm Password*</label>
        <div>
            <input type="password" name="user_confirm_password" placeholder="Confirm Password" required/>
        </div>
    </p>
    <p>
        <label> Address </label>
        <div>
            <input type="text" name="employee_address" />
        </div>
    </p>
    <p>
        <label> Phone </label>
        <div>
            <input type="text" name="employee_phone" />
        </div>
    </p>
    <p>
        <label>Active</label>
        <div>
            <input type="checkbox" name="active_status" value ="active"/>
        </div>
    </p>
    <p>
        <label>Profile Picture</label>
        <div>
            <input type="text" name="profile_image" class="profile_image" class="regular-text" />
            <input type='button' class="button-primary" value="Upload Image" id="uploadimage"/><br />
        </div>
    </p>
    <input type="submit" id="ga_butt" name="user_form_submit" value="Submit"/>
</form>
