<?php

	require_once('../include/header.php');
	require_once('../include/alt-navbar.php');

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
									$sql = "SELECT *
											FROM product p
											JOIN material m ON p.material_id = m.material_id
											JOIN product_type pt ON p.product_type_id = pt.product_type_id
											JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
											JOIN category c ON p.category_id = c.category_id
											JOIN section s ON p.section_id = s.section_id WHERE visibility = 'Shown' AND p.section_id = :rule AND deleted_at IS NULL";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['rule' => $rule]);
									closeDB($pdo);

								} elseif(isset($_GET['categoryid'])) {

									$pdo = openDB();
									$rule = $_GET['categoryid'];
									$sql = "SELECT *
											FROM product p
											JOIN material m ON p.material_id = m.material_id
											JOIN product_type pt ON p.product_type_id = pt.product_type_id
											JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
											JOIN category c ON p.category_id = c.category_id
											JOIN section s ON p.section_id = s.section_id WHERE visibility = 'Shown' AND p.category_id = :rule AND deleted_at IS NULL";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['rule' => $rule]);
									closeDB($pdo);

									
								} elseif(isset($_GET['subcategoryid'])) {

									$pdo = openDB();
									$rule = $_GET['subcategoryid'];
									$sql = "SELECT *
											FROM product p
											JOIN material m ON p.material_id = m.material_id
											JOIN product_type pt ON p.product_type_id = pt.product_type_id
											JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
											JOIN category c ON p.category_id = c.category_id
											JOIN section s ON p.section_id = s.section_id WHERE visibility = 'Shown' AND p.subcategory_id = :rule AND deleted_at IS NULL";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['rule' => $rule]);
									closeDB($pdo);

								} else {

									$pdo = openDB();
									$sql = "SELECT *
											FROM product p
											JOIN material m ON p.material_id = m.material_id
											JOIN product_type pt ON p.product_type_id = pt.product_type_id
											JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
											JOIN category c ON p.category_id = c.category_id
											JOIN section s ON p.section_id = s.section_id WHERE visibility = 'Shown' AND  deleted_at IS NULL";
									$stmt = $pdo->query($sql);
									closeDB($pdo);

								}

								while ($row = $stmt->fetch()) {

									$images = $row['image_url']; // e.g. from your query
									$imageArray = explode(',', $images);
									$coverImage = trim($imageArray[0]); // Get first image and trim any spaces

							
							?>

							

							<div class="col-12 col-sm-6 col-lg-3">
								<div class="product mb-0">
									<div class="product-thumb-info border-0 mb-3">

										<div class="product-thumb-info-badges-wrapper">
										<span class="badge badge-ecommerce text-bg-success">NEW</span>

										</div>

										<a href="product?id=<?php echo $row['product_id']; ?>">
											<div class="product-thumb-info-image">
												<img alt="" class="img-fluid product-image" src="../../media/<?php echo $coverImage; ?>">
											</div>
										</a>
									</div>
									<div class="d-flex justify-content-between">
										<div>
											<a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1"><?php echo $row['section_name']; ?></a>
											<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="product?id=<?php echo $row['product_id']; ?>" class="text-color-dark text-color-hover-primary"><?php echo $row['product_code'].' | '.$row['material_name'].' '.$row['product_name'].' '.$row['size_1'].'*'.$row['size_2'].'*'.$row['size_3'].' '.$row['variant']; ?></a></h3>
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
