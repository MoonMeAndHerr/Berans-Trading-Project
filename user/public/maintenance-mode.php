<?php

	require_once('../include/header.php');

?>
	<body data-plugin-page-transition>

		<div class="body coming-soon">
			<header id="header" data-plugin-options="{'stickyEnabled': false}">
				<div class="header-body border border-top-0 border-end-0 border-start-0">
					<div class="header-container container py-2">
						<div class="header-row">
							<div class="header-column">
								<div class="header-row">
									<p class="mb-0 text-dark"><strong>Get in touch!</strong> <a href="tel:012345679" class="text-color-dark text-color-hover-primary"><?php echo COMPANY_CONTACT; ?></a></span><span class="d-none d-sm-inline-block ps-1"> | <a href="#"><?php echo COMPANY_EMAIL; ?></a></span></p>
								</div>
							</div>
						</div>
					</div>
				</div>

			</header>

			<div role="main" class="main" style="min-height: calc(100vh - 393px);">
				<div class="container">
					<div class="row mt-5">
						<div class="col text-center">
							<div class="logo">
								<a href="#">
									<img width="100" height="60" src="../../siteidentity/<?php echo WEB_LOGO; ?>" alt="Porto">
								</a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<hr class="solid my-5">
						</div>
					</div>
					<div class="row">
						<div class="col text-center">
							<div class="overflow-hidden mb-2">
								<h2 class="font-weight-normal text-7 mb-0 appear-animation" data-appear-animation="maskUp"><strong class="font-weight-extra-bold">Maintenance Mode</strong></h2>
							</div>
							<div class="overflow-hidden mb-1">
								<p class="lead mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="200">The website is undergoing some scheduled maintenance.<br>Please come back later.</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<hr class="solid my-5">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="row">
								<div class="col-lg-4 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="600">
									<div class="feature-box">
										<div class="feature-box-icon">
											<i class="far fa-life-ring"></i>
										</div>
										<div class="feature-box-info">
											<h4 class="text-4 text-uppercase mb-1 font-weight-bold">Whats this about?</h4>
											<p class="mb-4">We're currently performing scheduled maintenance to improve your browsing experience. This brief pause allows us to fine-tune features, update systems, and ensure everything runs smoother than ever.</p>
										</div>
									</div>
								</div>
								<div class="col-lg-4 appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="400">
									<div class="feature-box">
										<div class="feature-box-icon">
											<i class="far fa-clock"></i>
										</div>
										<div class="feature-box-info">
											<h4 class="text-4 text-uppercase mb-1 font-weight-bold">Come back later</h4>
											<p class="mb-4">We're sprucing things up — just like a fresh coat of paint on a shop window. Great things are coming soon, so hang tight and check back shortly. You won't want to miss what’s next!</p>
										</div>
									</div>
								</div>
								<div class="col-lg-4 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="600">
									<div class="feature-box">
										<div class="feature-box-icon">
											<i class="far fa-envelope"></i>
										</div>
										<div class="feature-box-info">
											<h4 class="text-4 text-uppercase mb-1 font-weight-bold">Get in Touch</h4>
											<p class="mb-4">Have a question, concern, or just want to say hi?
											We're still here for you!
											Reach out at <?php echo COMPANY_EMAIL; ?> or message us through contact page later. We’ll get back to you as soon as we can.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

<?php

	require_once('../include/alt-footer.php');

?>
