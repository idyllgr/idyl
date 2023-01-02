<?php
// DOWNLOAD FEED.XML
try {
  // Set the file URL
  $file_url = "URL_HERE";

  // Set the file path
  $file_path = 'feed.xml';

  // Download the file
  $new_file = file_get_contents($file_url);

  // Delete the previous version of the file
  unlink($file_path);

  // Save the new version of the file
  file_put_contents($file_path, $new_file);
  echo "file downloaded feed.xml";
} catch (Exception $e) {
  // handle the exception
  echo "An error occurred: " . $e->getMessage();
}
?>
