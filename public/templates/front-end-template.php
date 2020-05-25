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
		<div class="sbprok-container">
			<div class="sbprok-row common_1">
				<div class="sbprok-md-12">
					<p style="margin-left:10px">Categories</p>
				</div>
				<?php  
						$terms = get_terms(
							array(
								'taxonomy'   => 'sbprok_category',
								'hide_empty' => false,
							)
						);
					foreach ( $terms as $term ) { 
						$image_id = get_term_meta ( $term->term_id, 'category-image-id', true ); ?>
                    <div class="sbprok-md-3">
					    <div class="sbprok-inner-box">
						<h5 data-toggle="tooltip" title="Tooltip on top"><?php echo $term->name ?></h5>
						<?php echo wp_get_attachment_image ( $image_id, 'large' ); ?>
					   </div>
				    </div>

			</div>
					
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
				              );
				 ?>
			<div class="sbprok-row sbprok-hide common_2">
				<div class="sbprok-md-12">
					<p><label style="cursor: pointer;" id="sbprok-back">< Back</label> <span style="margin-left:20px">Online consultations</span></p>
				</div>
				<br/>
				<?php foreach ( $services as $service ) {  ?>
					<div class="sbprok-md-4">
					<div class="sbprok-inner-box-2">
						<div class="sbprok-top-img" style="background: url(<?php echo get_the_post_thumbnail_url($service->ID); ?>); background-size: cover;">
						</div>
						<div class="sbprok-round-div">
							<h4>AB</h4>
						</div>
						<div class="sbprok-card-down">
							<h5 style="margin-top:2px"><?php echo $service->post_title; ?></h5>
						</div>
					</div>
				</div>
			<?php } ?>
				
			</div>
			<?php 
					} ?>	
			<!-- THIRD STEP -->
			<div class="sbprok-row sbprok-hide common_3">
				<div class="sbprok-md-12">
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
											<div>Capacity: 1</div> 
											<div>Duration: 1h  </div>
										</div>
									</div> 
								</div>
								<div class="sbprok-md-2">
									<div class="sbprok-am-service-price">
										$100.00
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
													<p style="margin-left:15px">Category:</p>
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
														<option value="">demo1</option>
														<option value="">demo2</option>
														<option value="">demo3</option>
													</select>
												</div>
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
			</div>
		</div>
		<script src='assets/js/jquery.min.js'></script>
		<script src='assets/js/main.js'></script>
	</body>
	
</html>