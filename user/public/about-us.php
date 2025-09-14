<?php

	require_once('../include/header.php');
	require_once('../include/navbar.php');

	$pdo = openDB();

        $stmt = $pdo->query("SELECT * FROM customer");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalClient = 42 + count($results);

		$stmt = $pdo->query("SELECT * FROM invoice");
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$totalOrder = 407 + count($results);

		$date1 = new DateTime("2023-12-20"); // your fixed date
		$date2 = new DateTime(); // current date
		$interval = $date1->diff($date2);
		$yearsInService = $interval->y;

    closeDB($pdo);

?>

			<div role="main" class="main">
				<section class="page-header page-header-modern page-header-background page-header-background-md overlay overlay-color-secondary overlay-show overlay-op-7" style="background-image: url(../../media/beransbanner.jpg);">
					<div class="container">
						<div class="row mt-5">
							<div class="col-md-12 align-self-center p-static order-2 text-center">
								<h1 class="text-9 font-weight-bold">About Us</h1>
								<span class="sub-title">The perfect choice for your goods supplier</span>
							</div>
							<div class="col-md-12 align-self-center order-1">
								<ul class="breadcrumb breadcrumb-light d-block text-center">
									<li><a href="#">Home</a></li>
									<li class="active">About Us</li>
								</ul>
							</div>
						</div>
					</div>
				</section>

				<div class="container">

					<div class="row pt-5">
						<div class="col">

							<div class="row text-center pb-5">
								<div class="col-md-9 mx-md-auto">
									<div class="overflow-hidden mb-3">
										<h1 class="word-rotator slide font-weight-bold text-8 mb-0 appear-animation" data-appear-animation="maskUp">
											<span>We are Berans, We </span>
											<span class="word-rotator-words bg-primary">
												<b class="is-visible">Supply</b>
												<b>Provide</b>
												<b>Grant</b>
											</span>
											<span> Goods</span>
										</h1>
									</div>
									<div class="overflow-hidden mb-3">
										<p class="lead mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="200">
											We mainly supply business needs for food packaging, shope furnitures, disposables, and other goods for below market price. 
										</p>
									</div>
								</div>
							</div>

							<div class="row mt-3 mb-5">
								<div class="col-md-4 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="800">
									<h3 class="font-weight-bold text-4 mb-2">Our Mission</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elementum, nulla vel pellentesque consequat, ante nulla hendrerit arcu.</p>
								</div>
								<div class="col-md-4 appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="600">
									<h3 class="font-weight-bold text-4 mb-2">Our Vision</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce nulla vel pellentesque consequat, ante nulla hendrerit arcu.</p>
								</div>
								<div class="col-md-4 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="800">
									<h3 class="font-weight-bold text-4 mb-2">Why Us</h3>
									<p>At Berans Trading, we go beyond supplying — we empower businesses. From food packaging to store furnishings and daily-use disposables, we provide quality essentials at below-market prices. Our commitment to transparency, bulk affordability, and responsive support makes us the smart choice for growing businesses that need reliable, cost-efficient sourcing without the corporate overhead.</p>
								</div>
							</div>

						</div>
					</div>

				</div>

				<section class="section section-secondary border-0 mb-0 appear-animation" data-appear-animation="fadeIn" data-plugin-options="{'accY': -150}">
					<div class="container">
						<div class="row counters counters-sm pb-4 pt-3">
							<div class="col-sm-6 col-lg-3 mb-5 mb-lg-0">
								<div class="counter">
									<i class="icons icon-user text-color-light"></i>
									<strong class="text-color-light font-weight-extra-bold" data-to="<?php echo $totalClient; ?>" data-append="+">0</strong>
									<label class="text-4 mt-1 text-color-light">Happy Clients</label>
								</div>
							</div>
							<div class="col-sm-6 col-lg-3 mb-5 mb-lg-0">
								<div class="counter">
									<i class="icons icon-badge text-color-light"></i>
									<strong class="text-color-light font-weight-extra-bold" data-to="<?php echo $yearsInService; ?>">0</strong>
									<label class="text-4 mt-1 text-color-light">Years In Business</label>
								</div>
							</div>
							<div class="col-sm-6 col-lg-3 mb-5 mb-sm-0">
								<div class="counter">
									<i class="icons icon-graph text-color-light"></i>
									<strong class="text-color-light font-weight-extra-bold" data-to="99" data-append="%">0</strong>
									<label class="text-4 mt-1 text-color-light">Trusted Score</label>
								</div>
							</div>
							<div class="col-sm-6 col-lg-3">
								<div class="counter">
									<i class="icons icon-cup text-color-light"></i>
									<strong class="text-color-light font-weight-extra-bold" data-to="<?php echo $totalClient; ?>" data-append="+">0</strong>
									<label class="text-4 mt-1 text-color-light">Order Placed</label>
								</div>
							</div>
						</div>
					</div>
				</section>

				<section class="section section-height-3 bg-color-grey m-0 border-0">
					<div class="container">
						<div class="row align-items-center justify-content-center">
							<div class="col-lg-6 pb-sm-4 pb-lg-0 pe-lg-5 mb-sm-5 mb-lg-0">
								<h2 class="text-color-dark font-weight-normal text-6 mb-2">Who <strong class="font-weight-extra-bold">We Are</strong></h2>
								<p class="lead">To err is human, to supply is Berans Trading</p>
								<p class="pe-5 me-5">Berans Trading is a dedicated independent supplier specializing in business essentials — from food packaging and store furnishings to disposables and general goods. Founded with a mission to serve businesses of all sizes, we prioritize affordability, reliability, and transparency. Whether you're just starting or scaling up, Berans Trading is your trusted partner for bulk solutions at below-market prices.</p>
							</div>
							<div class="col-sm-8 col-md-6 col-lg-4 offset-sm-4 offset-md-4 offset-lg-2 position-relative mt-sm-5" style="top: 1.7rem;">
								<img src="img/generic/generic-corporate-3-1.jpg" class="img-fluid position-absolute d-none d-sm-block appear-animation" data-appear-animation="expandIn" data-appear-animation-delay="300" style="top: 10%; left: -50%;" alt="" />
								<img src="img/generic/generic-corporate-3-2.jpg" class="img-fluid position-absolute d-none d-sm-block appear-animation" data-appear-animation="expandIn" style="top: -33%; left: -29%;" alt="" />
								<img src="img/generic/generic-corporate-3-3.jpg" class="img-fluid position-relative appear-animation mb-2" data-appear-animation="expandIn" data-appear-animation-delay="600" alt="" />
							</div>
						</div>
					</div>
				</section>

			</div>

<?php

	require_once('../include/footer.php');

?>
