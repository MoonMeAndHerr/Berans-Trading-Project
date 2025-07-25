<?php

	require_once('../include/header.php');
	require_once('../include/navbar.php');

?>

			<div role="main" class="main">
				<div class="owl-carousel owl-carousel-light owl-carousel-light-init-fadeIn owl-theme manual dots-inside dots-horizontal-center dots-light show-dots-hover show-dots-xs nav-inside nav-inside-plus nav-dark nav-md nav-font-size-md show-nav-hover mb-0" data-plugin-options="{'autoplayTimeout': 7000}" data-dynamic-height="['600px','600px','600px','550px','500px']" style="height: 600px;">
					<div class="owl-stage-outer">
						<div class="owl-stage">

							<!-- Carousel Slide 1 -->
							<div class="owl-item position-relative overlay overlay-color-primary overlay-show overlay-op-8" style="background-image: url(img/beransbanner.jpg); background-size: cover; background-position: center; height: 600px;">
								<div class="container position-relative z-index-3 h-100">
									<div class="row justify-content-center align-items-center h-100">
										<div class="col-lg-6">
											<div class="d-flex flex-column align-items-center">
												<h3 class="position-relative text-color-light text-4 line-height-5 font-weight-medium px-4 mb-2 appear-animation" data-appear-animation="fadeInDownShorter" data-plugin-options="{'minWindowWidth': 0}">
													<span class="position-absolute right-100pct top-50pct transform3dy-n50 opacity-3">
														<img src="img/slides/slide-title-border.png" class="w-auto appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
													</span>
													WE SUPPLY GOODS, WE ARE
													<span class="position-absolute left-100pct top-50pct transform3dy-n50 opacity-3">
														<img src="img/slides/slide-title-border.png" class="w-auto appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
													</span>
												</h3>
												<h2 class="porto-big-title text-color-light font-weight-extra-bold mb-3" data-plugin-animated-letters data-plugin-options="{'startDelay': 1000, 'minWindowWidth': 0, 'animationSpeed': 300, 'animationName': 'fadeInRightShorterOpacity', 'letterClass': 'd-inline-block'}">BERANS</h2>
												<p class="text-4 text-color-light font-weight-light text-center mb-0" data-plugin-animated-letters data-plugin-options="{'startDelay': 2000, 'minWindowWidth': 0}">The best choice for your startup business goods supplier</p>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Carousel Slide 2 -->
							<div class="owl-item position-relative overlay overlay-color-primary overlay-show overlay-op-8" style="background-image: url(img/beransbanner.jpg); background-size: cover; background-position: center; height: 600px;">
								<div class="container position-relative z-index-3 h-100">
									<div class="d-flex flex-column align-items-center justify-content-center h-100">
										<h3 class="position-relative text-color-light text-5 line-height-5 font-weight-medium px-4 mb-2 appear-animation" data-appear-animation="fadeInDownShorter" data-plugin-options="{'minWindowWidth': 0}">
											<span class="position-absolute right-100pct top-50pct transform3dy-n50 opacity-3">
												<img src="img/slides/slide-title-border.png" class="w-auto appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
											</span>
											DO YOU NEED A <span class="position-relative">NEW <span class="position-absolute left-50pct transform3dx-n50 top-0 mt-4"><img src="img/slides/slide-white-line.png" class="w-auto appear-animation" data-appear-animation="fadeInLeftShorterPlus" data-appear-animation-delay="1000" data-plugin-options="{'minWindowWidth': 0}" alt="" /></span></span>
											<span class="position-absolute left-100pct top-50pct transform3dy-n50 opacity-3">
												<img src="img/slides/slide-title-border.png" class="w-auto appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
											</span>
										</h3>
										<h1 class="text-color-light font-weight-extra-bold text-13 mb-3 appear-animation" data-appear-animation="blurIn" data-appear-animation-delay="500" data-plugin-options="{'minWindowWidth': 0}">Business Needs?</h1>
										<p class="text-4-5 text-color-light font-weight-light mb-0" data-plugin-animated-letters data-plugin-options="{'startDelay': 1000, 'minWindowWidth': 0}">Check out our packaging, furnitures, disposables and other goods</p>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="owl-dots mb-5">
						<button role="button" class="owl-dot active"><span></span></button>
						<button role="button" class="owl-dot"><span></span></button>
					</div>
				</div>

				<div class="home-intro" id="home-intro">
					<div class="container">

						<div class="row align-items-center">
							<div class="col-lg-8">
								<p>
									The fastest way to get your business needs with <span class="highlighted-word text-color-primary font-weight-semibold text-5">Berans</span>
									<span>Check out our options of goods now.</span>
								</p>
							</div>
							<div class="col-lg-4">
								<div class="get-started text-start text-lg-end">
									<a href="#" class="btn btn-primary btn-lg text-3 font-weight-semibold px-4 py-3">Get Your Quote Now!</a>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="container">
					<div class="row">
						<div class="col">

							<div class="my-4 lightbox appear-animation" data-appear-animation="fadeInUpShorter" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}">
								<div class="owl-carousel owl-theme pb-3" data-plugin-options="{'items': 4, 'margin': 35, 'loop': false}">

									<?php

										$pdo = openDB();
										$sql = "SELECT * FROM product INNER JOIN section on product.section_id = section.section_id ORDER BY product_id DESC LIMIT 12";
										$stmt = $pdo->query($sql);
										closeDB($pdo);

										while ($row = $stmt->fetch()) {

									?>

									<div class="portfolio-item">
										<span class="thumb-info thumb-info-lighten thumb-info-no-borders thumb-info-bottom-info thumb-info-centered-icons border-radius-0">
											<span class="thumb-info-wrapper border-radius-0">
												<img src="../../media/<?php echo $row['image_url']; ?>" class="img-fluid border-radius-0" alt="">
												<span class="thumb-info-title">
													<span class="thumb-info-inner line-height-1 font-weight-bold text-dark position-relative top-3"><?php echo $row['name']; ?></span>
													<span class="thumb-info-type"><?php echo $row['section_name']; ?></span>
												</span>
												<span class="thumb-info-action">
													<a href="portfolio-single-wide-slider.html">
														<span class="thumb-info-action-icon thumb-info-action-icon-primary"><i class="fas fa-link"></i></span>
													</a>
													<a href="../../media/<?php echo $row['image_url']; ?>" class="lightbox-portfolio">
														<span class="thumb-info-action-icon thumb-info-action-icon-light"><i class="fas fa-search text-dark"></i></span>
													</a>
												</span>
											</span>
										</span>
									</div>
									<?php

										}

									?>
								</div>
							</div>
						<hr class="solid my-5">

						</div>
					</div>

					<div class="row pt-3">
						<div class="col-lg-4 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="300">
							<div class="feature-box feature-box-style-2">
								<div class="feature-box-icon">
									<i class="icons icon-support text-color-primary"></i>
								</div>
								<div class="feature-box-info">
									<h4 class="font-weight-bold mb-2">Customer Support</h4>
									<p>Our dedicated team is always ready to assist you with product inquiries, orders, and after-sales service — ensuring a smooth and satisfying experience.</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 appear-animation" data-appear-animation="fadeInUpShorter">
							<div class="feature-box feature-box-style-2">
								<div class="feature-box-icon">
									<i class="icons icon-layers text-color-primary"></i>
								</div>
								<div class="feature-box-info">
									<h4 class="font-weight-bold mb-2">Trusted and Transparent</h4>
									<p>We build long-term partnerships through honest pricing, clear communication, and reliable fulfillment you can count on.</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="300">
							<div class="feature-box feature-box-style-2">
								<div class="feature-box-icon">
									<i class="icons icon-menu text-color-primary"></i>
								</div>
								<div class="feature-box-info">
									<h4 class="font-weight-bold mb-2">Bulk Purchasing</h4>
									<p>Enjoy competitive rates with our bulk ordering system — perfect for businesses needing consistent supply at below-market prices.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-lg-4 pb-5">
						<div class="col-lg-4 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="300">
							<div class="feature-box feature-box-style-2">
								<div class="feature-box-icon">
									<i class="icons icon-doc text-color-primary"></i>
								</div>
								<div class="feature-box-info">
									<h4 class="font-weight-bold mb-2">Documented Finance</h4>
									<p>Every transaction comes with proper invoicing and documentation, supporting your business records and compliance needs.</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 appear-animation" data-appear-animation="fadeInUpShorter">
							<div class="feature-box feature-box-style-2">
								<div class="feature-box-icon">
									<i class="icons icon-user text-color-primary"></i>
								</div>
								<div class="feature-box-info">
									<h4 class="font-weight-bold mb-2">Independent Supplier</h4>
									<p>As a non-franchise, we offer flexible sourcing and a broader product range tailored to your unique business demands.</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="300">
							<div class="feature-box feature-box-style-2">
								<div class="feature-box-icon">
									<i class="icons icon-screen-desktop text-color-primary"></i>
								</div>
								<div class="feature-box-info">
									<h4 class="font-weight-bold mb-2">Up-To-Date</h4>
									<p>We constantly update our inventory to match current market trends and seasonal demands, helping your business stay ahead.</p>
								</div>
							</div>
						</div>
					</div>
				</div>				

				<section class="section section-primary border-top-0 mb-0">
					<div class="container">
						<div class="row counters counters-sm counters-text-light">
							<div class="col-sm-6 col-lg-3 mb-5 mb-lg-0">
								<div class="counter">
									<strong data-to="250" data-append="+">0</strong>
									<label class="opacity-5 text-4 mt-1">Happy Clients</label>
								</div>
							</div>
							<div class="col-sm-6 col-lg-3 mb-5 mb-lg-0">
								<div class="counter">
									<strong data-to="6500" data-append="+">0</strong>
									<label class="opacity-5 text-4 mt-1">Order Placed</label>
								</div>
							</div>
							<div class="col-sm-6 col-lg-3 mb-5 mb-sm-0">
								<div class="counter">
									<strong data-to="100" data-append="+">0</strong>
									<label class="opacity-5 text-4 mt-1">Product Supplied</label>
								</div>
							</div>
							<div class="col-sm-6 col-lg-3">
								<div class="counter">
									<strong data-to="3000" data-append="+">0</strong>
									<label class="opacity-5 text-4 mt-1">Growing Community</label>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>

<?php

	require_once('../include/footer.php');

?>