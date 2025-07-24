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
									<p class="mb-0 text-dark"><strong>Get in touch!</strong> <a href="tel:012345679" class="text-color-dark text-color-hover-primary">(123) 456-789</a></span><span class="d-none d-sm-inline-block ps-1"> | <a href="#">mail@domain.com</a></span></p>
								</div>
							</div>
							<div class="header-column justify-content-end">
								<div class="header-row">
									<ul class="header-social-icons social-icons me-2">
										<li class="social-icons-facebook"><a href="http://www.facebook.com/" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
										<li class="social-icons-x"><a href="http://www.x.com/" target="_blank" title="X"><i class="fab fa-x-twitter"></i></a></li>
										<li class="social-icons-linkedin"><a href="http://www.linkedin.com/" target="_blank" title="Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
									</ul>
									<div class="header-nav-features">
										<div class="header-nav-features-search-reveal-container">
											<div class="header-nav-feature header-nav-features-search header-nav-features-search-reveal d-inline-flex">
												<a href="#" class="header-nav-features-search-show-icon d-inline-flex text-decoration-none"><i class="fas fa-search header-nav-top-icon"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="header-nav-features header-nav-features-no-border p-static">
					<div class="header-nav-feature header-nav-features-search header-nav-features-search-reveal header-nav-features-search-reveal-big-search header-nav-features-search-reveal-big-search-full">
						<div class="container">
							<div class="row h-100 d-flex">
								<div class="col h-100 d-flex">
									<form role="search" class="d-flex h-100 w-100" action="page-search-results.html" method="get">
										<div class="big-search-header input-group">
											<input class="form-control text-1" id="headerSearch" name="q" type="search" value="" placeholder="Type and hit enter...">
											<a href="#" class="header-nav-features-search-hide-icon"><i class="fas fa-times header-nav-top-icon"></i></a>
										</div>
									</form>
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
								<a href="index.html">
									<img width="100" height="48" src="img/logo-default-slim.png" alt="Porto">
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
											<p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing metus elit. Quisque rutrum pellentesque imperdiet. Quisque rutrum pellentesque imperdiet.</p>
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
											<p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum pellentesque imperdiet. Quisque rutrum pellentesque imperdiet. Nulla lacinia iaculis nulla.</p>
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
											<p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum pellentesque imperdiet. Quisque rutrum pellentesque imperdiet. Nulla lacinia iaculis nulla.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

<?php

	require_once('../include/footer.php');

?>
