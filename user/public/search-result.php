<?php

	require_once('../include/header.php');
	require_once('../include/navbar.php');
	require_once('../private/search-result.php');

?>

<style>
	
	.product-image {
		width: 250px;         
		height: 250px;       
	}

</style>

			<div role="main" class="main">

				<section class="page-header page-header-modern page-header page-header-modern bg-color-primary page-header-md m-0">
					<div class="container">
						<div class="row">
							<div class="col-md-12 align-self-center p-static order-2 text-center">
								<h1 class="text-light text-10"><strong>Search</strong></h1>
								<span class="sub-title text-light"><?php echo $rowCount; ?> search results founds!</span>
							</div>
							<div class="col-md-12 align-self-center order-1">
								<ul class="breadcrumb d-block text-center breadcrumb-light">
									<li><a href="#">Home</a></li>
									<li class="active">Search Result</li>
								</ul>
							</div>
						</div>
					</div>
				</section>
				<hr class="m-0">

				<div class="container py-5 mt-3">

					<div class="row">
						<div class="col">
							<h2 class="font-weight-normal text-7 mb-0">Showing <?php echo $rowCount; ?> results found.</h2>
							<p class="lead mb-0"></p>
						</div>
					</div>
					<div class="row">
						<div class="col pt-2 mt-1">
							<hr class="my-4">
						</div>
					</div>
								<div role="main" class="main shop pt-4">

				<div class="container">

					<div class="masonry-loader masonry-loader-showing">
						<div class="row products product-thumb-info-list" data-plugin-masonry data-plugin-options="{'layoutMode': 'fitRows'}">

					<?php

						foreach ($results as $row) {

					?>

					<div class="col-12 col-sm-6 col-lg-3">
						<div class="product mb-0">
							<div class="product-thumb-info border-0 mb-3">

								<div class="product-thumb-info-badges-wrapper">
								<span class="badge badge-ecommerce text-bg-success">NEW</span>

								</div>

								<a href="product?id=<?php echo $row['product_id']; ?>">
									<div class="product-thumb-info-image">
										<img alt="" class="img-fluid product-image" src="../../media/<?php echo $row['image_url']; ?>">
									</div>
								</a>
							</div>
							<div class="d-flex justify-content-between">
								<div>
									<a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1"><?php echo $row['section_name']; ?></a>
									<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="product?id=<?php echo $row['product_id']; ?>" class="text-color-dark text-color-hover-primary"><?php echo $row['name']; ?></a></h3>
								</div>
								<a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
							</div>
							<div title="Rated 5 out of 5">
								<input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
							</div>
							<p class="price text-5 mb-3"></p>
						</div>
					</div>
					<?php

						}

					?>

						</div>
					</div>
				</div>
			</div>
				</div>



			</div>


<?php

	require_once('../include/alt-footer.php');

?>