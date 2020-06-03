<?php
 /* 
 * Template Name: front-end-template
 * Template Post Type: post, page
 */ ?>
<html>
	<head>
		<title>front</title>
		<meta charset='utf-8' />
		<link href='assets/css/main.css' rel='stylesheet' />
	</head>
	<body>
		<?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/paypal-integration/config.php'; ?>
		<div class="sbprok-container">
			<div class="sbprok-row common_1">
				<div class="sbprok-md-12">
					<p style="margin-left:10px">Categories</p>
				</div>
				<?php  
					$terms = get_terms(array(
								'taxonomy'   => 'sbprok_category',
								'parent' => 0,
								'hide_empty' => false,
							)
						);
					foreach ( $terms as $term ) {
					$sub_catg  = get_terms(array(
									'taxonomy'   => 'sbprok_category',
									'parent' => $term->term_id,
									'hide_empty' => false,
								   )
				                );
					$image_id = get_term_meta ( $term->term_id, 'category-image-id', true ); ?>
                    <div class="sbprok-md-3">
					    <div class="sbprok-inner-box">
						<h5 data-toggle="tooltip" title="Tooltip on top"><?php echo $term->name ?></h5>
						<?php echo wp_get_attachment_image ( $image_id, 'large' ); ?>
					   </div>
				    </div>

			</div>
			<?php if(!empty($sub_catg)){ ?>
			<!-- Sub-Category -->
			<div class="sbprok-row sbprok-hide Sub-Category">
				<div class="sbprok-md-12">
				<p style="margin-left:10px">Sub Categories</p>
				</div>
				<br/>
				<?php foreach ( $sub_catg as $sub_cat ) { 
				$sub_image_id = get_term_meta ( $sub_cat->term_id, 'category-image-id', true );?>
				<div class="sbprok-md-3">
					    <div class="sbprok-inner-box">
						<h5 data-toggle="tooltip" title="Tooltip on top"><?php echo $sub_cat->name ?></h5>
						<?php echo wp_get_attachment_image ( $sub_image_id, 'large' ); ?>
					   </div>
				    </div>
				</div>
               
                <!-- Sub-Category SECOND STEP -->
			<?php $services = get_posts( array( 
					'numberposts' => -1,
					'post_type'   => 'sbprok_services',
					'order'       => 'ASC',
					'tax_query'   => array(
										array(
											'taxonomy' => 'sbprok_category',
											'terms' => $sub_cat->term_id
										)
					                )
				                )
							  ); ?>

			<div class="sbprok-row sbprok-hide sub_common_2">
				<div class="sbprok-md-12 service_list">
					<p><label style="cursor: pointer;" id="sbprok-back">< Back</label> <span style="margin-left:20px">Online consultations</span></p>
				</div>
				<br/>
				<?php foreach ( $services as $service ) {  
					 $service_meta    = get_post_meta($service->ID);
					 $service_details = get_post_meta( $service->ID, "_sbprok_service_details", true );
					 $duration        = $service_details['_duration'];
					 $price           = $service_details['_price'];
					 $capacity        = $service_details['_max_capacity'];
					 $employee        = get_post_meta( $service->ID, "_sbprok_employees", true );
					 $emp_names       = array();
					 foreach($employee as $emp ){
						$emp_meta  = get_userdata($emp);
						$emp_names[] = $emp_meta->display_name;
					 }
					?>
					<div class="sbprok-md-4 service_list">
					<div class="sbprok-inner-box-2">
						<div class="sbprok-top-img" style="background: url(<?php echo get_the_post_thumbnail_url($service->ID); ?>); background-size: cover;">
						</div>
						<div class="sbprok-round-div">
							<h4>AB</h4>
						</div>
						<div class="sbprok-card-down">
						<input type="hidden" class="service_id" value="<?php echo $service->ID; ?>" name="service_id" >
							<h5 style="margin-top:2px"><?php echo $service->post_title; ?></h5>
						</div>
					</div>
				</div>
                <!-- Sub-Category SECOND STEP END-->

				<!--Sub-Category THIRD STEP -->
				<div class="sbprok-md-12 sbprok-hide common_<?php echo $service->ID; ?>">
					<div class="sbprok-banner-head" style="background-image:linear-gradient(90deg, rgba(191, 144, 67, 0.65) 0%, #bf9043 100%); height:200px;">
					</div>
					<div class="sbprok-am-service">
						<div class="sbprok-am-service-header">
							<div class="sbprok-row">
								<div class="sbprok-md-3">
									<div class="sbprok-am-service-image">
										<img src="https://beauty.wpamelia.com/wp-content/uploads/2020/04/shutterstock_557085679-150x150.jpg" alt="Non-surgical consultation (Online)" style="width:90%">
									</div> 
								</div>
								<div class="sbprok-md-6">
									<div class="sbprok-am-service-data">
										<div class="sbprok-am-category-url">
											<i class="el-icon-back"></i> Online consultations
										</div> 
										<div class="sbprok_am_service-title">
											<h2>Non-surgical consultation (Online)</h2>
										</div> 
										<div class="sbprok_am_service-info">
											<div>Capacity: <?php echo $capacity; ?></div> 
											<div>Duration: <?php echo $duration; ?> </div>
										</div>
									</div> 
								</div>
								<div class="sbprok-md-2">
									<div class="sbprok-am-service-price">
										$<?php echo $price; ?>
									</div>
								</div>
							</div>
							<br/>
							<div class="sbprok-am-service-description">
								<h3>Description</h3> 
								<p>This is an online consultation, you will receive a Zoom link for the meeting in the notification after booking. Thanks.</p>
							</div>
							<div class="sbprok-am-service-info">
								<div class="sbprok-row" style="margin-left: -16px; margin-right: -16px;">
									<div class="sbprok-md-6">
										<div style="padding-left: 16px; padding-right: 16px;">
											<h3>Service Info</h3>
											<div class="sbprok-row">
												<div class="sbprok-md-6">
													<p style="margin-left:15px">Category: <b><?php echo $sub_cat->name ?></b></p>
												</div> 
												<div class="sbprok-md-6">
													<div class="am-category-url">
														<p>Online consultations</p>
													</div>
												</div>
											</div> 
										</div> 
									</div>
									<div class="sbprok-md-6">
										<div class="sbprok_am_service-info-column" style="padding-left: 16px; padding-right: 16px;">
											<div class="sbprok_am_service-providers">
												<h3>Employees</h3> 
												<style>
												.sbprok-am-service-provider select{
													width: 100%;
													height: 32px;
													display: inline-block;
													vertical-align: middle;
													margin: 12px 4px 0 0;
												}
												</style>
												<div class="sbprok-am-service-provider">
													<select>
														<option value="">--Select employee--</option>
														<?php foreach($emp_names as $employee_name){ ?>
															<option value="<?php echo $employee_name; ?>"><?php echo $employee_name; ?></option>	
													    <?php } ?>
													</select>
												</div>
												<form action="<?php echo PAYPAL_URL; ?>" method="post">
												<!-- Identify your business so that you can collect the payments. -->
												<input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
												
												<!-- Specify a Buy Now button. -->
												<input type="hidden" name="cmd" value="_xclick">
												
												<!-- Specify details about the item that buyers will purchase. -->
												<input type="hidden" name="item_name" value="<?php echo $term->name; ?>">
												<input type="hidden" name="item_number" value="<?php echo "1"; ?>">
												<input type="hidden" name="amount" value="<?php echo $price; ?>">
												<input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
												
												<!-- Specify URLs -->
												<input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
												<input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
												
												<!-- Display the payment button. -->
												<input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif">
											</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="sbprok-md-1">
				</div>
				 <!--Sub-Category THIRD STEP END -->
			<?php } ?>
			</div>

				<?php } ?>
            <!-- Sub-Category END -->
			<?php } ?>

			<!-- SECOND STEP -->
			<?php $services = get_posts( array( 
					'numberposts' => -1,
					'post_type'   => 'sbprok_services',
					'order'       => 'ASC',
					'tax_query'   => array(
										array(
											'taxonomy' => 'sbprok_category',
											'terms' => $term->term_id
										)
					                )
				                )
							  ); ?>

			<div class="sbprok-row sbprok-hide common_2">
				<div class="sbprok-md-12 service_list">
					<p><label style="cursor: pointer;" id="sbprok-back">< Back</label> <span style="margin-left:20px">Online consultations</span></p>
				</div>
				<br/>
				<?php foreach ( $services as $service ) {  
					 $service_meta    = get_post_meta($service->ID);
					 $service_details = get_post_meta( $service->ID, "_sbprok_service_details", true );
					 $duration        = $service_details['_duration'];
					 $price           = $service_details['_price'];
					 $capacity        = $service_details['_max_capacity'];
					 $employee        = get_post_meta( $service->ID, "_sbprok_employees", true );
					 $emp_names       = array();
					 foreach($employee as $emp ){
						$emp_meta  = get_userdata($emp);
						$emp_names[] = $emp_meta->display_name;
					 }
					?>
					<div class="sbprok-md-4 service_list">
					<div class="sbprok-inner-box-2">
						<div class="sbprok-top-img" style="background: url(<?php echo get_the_post_thumbnail_url($service->ID); ?>); background-size: cover;">
						</div>
						<div class="sbprok-round-div">
							<h4>AB</h4>
						</div>
						<div class="sbprok-card-down">
						<input type="hidden" class="service_id" value="<?php echo $service->ID; ?>" name="service_id" >
							<h5 style="margin-top:2px"><?php echo $service->post_title; ?></h5>
						</div>
					</div>
				</div>
                <!-- SECOND STEP END-->

				<!-- THIRD STEP -->
				<div class="sbprok-md-12 sbprok-hide common_<?php echo $service->ID; ?>">
					<div class="sbprok-banner-head" style="background-image:linear-gradient(90deg, rgba(191, 144, 67, 0.65) 0%, #bf9043 100%); height:200px;">
					</div>
					<div class="sbprok-am-service">
						<div class="sbprok-am-service-header">
							<div class="sbprok-row">
								<div class="sbprok-md-3">
									<div class="sbprok-am-service-image">
										<img src="https://beauty.wpamelia.com/wp-content/uploads/2020/04/shutterstock_557085679-150x150.jpg" alt="Non-surgical consultation (Online)" style="width:90%">
									</div> 
								</div>
								<div class="sbprok-md-6">
									<div class="sbprok-am-service-data">
										<div class="sbprok-am-category-url">
											<i class="el-icon-back"></i> Online consultations
										</div> 
										<div class="sbprok_am_service-title">
											<h2>Non-surgical consultation (Online)</h2>
										</div> 
										<div class="sbprok_am_service-info">
											<div>Capacity: <?php echo $capacity; ?></div> 
											<div>Duration: <?php echo $duration; ?> </div>
										</div>
									</div> 
								</div>
								<div class="sbprok-md-2">
									<div class="sbprok-am-service-price">
										$<?php echo $price; ?>
									</div>
								</div>
							</div>
							<br/>
							<div class="sbprok-am-service-description">
								<h3>Description</h3> 
								<p>This is an online consultation, you will receive a Zoom link for the meeting in the notification after booking. Thanks.</p>
							</div>
							<div class="sbprok-am-service-info">
								<div class="sbprok-row" style="margin-left: -16px; margin-right: -16px;">
									<div class="sbprok-md-6">
										<div style="padding-left: 16px; padding-right: 16px;">
											<h3>Service Info</h3>
											<div class="sbprok-row">
												<div class="sbprok-md-6">
													<p style="margin-left:15px">Category: <b><?php echo $term->name ?></b></p>
												</div> 
												<div class="sbprok-md-6">
													<div class="am-category-url">
														<p>Online consultations</p>
													</div>
												</div>
											</div> 
										</div> 
									</div>
									<div class="sbprok-md-6">
										<div class="sbprok_am_service-info-column" style="padding-left: 16px; padding-right: 16px;">
											<div class="sbprok_am_service-providers">
												<h3>Employees</h3> 
												<style>
												.sbprok-am-service-provider select{
													width: 100%;
													height: 32px;
													display: inline-block;
													vertical-align: middle;
													margin: 12px 4px 0 0;
												}
												</style>
												<div class="sbprok-am-service-provider">
													<select>
														<option value="">--Select employee--</option>
														<?php foreach($emp_names as $employee_name){ ?>
															<option value="<?php echo $employee_name; ?>"><?php echo $employee_name; ?></option>	
													    <?php } ?>
													</select>
												</div>
						                    <form action="<?php echo PAYPAL_URL; ?>" method="post">
												<!-- Identify your business so that you can collect the payments. -->
												<input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
												
												<!-- Specify a Buy Now button. -->
												<input type="hidden" name="cmd" value="_xclick">
												
												<!-- Specify details about the item that buyers will purchase. -->
												<input type="hidden" name="item_name" value="<?php echo $term->name; ?>">
												<input type="hidden" name="item_number" value="<?php echo "1"; ?>">
												<input type="hidden" name="amount" value="<?php echo $price; ?>">
												<input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
												
												<!-- Specify URLs -->
												<input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
												<input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
												
												<!-- Display the payment button. -->
												<input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif">
											</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="sbprok-md-1">
				</div>
				 <!-- THIRD STEP END -->
			<?php } ?>
			</div>
				<?php 
					} ?>
		</div>
		
		
		<script src='assets/js/jquery.min.js'></script>
		<script src='assets/js/main.js'></script>
	</body>
	
</html>