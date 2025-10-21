<?php

    require_once __DIR__ . '/../../global/main_configuration.php';
    require_once __DIR__ . '/auth_check.php';
    use League\OAuth2\Client\Provider\GenericProvider;
    use GuzzleHttp\Client;

    $pdo = openDB();

    if(isset($_GET['delete_img'])){

        $product_id = $_GET['prod_id'];
        $imageToDelete = $_GET['delete_img'];
        $stmt = $pdo->prepare("SELECT image_url FROM product WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $allImg = $row['image_url'];

        function removeImageFromList($imgUrl, $imageToDelete, $product_id) {
            // Step 1: Convert string to array
            $images = explode(',', $imgUrl);

            // Step 2: Remove the image
            $filteredImages = array_filter($images, function($img) use ($imageToDelete) {
                return trim($img) !== $imageToDelete;
            });

            // Step 3: Re-index and re-convert to string
            $result = implode(',', array_values($filteredImages));

            $pdo = openDB();

            $updateStmt = $pdo->prepare("UPDATE product SET image_url = ? WHERE product_id = ?");
            $updateStmt->execute([$result, $product_id]);
        }

        $newImgUrl = removeImageFromList($allImg, $imageToDelete, $product_id);
        header("Location: ../public/forms-update-image.php?product_id={$product_id}");


    }

    if(isset($_POST['update_img'])){

        $pdo = openDB();

        $uploadDir = '../../media/';
        $all_images = [];

        $product_id = $_POST['prod_id'];

        $stmt = $pdo->prepare("SELECT image_url FROM product WHERE product_id = ?");
        $stmt->execute([$_POST['prod_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $existing_images = $row['image_url'];
        $existing_array = explode(',', $existing_images);

        // Split existing image array
        $existing_cover = $existing_array[0] ?? '';
        $existing_products = array_slice($existing_array, 1);

        /* -------------------- 2️⃣ Handle Cover Image -------------------- */
        if (!empty($_FILES['image']['name'])) {
            // New cover uploaded
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $cover_name = 'product_cover_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $cover_name);
        } else {
            // Keep existing cover
            $cover_name = $existing_cover;
        }

        /* -------------------- 3️⃣ Handle Product Images -------------------- */
        $new_product_images = [];
        if (!empty($_FILES['listimg']['name'][0])) {
            foreach ($_FILES['listimg']['name'] as $key => $filename) {
                if (!empty($filename)) {
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $img_name = 'product_' . time() . '_' . $key . '.' . $ext;
                    move_uploaded_file($_FILES['listimg']['tmp_name'][$key], $uploadDir . $img_name);
                    $new_product_images[] = $img_name;
                }
            }
        }

        // Case 1: Only cover updated (no new product images)
        if (empty($new_product_images) && !empty($_FILES['image']['name'])) {
            $final_product_images = $existing_products;
        }

        // Case 2: Only product images updated (no new cover)
        elseif (!empty($new_product_images) && empty($_FILES['image']['name'])) {
            $final_product_images = array_merge($existing_products, $new_product_images);
        }

        // Case 3: Both cover and product images updated (append mode)
        elseif (!empty($new_product_images) && !empty($_FILES['image']['name']) ) {
            $final_product_images = array_merge($existing_products, $new_product_images);
        }

        // Default fallback — no changes
        else {
            $final_product_images = $existing_products;
        }

        /* -------------------- 5️⃣ Merge and Update -------------------- */
        $all_images = array_merge([$cover_name], $final_product_images);
        $images_string = implode(',', $all_images);

        $stmt = $pdo->prepare("UPDATE product SET image_url = ? WHERE product_id = ?");
        $stmt->execute([$images_string, $_POST['prod_id']]);

        header("Location: ../public/forms-update-image.php?product_id={$product_id}");


    }


?>