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
if (!is_dir($cacheDir)) {
  mkdir($cacheDir);
}
// Function to calculate the distance between two colors in RGB space
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
  // Handle the error if the XML file cannot be loaded
  echo "Error loading XML file: " . $e->getMessage();
  exit;
}

// Load the ColorThief library
try {
  require 'vendor/autoload.php';
} catch (Exception $e) {
  // Handle the error if the ColorThief library cannot be loaded
  echo "Error loading ColorThief library: " . $e->getMessage();
  exit;
}

use ColorThief\ColorThief;
$total = count($xml->product); // Get the total number of products
  echo $total;
// Iterate over each product in the XML file
foreach ($xml->product as $product) {
  $ttl = 3600;
  $cacheFile = 'cache/' . md5($product->id) . '.cache';
  if (file_exists($cacheFile) && time() - $ttl < filemtime($cacheFile)) {
  // Read the cached output and add it to the $closestColors array
  $closestColors = unserialize(file_get_contents($cacheFile));
  // Skip the rest of the loop
  continue;
  }
  if (isset($product->images)) {
    $imageUrl = (string)$product->images->image[0];
  } else {
    $imageUrl = null;
  }
  // If the image doesn't exist or the URL is not accessible, skip this product
  if (!$imageUrl || !@getimagesize($imageUrl)) {
    continue;
  }
  if ($num > 15000) {
    break;
  }
  // Get the color palette for the image
  try {
    $palette = ColorThief::getPalette($imageUrl, 5);
  } catch (Exception $e) {
    // Handle the error if the palette cannot be extracted
    echo "Error extracting palette for image " . $imageUrl . ": " . $e->getMessage();
    continue;
  }


	$paletteColors = array_map(function($color) {
	  return sprintf('#%02x%02x%02x', $color[0], $color[1], $color[2]);
	}, $palette);

	// Find the closest color in the array for each color in the palette
	$closestColors = [];
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
  // Extract the hex values for the colors in the palette
  $colors = array_map(function($color) {
    return sprintf('#%02x%02x%02x', $color[0], $color[1], $color[2]);
  }, $palette);
  file_put_contents($cacheFile, serialize($closestColors));
  // Save the colors as a comma-separated list in the <producer> element
  $num = $num + 1;
  $product->pattern = implode(',', $closestColors);
  echo $num . "" . $imageUrl . "" . $closestColors . "\n";
  $num++;
}

// Save the modified XML file
try {
  $xml->asXML('modified-products.xml');
} catch (Exception $e) {
  // Handle the error if the XML file cannot be saved
  echo "Error saving XML file: " . $e->getMessage();
  exit;
}

?>
