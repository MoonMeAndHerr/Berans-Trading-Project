	<body data-plugin-page-transition>

		<div class="body">
            			<header id="header" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyStartAt': 135, 'stickySetTop': '-135px', 'stickyChangeLogo': true}">
				<div class="header-body header-body-bottom-border-fixed box-shadow-none border-top-0">
					<div class="header-container container">
						<div class="header-row py-2">
							<div class="header-column w-100">
								<div class="header-row justify-content-between">
									<div class="header-logo z-index-2 col-lg-2 px-0">
										<a href="index">
											<img alt="Berans" width="100" height="60" data-sticky-width="82" data-sticky-height="40" data-sticky-top="84" src="../../siteidentity/<?php echo WEB_LOGO; ?>">
										</a>
									</div>
									<div class="header-nav-features header-nav-features-no-border p-relative z-index-2 col col-lg-5 col-xl-6 px-0 ms-0">
										<div class="header-nav-feature ps-lg-5 pe-2 pe-lg-4 me-4 me-lg-0">
											<form role="search" action="search-result" method="POST">
												<div class="search-with-select">
													<a href="#" class="mobile-search-toggle-btn text-decoration-none" data-toggle-class="open">
														<i class="icons icon-magnifier text-color-dark text-color-hover-primary"></i>
													</a>
													<div class="search-form-wrapper input-group">
														<input class="form-control text-1" id="headerSearch" name="keyword" type="search" value="" placeholder="Search...">
														<div class="search-form-select-wrapper">
															<div class="custom-select-1 d-none d-lg-block">
																<select name="section" class="form-control form-select">
																	<option value="all" selected>All Categories</option>
                                                                    <?php

                                                                        $pdo = openDB();
                                                                        $sql = "SELECT * FROM section";
                                                                        $stmt = $pdo->query($sql);
                                                                        closeDB($pdo);

                                                                        while ($row = $stmt->fetch()) {
                                                                            
                                                                    ?>
                                                                    <option value="<?php echo $row['section_id']; ?>"><?php echo $row['section_name']; ?></option>
                                                                    <?php

                                                                        }

                                                                    ?>
																</select>
															</div>
															<button class="btn" type="submit" aria-label="Search">
																<i class="icons icon-magnifier header-nav-top-icon text-color-dark"></i>
															</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
									<ul class="header-extra-info col-auto col-lg-3 col-xl-2 ps-2 ps-xl-0 ms-lg-3 d-none d-lg-block">
										<li class="d-none d-sm-inline-flex ms-0">
											<div class="header-extra-info-icon ms-lg-12">
												<i class="icons icon-phone text-3 text-color-dark position-relative top-1"></i>
											</div>
											<div class="header-extra-info-text">
												<label class="text-1 font-weight-semibold text-color-default">Whatsapp Us Now!</label>
												<strong class="text-4"><a href="https://wa.me/+60<?php echo COMPANY_CONTACT;?>?text=Hello,%20I'm%20interested%20in%20your%20services. Can you provide me with more details?" class="text-color-hover-primary text-decoration-none"><?php echo COMPANY_CONTACT; ?></a></strong>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="header-column justify-content-end">
								<div class="header-row"></div>
							</div>
						</div>
					</div>
					<div class="header-nav-bar header-nav-bar-top-border bg-light p-relative z-index-1">
						<div class="header-container container">
							<div class="header-row">
								<div class="header-column">
									<div class="header-row justify-content-end">
										<div class="header-nav header-nav-line header-nav-top-line header-nav-top-line-with-border justify-content-start" data-sticky-header-style="{'minResolution': 991}" data-sticky-header-style-active="{'margin-left': '105px'}" data-sticky-header-style-deactive="{'margin-left': '0'}">
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
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>