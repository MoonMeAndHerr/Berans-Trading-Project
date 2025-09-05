<?php

	require_once('../include/header.php');
	require_once('../include/alt-navbar.php');

	$pdo = openDB();
	if(isset($_GET['id']) && is_numeric($_GET['id'])) {
		$id = $_GET['id'];
	} 
	$sql = "SELECT *
			FROM product p
			JOIN material m ON p.material_id = m.material_id
			JOIN product_type pt ON p.product_type_id = pt.product_type_id
			JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
			JOIN category c ON p.category_id = c.category_id
			JOIN section s ON p.section_id = s.section_id
			WHERE product_id = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>

	.product-image {
		width: 250px;         
		height: 250px;       
	}

</style>

			<div role="main" class="main shop pt-4">

				<div class="container">

					<div class="row">
						<div class="col">
							<ul class="breadcrumb breadcrumb-style-2 d-block text-4 mb-4">
								<li><a href="#" class="text-color-default text-color-hover-primary text-decoration-none"><?php echo $row["section_name"]; ?></a></li>
								<li><a href="#" class="text-color-default text-color-hover-primary text-decoration-none"><?php echo $row["category_name"]; ?></a></li>
								<li><?php echo $row["subcategory_name"]; ?></li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5 mb-5 mb-md-0">

							<div class="thumb-gallery-wrapper lightbox" data-plugin-options="{'delegate': 'a.shop-gallery', 'type': 'image', 'gallery': {'enabled': true}, 'mainClass': 'mfp-with-zoom', 'zoom': {'enabled': true, 'duration': 300}}">
								<div class="thumb-gallery-detail owl-carousel owl-theme manual nav-inside nav-style-1 nav-dark mb-3">
									<div>
										<a href="../../media/<?php echo $row['image_url']; ?>" class="shop-gallery" title=""><img alt="" class="img-fluid" src="../../media/<?php echo $row['image_url']; ?>"></a>
									</div>
								</div>
								<div class="thumb-gallery-thumbs owl-carousel owl-theme manual thumb-gallery-thumbs">
									<div class="cur-pointer">
										<img alt="" class="img-fluid" src="../../media/<?php echo $row['image_url']; ?>">
									</div>
								</div>
							</div>

						</div>

						<div class="col-md-7">

							<div class="summary entry-summary position-relative">


								<h1 class="mb-0 font-weight-bold text-7"><?php echo $row['material_name'].' '.$row['product_name'].' '.$row['size_1'].'*'.$row['size_2'].'*'.$row['size_3'].' '.$row['variant']; ?></h1>

								<div class="pb-0 clearfix d-flex align-items-center">
									<div title="Rated 3 out of 5" class="float-start">
										<input type="text" class="d-none" value="3" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'primary', 'size':'xs'}">
									</div>

								</div>

								<div class="divider divider-small">
									<hr class="bg-color-grey-400">
								</div>

								<p class="text-3-5 mb-3"><?php echo $row['description']; ?></p>

									<hr>
									<a href="https://wa.me/+60<?php echo COMPANY_CONTACT;?>?text=Hello,%20I'm%20interested%20in%20your%20product called <?php echo $row['material_name'].' '.$row['product_name'].' '.$row['size_1'].'*'.$row['size_2'].'*'.$row['size_3'].' '.$row['variant']; ?>. Can you provide me with more details?">
									<button type="submit" class="btn btn-dark btn-modern text-uppercase bg-color-hover-primary border-color-hover-primary">Get Your Quote Now!</button>
									</a>
									<hr>

							</div>

						</div>
					</div>

					<div class="row mb-4">
						<div class="col">
							<div id="description" class="tabs tabs-simple tabs-simple-full-width-line tabs-product tabs-dark mb-2">
								<ul class="nav nav-tabs justify-content-start">
									<li class="nav-item"><a class="nav-link active font-weight-bold text-3 text-uppercase py-2 px-3" href="#productDescription" data-bs-toggle="tab">Description</a></li>
									<li class="nav-item"><a class="nav-link font-weight-bold text-3 text-uppercase py-2 px-3" href="#productInfo" data-bs-toggle="tab">Additional Information</a></li>
								</ul>
								<div class="tab-content p-0">
									<div class="tab-pane px-0 py-3 active" id="productDescription">
										<p><?php echo $row['description']; ?></p>
									</div>
									<div class="tab-pane px-0 py-3" id="productInfo">
										<table class="table table-striped m-0">
											<tbody>
												<tr>
													<th class="border-top-0">
														Material:
													</th>
													<td class="border-top-0">
														<?php echo $row['material_name']; ?>
													</td>
												</tr>
												<tr>
													<th>
														Size:
													</th>
													<td>
														<?php echo $row['size_1'].'*'.$row['size_2'].'*'.$row['size_3']; ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<h4 class="font-weight-semibold text-4 mb-3">RELATED PRODUCTS</h4>
							<hr class="mt-0">
							<div class="products row">
								<div class="col">
									<div class="owl-carousel owl-theme nav-style-1 nav-outside nav-outside nav-dark mb-0" data-plugin-options="{'loop': false, 'autoplay': false, 'items': 4, 'nav': true, 'dots': false, 'margin': 20, 'autoplayHoverPause': true, 'autoHeight': true, 'stagePadding': '75', 'navVerticalOffset': '50px'}">

										<?php

											$pdo = openDB();
											$sql = "SELECT *
													FROM product p
													JOIN material m ON p.material_id = m.material_id
													JOIN product_type pt ON p.product_type_id = pt.product_type_id
													JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
													JOIN category c ON p.category_id = c.category_id
													JOIN section s ON p.section_id = s.section_id";
											$stmt = $pdo->query($sql);
											closeDB($pdo);

											while ($row = $stmt->fetch()) {

										?>


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
													<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="product?id=<?php echo $row['product_id']; ?>" class="text-color-dark text-color-hover-primary"><?php echo $row['material_name'].' '.$row['product_name'].' '.$row['size_1'].'*'.$row['size_2'].'*'.$row['size_3'].' '.$row['variant']; ?></a></h3>
												</div>
												<a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
											</div>
											<div title="Rated 5 out of 5">
												<input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
											</div>
											<p class="price text-5 mb-3"></p>
										</div>

										<?php

											}

										?>

									</div>
								</div>
							</div>
						</div>
					</div>

					<hr class="my-5">
			</div>

<?php

	require_once('../include/footer.php');

?>
