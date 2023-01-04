<?php
// Define the array of colors
$colors = [
'#808080', # Grey
'#A52A2A', # Brown
'#FFFF00', # Yellow
'#008000', # Green
'#FFFFFF', # White
'#F5F5DC', # Beige
'#0000FF', # Blue
'#FFC0CB', # Pink
'#FFFFF0', # Cream
'#EE82EE', # Violet
'#FFD700', # Gold
'#800080', # Navy Blue
'#87CEEB', # Sky Blue
'#40E0D0', # Turquoise
'#8B4513', # Sepia
'#C0C0C0', # Silver
'#B87333', # Copper
'#FFE4C4', # Pearl
'#800000' # Burgundy
];

function colorDistance($color1, $color2) {
  $r1 = hexdec(substr($color1, 1, 2));
  $g1 = hexdec(substr($color1, 3, 2));
  $b1 = hexdec(substr($color1, 5, 2));
  $r2 = hexdec(substr($color2, 1, 2));
  $g2 = hexdec(substr($color2, 3, 2));
  $b2 = hexdec(substr($color2, 5, 2));
  $distance = sqrt(pow($r2 - $r1, 2) + pow($g2 - $g1, 2) + pow($b2 - $b1, 2));
  return $distance;
}
$num = 0;
try {
  $xml = simplexml_load_file('feed.xml');
} catch (Exception $e) {
  echo "Error loading XML file: " . $e->getMessage();
}

// Load the ColorThief library
try {
  require 'vendor/autoload.php';
} catch (Exception $e) {
  // Handle the error if the ColorThief library cannot be loaded
  echo "Error loading ColorThief library: " . $e->getMessage();
}

use ColorThief\ColorThief;
$total = count($xml->product); // Get the total number of products
  echo $total;
foreach ($xml->product as $product) {

 $closestColors = [];
  if (isset($product->images)) {
    $imageUrl = (string)$product->images->image[0];
  } else {
    // If the product doesn't have any images, try to get the first image for the first variant
    if (isset($product->variants->variant[0]->images)) {
      $imageUrl = (string)$product->variants->variant[0]->images->image[0];
    } else {
      $imageUrl = null;
    }
  }

  if ($num > $total) {
    break;
  }
  if (!$imageUrl || !@getimagesize($imageUrl)) {
    continue;
  }

  try {
    $palette = ColorThief::getPalette($imageUrl, 5);
  } catch (Exception $e) {
    echo "Error extracting palette for image " . $imageUrl . ": " . $e->getMessage();
    continue;
  }

	$paletteColors = array_map(function($color) {
	return sprintf('#%02x%02x%02x', $color[0], $color[1], $color[2]);
	}, $palette);

	foreach ($paletteColors as $paletteColor) {
	$minDistance = PHP_INT_MAX;
	$closestColor = null;
	foreach ($colors as $color) {
	$distance = colorDistance($paletteColor, $color);
	if ($distance < $minDistance) {
	$minDistance = $distance;
	$closestColor = $color;
	}
	}
	$closestColors[] = $closestColor;
	}

	// Choose the first closest color
	$colorToUse = $closestColors[0];

  $num = $num + 1;
  $uniqueColors = array_unique($closestColors);
  $domProduct = dom_import_simplexml($product);
  $colorIdyll = $domProduct->ownerDocument->createElement('color_idyll', implode(',', $uniqueColors));
  $domProduct->appendChild($colorIdyll);
  echo $num . "%\n";
  $num++;
}

try {
  $xml->asXML('modified-products.xml');
} catch (Exception $e) {
  echo "Error saving XML file: " . $e->getMessage();
}

?>
