<?php
// UPDATE PRICE

// Load the XML file
$xml = simplexml_load_file('feed.xml');

// Iterate through each product element
foreach ($xml->product as $product) {
  // Calculate the suggested price based on the price_net element
	if ($product->price_net < 20) {
	  $suggested_price = ((floatval($product->price_net) * 1.23) + 11) * 1.2;
	} elseif ($product->price_net >= 20 && $product->price_net <= 40) {
	  $suggested_price = ((floatval($product->price_net) * 1.23) + 15) * 1.2;
	} elseif ($product->price_net >= 50 && $product->price_net <= 90) {
	  $suggested_price = ((floatval($product->price_net) * 1.23) + 18) * 1.2;
	} else {
	  $suggested_price = ((floatval($product->price_net) * 1.23) + 20) * 1.2;
	}
  if (isset($product->suggested_price)) {
    // Update the value of the existing suggested_price element
    $product->suggested_price = $suggested_price;
  } else {
    // Create a new suggested_price element and add it after the price_net element
    $new_suggested_price = $product->addChild('suggested_price', $suggested_price);
    $price_net = $product->price_net;
    $dom_product = dom_import_simplexml($product);
    $dom_new_suggested_price = $dom_product->insertBefore(
      $dom_product->ownerDocument->importNode(dom_import_simplexml($new_suggested_price), true),
      dom_import_simplexml($price_net)->nextSibling
    );
  }
  
  // Iterate through each variant element
  foreach ($product->variants->variant as $variant) {
    // Calculate the suggested price based on the price_net element
	if ($variant->price_net < 20) {
	  $suggested_price = ((floatval($variant->price_net) * 1.23) + 11) * 1.2;
	} elseif ($variant->price_net >= 20 && $variant->price_net <= 40) {
	  $suggested_price = ((floatval($variant->price_net) * 1.23) + 15) * 1.2;
	} elseif ($variant->price_net >= 40 && $variant->price_net <= 90) {
	  $suggested_price = ((floatval($variant->price_net) * 1.23) + 18) * 1.2;
	} else {
	  $suggested_price = ((floatval($variant->price_net) * 1.23) + 21) * 1.2;
	}
    if (isset($variant->suggested_price)) {
      // Update the value of the existing suggested_price element
      $variant->suggested_price = $suggested_price;
    } else {
      // Create a new suggested_price element and add it after the price_net element
      $new_suggested_price = $variant->addChild('suggested_price', $suggested_price);
      $price_net = $variant->price_net;
      $dom_variant = dom_import_simplexml($variant);
      $dom_new_suggested_price = $dom_variant->insertBefore(
        $dom_variant->ownerDocument->importNode(dom_import_simplexml($new_suggested_price), true),
        dom_import_simplexml($price_net)->nextSibling
      );
    }
  }
}

// Save the modified XML file
$xml->asXML('feed.xml');

echo "Suggested prices added/updated in XML file successfully.";
?>

