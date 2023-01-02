
<?php
//UPDATE DESCRIPTION
try {
  // Load the XML file and parse it into a SimpleXML object
  $xml = simplexml_load_file('feed.xml');
  
  // Get the total number of products
  $total = count($xml->product);
  
  // Initialize a counter for the number of products in the Αφίσες category
  $count = 0;
  
  // Iterate over each product
  foreach ($xml->product as $product) {
    // Check if the product is in the Αφίσες category
    $category = (string)$product->category;
    if (stripos($category, 'Αφίσες') !== false) {
      // Increment the counter
      $count++;
      
      // Set the new description for the product
      $product->description = '<![CDATA[[rev_slider alias="slider-1"][/rev_slider]]]>';
    }
    if (stripos($category, 'Πίνακες') !== false) {
      // Increment the counter
      $count++;
      
      // Set the new description for the product
      $product->description = '<![CDATA[[rev_slider alias="slider-3"][/rev_slider]]]>';
    }
    if (stripos($category, 'Ζωγραφική σύμφωνα με αριθμούς') !== false) {
      // Increment the counter
      $count++;
      
      // Set the new description for the product
      $product->description = '<![CDATA[[rev_slider alias="description-poster-1-1"][/rev_slider]]]>';
    }
    if (stripos($category, 'Χειροποίητα ζωγραφισμένοι πίνακες') !== false) {
      // Increment the counter
      $count++;
      
      // Set the new description for the product
      $product->description = '<![CDATA[[rev_slider alias="description-poster-11"][/rev_slider]]]>';
    }
    if (stripos($category, 'διαχωριστικά') !== false) {
      // Increment the counter
      $count++;
      
      // Set the new description for the product
      $product->description = '<![CDATA[[rev_slider alias="description-poster-12"][/rev_slider]]]>';
    }
    
    // Calculate and print the percentage of products that have been processed
    $percentage = round(($count / $total) * 100);
    echo "Processed $count of $total products ($percentage%)\n";
  }
  
  // Save the modified XML object back to the file
  $xml->asXML('feed.xml');
} catch (Exception $e) {
  // Print an error message if there was an exception
  echo "An error occurred: " . $e->getMessage();
}
?>
