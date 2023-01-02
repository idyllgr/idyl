<?php

try {
    $xml = simplexml_load_file("feed.xml");
	
foreach ($xml->product as $product) {
    $price = (float)$product->suggested_price;
    $price = round($price, 0);
    $product->suggested_price = $price;

    if ($product->variants !== null && $product->variants->variant !== null) {
        foreach ($product->variants->variant as $variant) {
            $price = (float)$variant->suggested_price;
            $price = round($price, 0);
            $variant->suggested_price = $price;
        }
    } else {
       echo "WRONG";
    }
}
	

    if ($xml->product !== null) {
        foreach ($xml->product as $product) {
            $price = (float)$product->suggested_price;
            switch ($price) {
                case $price >= 110 && $price <= 115:
                    $price = 109.9;
                    break;
                case $price >= 100 && $price <= 101:
                    $price = 99.9;
                    break;
                case $price >= 90 && $price <= 91:
                    $price = 89.9;
                    break;
                case $price >= 80 && $price <= 82:
                    $price = 79.9;
                    break;
                case $price >= 70 && $price <= 72:
                    $price = 69.9;
                    break;
                case $price >= 60 && $price <= 62:
                    $price = 59.9;
                    break;
                case $price >= 50 && $price <= 51:
                    $price = 49.9;
                    break;
                case $price >= 40 && $price <= 43:
                    $price = 39.9;
                    break;
                case $price >= 30 && $price <= 31:
                    $price = 29.9;
                    break;
                case $price >= 20 && $price <= 21:
                    $price = 19.9;
                    break;
            }
            $product->suggested_price = $price;

            if ($product->variants->variant !== null) {
                foreach ($product->variants->variant as $variant) {
                    $price = (float)$variant->suggested_price;
                    switch ($price) {
                        case $price >= 110 && $price <= 115:
                            $price = 109.9;
                            break;
                        case $price >= 100 && $price <= 101:
                            $price = 99.9;
                            break;
                        case $price >= 90 && $price <= 91:
                            $price = 89.9;
                            break;
                        case $price >= 80 && $price <= 82:
                            $price = 79.9;
                            break;
                        case $price >= 70 && $price <= 72:
                            $price = 69.9;
                            break;
                        case $price >= 60 && $price <= 62:
                            $price = 59.9;
                            break;
                        case $price >= 50 && $price <= 52:
                            $price = 49.9;
                            break;
                        case $price >= 40 && $price <= 43:
                            $price = 39.9;
                            break;
                        case $price >= 30 && $price <= 31:
                            $price = 29.9;
                            break;
                        case $price >= 20 && $price <= 21:
                            $price = 19.9;
                            break;
                    }
                    $variant->suggested_price = $price;
                }
            } else {
           }
        }
    } else {
        // handle the case where $xml->product is null
    }
    $xml->asXML('feed.xml');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>
