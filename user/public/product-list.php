<?php

	require_once('../include/header.php');
	require_once('../include/navbar.php');

?>

<style>
.product-image {
	width: 250px;         
	height: 250px;       
}
</style>
			<div role="main" class="main shop pt-4">

				<div class="container">

					<div class="masonry-loader masonry-loader-showing">
						<div class="row products product-thumb-info-list" data-plugin-masonry data-plugin-options="{'layoutMode': 'fitRows'}">

							<?php

								if(isset($_GET['sectionid'])) {

									$pdo = openDB();
									$rule = $_GET['sectionid'];
									$sql = "SELECT * FROM product INNER JOIN section on product.section_id = section.section_id WHERE product.section_id = :rule ";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['rule' => $rule]);
									closeDB($pdo);

								} elseif(isset($_GET['categoryid'])) {

									$pdo = openDB();
									$rule = $_GET['categoryid'];
									$sql = "SELECT * FROM product INNER JOIN section on product.section_id = section.section_id WHERE product.category_id = :rule ";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['rule' => $rule]);
									closeDB($pdo);

									
								} elseif(isset($_GET['subcategoryid'])) {

									$pdo = openDB();
									$rule = $_GET['subcategoryid'];
									$sql = "SELECT * FROM product INNER JOIN section on product.section_id = section.section_id WHERE product.subcategory_id = :rule ";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['rule' => $rule]);
									closeDB($pdo);

								} else {

									$pdo = openDB();
									$sql = "SELECT * FROM product INNER JOIN section on product.section_id = section.section_id";
									$stmt = $pdo->query($sql);
									closeDB($pdo);

								}

								while ($row = $stmt->fetch()) {

							
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

<?php

	require_once('../include/alt-footer.php');

?>
