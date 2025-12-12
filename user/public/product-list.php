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
						$pdo = openDB(); // Open connection once at the start

						// 1. Initialize variables
						$filterType = '';
						$filterId = 0;
						$customListItems = '';
						$orderByClause = "ORDER BY p.product_id DESC"; // Default sorting (Newest first)

						// 2. Determine what page we are on
						if (isset($_GET['sectionid'])) {
							$filterType = 'section';
							$filterId = $_GET['sectionid'];
						} elseif (isset($_GET['categoryid'])) {
							$filterType = 'category';
							$filterId = $_GET['categoryid'];
						} elseif (isset($_GET['subcategoryid'])) {
							$filterType = 'subcategory';
							$filterId = $_GET['subcategoryid'];
						}

						// 3. Check for a Custom Folder Arrangement
						if ($filterId > 0) {
							$folderSql = "";
							
							// We look for a folder that matches the specific section/category/subcategory
							if ($filterType == 'section') {
								$folderSql = "SELECT listItems FROM folder_catalogue WHERE section_id = ? AND category_id IS NULL AND subcategory_id IS NULL LIMIT 1";
							} elseif ($filterType == 'category') {
								$folderSql = "SELECT listItems FROM folder_catalogue WHERE category_id = ? AND subcategory_id IS NULL LIMIT 1";
							} elseif ($filterType == 'subcategory') {
								$folderSql = "SELECT listItems FROM folder_catalogue WHERE subcategory_id = ? LIMIT 1";
							}

							if ($folderSql) {
								$stmtFolder = $pdo->prepare($folderSql);
								$stmtFolder->execute([$filterId]);
								$folderRow = $stmtFolder->fetch(PDO::FETCH_ASSOC);

								if ($folderRow && !empty($folderRow['listItems'])) {
									$customListItems = $folderRow['listItems']; // e.g., "15,2,9"

									// 4. Create the Custom Sorting Logic
									// Logic: 
									// 1. CASE WHEN... checks if product is in our list. If yes, give it priority 0 (top). If no, priority 1 (bottom).
									// 2. FIELD(...) sorts the top items exactly as they appear in the ID list.
									$orderByClause = "ORDER BY 
													CASE WHEN p.product_id IN ($customListItems) THEN 0 ELSE 1 END, 
													FIELD(p.product_id, $customListItems)";
								}
							}
						}

						// 5. Build the Main Query
						$sql = "SELECT p.*, m.material_name, pt.product_name, s.section_name, 
									sc.subcategory_name, c.category_name
								FROM product p
								JOIN material m ON p.material_id = m.material_id
								JOIN product_type pt ON p.product_type_id = pt.product_type_id
								JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
								JOIN category c ON p.category_id = c.category_id
								JOIN section s ON p.section_id = s.section_id 
								WHERE p.visibility = 'Shown' 
								AND p.deleted_at IS NULL ";

						// Append the specific filter rule
						if ($filterType == 'section') {
							$sql .= " AND p.section_id = :rule ";
						} elseif ($filterType == 'category') {
							$sql .= " AND p.category_id = :rule ";
						} elseif ($filterType == 'subcategory') {
							$sql .= " AND p.subcategory_id = :rule ";
						}

						// Append the Sorting Order
						$sql .= $orderByClause;

						// 6. Execute Query
						if ($filterType) {
							// Specific Filter View
							$stmt = $pdo->prepare($sql);
							$stmt->execute(['rule' => $filterId]);
						} else {
							// Catch-all (View All)
							$sql = "SELECT p.*, m.material_name, pt.product_name, s.section_name, 
									sc.subcategory_name, c.category_name
								FROM product p
								JOIN material m ON p.material_id = m.material_id
								JOIN product_type pt ON p.product_type_id = pt.product_type_id
								JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
								JOIN category c ON p.category_id = c.category_id
								JOIN section s ON p.section_id = s.section_id 
								WHERE p.visibility = 'Shown' AND p.deleted_at IS NULL
								ORDER BY p.product_id DESC"; // Default view
							$stmt = $pdo->query($sql);
						}

						// 7. Display Results
						while ($row = $stmt->fetch()) {
							$images = $row['image_url'];
							$imageArray = explode(',', $images);
							$coverImage = trim($imageArray[0]); 
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
											<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0">
												<a href="product?id=<?php echo $row['product_id']; ?>" class="text-color-dark text-color-hover-primary">
													<?php echo $row['product_code'].' | '.$row['material_name'].' '.$row['product_name'].' '.$row['size_1'].'*'.$row['size_2'].'*'.$row['size_3'].' '.$row['variant']; ?>
												</a>
											</h3>
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
