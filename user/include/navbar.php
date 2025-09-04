	<body data-plugin-page-transition>

		<div class="body">
			<header id="header" class="header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': false, 'stickyEnableOnMobile': false, 'stickyStartAt': 70, 'stickyChangeLogo': false, 'stickyHeaderContainerHeight': 70}">
				<div class="header-body border-top-0 box-shadow-none">
					<div class="header-container header-container-md container">
						<div class="header-row">
							<div class="header-column">
								<div class="header-row">
									<div class="header-logo">
										<a href="index.php"><img alt="Berans" width="100" height="60" data-sticky-width="82" data-sticky-height="40" data-sticky-top="0" src="<?php echo "../../media/". WEB_LOGO ?>"></a>
									</div>
								</div>
							</div>
							<div class="header-column justify-content-end">
								<div class="header-row">
									<div class="header-nav header-nav-line header-nav-bottom-line header-nav-bottom-line-no-transform header-nav-bottom-line-active-text-dark header-nav-bottom-line-effect-1 order-2 order-lg-1">
										<div class="header-nav-main header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-2 header-nav-main-sub-effect-1">
											<nav class="collapse">
												<ul class="nav nav-pills" id="mainNav">
													<li class="dropdown">
														<a class="dropdown-item dropdown-toggle" href="index">
															Home
														</a>
													</li>
													<li class="dropdown">
														<a class="dropdown-item dropdown-toggle" href="about-us">
															About Us
														</a>
													</li>
													<li class="dropdown">
														<a class="dropdown-item dropdown-toggle" href="product-list">
															Catalogue
														</a>
														<ul class="dropdown-menu">

															<?php

																$pdo = openDB();
																
																$sectionsStmt = $pdo->query("SELECT section_id, section_name FROM section ORDER BY section_name");
																$sections = $sectionsStmt->fetchAll(PDO::FETCH_ASSOC);

																$categoriesStmt = $pdo->query("SELECT category_id, category_name, section_id FROM category ORDER BY section_id, category_name");
																$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

																$subcategoriesStmt = $pdo->query("SELECT subcategory_id, subcategory_name, category_id FROM subcategory ORDER BY category_id, subcategory_name");
																$subcategories = $subcategoriesStmt->fetchAll(PDO::FETCH_ASSOC);

																closeDB($pdo);

																$categoryMap = [];
																foreach ($categories as $category) {
																	$category['subcategories'] = [];
																	$categoryMap[$category['category_id']] = $category;
																}

																foreach ($subcategories as $subcat) {
																	$categoryMap[$subcat['category_id']]['subcategories'][] = $subcat;
																}

																$sectionMap = [];
																foreach ($sections as $section) {
																	$section['categories'] = [];
																	$sectionMap[$section['section_id']] = $section;
																}

																foreach ($categoryMap as $cat) {
																	$sectionMap[$cat['section_id']]['categories'][] = $cat;
																}

																// Re-index to use in view
																$menuStructure = array_values($sectionMap);


															 
															?>

															<?php foreach ($sections as $section): ?>
																<li class="dropdown-submenu">
																	<a class="dropdown-item" href="product-list?sectionid=<?= htmlspecialchars($section['section_id']) ?>"><?= htmlspecialchars($section['section_name']) ?></a>
																	<ul class="dropdown-menu">
																		<?php foreach ($categories as $category): ?>
																			<?php if ($category['section_id'] == $section['section_id']): ?>
																				<li class="dropdown-submenu">
																					<a class="dropdown-item" href="product-list?categoryid=<?= htmlspecialchars($category['category_id']) ?>"><?= htmlspecialchars($category['category_name']) ?></a>
																					<ul class="dropdown-menu">
																						<?php foreach ($subcategories as $subcategory): ?>
																							<?php if ($subcategory['category_id'] == $category['category_id']): ?>
																								<li>
																									<a class="dropdown-item" href="product-list?subcategoryid=<?= htmlspecialchars($subcategory['subcategory_id']) ?>"><?= htmlspecialchars($subcategory['subcategory_name']) ?></a>
																								</li>
																							<?php endif; ?>
																						<?php endforeach; ?>
																					</ul>
																				</li>
																			<?php endif; ?>
																		<?php endforeach; ?>
																	</ul>
																</li>
															<?php endforeach; ?>
														</ul>
													</li>
													<li class="dropdown">
														<a class="dropdown-item dropdown-toggle" href="contact-us.php">
															Contact Us
														</a>
													</li>
												</ul>
											</nav>
										</div>
										<button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
											<i class="fas fa-bars"></i>
										</button>
									</div>
									<div class="header-nav-features header-nav-features-no-border header-nav-features-lg-show-border order-1 order-lg-2">
										<div class="header-nav-feature header-nav-features-search d-inline-flex">
											<a href="#" class="header-nav-features-toggle text-decoration-none" data-focus="headerSearch" aria-label="Search"><i class="fas fa-search header-nav-top-icon"></i></a>
											<div class="header-nav-features-dropdown header-nav-features-dropdown-mobile-fixed" id="headerTopSearchDropdown">
												<form role="search" action="search-result" method="POST">
													<div class="simple-search input-group">
														<input class="form-control text-1" id="headerSearch" name="keyword" type="search" value="" placeholder="Search...">
														<button class="btn" type="submit" aria-label="Search">
															<i class="fas fa-search header-nav-top-icon text-color-dark"></i>
														</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>