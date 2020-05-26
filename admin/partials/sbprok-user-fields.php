<h3><?php _e('Additional Employee Information'); ?></h3>
		<table class="form-table">
			<tr>
				<th>
					<label for="employee_address"><?php _e('Address'); ?></label>
				</th>
				<td>
					<input type="text" name="employee_address" id="employee_address" value="<?php echo $employee_address; ?>"  /><br />
				</td>
			</tr>
			<tr>
				<th>
					<label for="employee_phone"><?php _e('Phone'); ?></label>
				</th>
				<td>
					<input type="text" name="employee_phone" id="employee_phone" value="<?php echo $employee_phone; ?>"  /><br />
				</td>
			</tr>
			<tr>
				<th>
					<?php _e('Active'); ?>
				</th>
				<td>
					<input type="checkbox" name="active_status" <?php if ($active_status == 'active' ) { ?>checked="checked"<?php } ?> value="active" /> 
				</td>
			</tr>
			<tr>
				<th>
					<?php _e('Active'); ?>
				</th>
				<td>
				<input type="text" name="calendar_id" value="<?php echo $calendar_id; ?>" placeholder="Google Calendar ID"/>
				</td>
			</tr>
			
			<tr>
            	<th>
					<?php _e('Profile Image'); ?>
				</th>
				<td>
					<img id='sbprok-img-preview' src='<?php echo $profile_image ; ?>' height='100'>
					<input id="sbprok-img-upload" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
					<input type='hidden' name='sbprok_img_id' id='sbprok-img-id' value='<?php echo $profile_image; ?>'>
        		</td>
       		</tr>
		</table>