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
									<p class="mb-0 text-dark"><strong>Get in touch!</strong> (123) 456-789</span><span class="d-none d-sm-inline-block ps-1"> | <a href="#">mail@domain.com</a></span></p>
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

			<div role="main" class="main">
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
							<h2 class="font-weight-normal text-7 mb-2"><strong class="font-weight-extra-bold">Our Website is Coming Soon</strong></h2>
							<p class="mb-0 lead">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. </p>
							<p class="mb-1">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. </p>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<hr class="solid my-5">
						</div>
					</div>
					<div class="row justify-content-center mb-2">
						<div class="col-lg-6">
							<div class="progress-bars mt-4">
								<div class="progress-label text-2">
									<span><strong>Conception:</strong> Finished</span>
								</div>
								<div class="progress mb-3">
									<div class="progress-bar progress-bar-primary" data-appear-progress-animation="100%">
										<span class="progress-bar-tooltip">100%</span>
									</div>
								</div>
								<div class="progress-label text-2">
									<span><strong>Design</strong></span>
								</div>
								<div class="progress mb-3">
									<div class="progress-bar progress-bar-primary" data-appear-progress-animation="85%" data-appear-animation-delay="300">
										<span class="progress-bar-tooltip">85%</span>
									</div>
								</div>
								<div class="progress-label text-2">
									<span><strong>Development</strong></span>
								</div>
								<div class="progress mb-3">
									<div class="progress-bar progress-bar-primary" data-appear-progress-animation="75%" data-appear-animation-delay="600">
										<span class="progress-bar-tooltip">75%</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<hr class="solid my-5">
						</div>
					</div>
					<div class="row justify-content-center pt-2 pb-4">
						<div class="col-lg-6">
							<h5 class="text-dark">Let me know when the site is done</h5>

							<div class="alert alert-success d-none" id="newsletterSuccess">
								<strong>Success!</strong> You've been added to our email list.
							</div>

							<div class="alert alert-danger d-none" id="newsletterError"></div>

							<form id="newsletterForm" action="php/newsletter-subscribe.php" method="get">
								<div class="input-group input-group-lg">
									<input class="form-control h-auto" placeholder="E-mail Address" name="newsletterEmail" id="newsletterEmail" type="email">
									<button type="submit" class="btn btn-primary btn-modern text-1">Subscribe</button>
								</div>
							</form>	
						</div>
					</div>
				</div>
			</div>

<?php

	require_once('../include/footer.php');

?>
