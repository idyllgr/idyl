<?php
/*
Plugin Name: Invoice PDF Attachment
Description: Sends a PDF with the invoice details to the customer via email.
Version: 1.0
Author: IDYL LTD
*/

add_action( 'woocommerce_email_attachments', 'invoice_pdf_attachment', 10, 3 );
function invoice_pdf_attachment( $attachments, $email_id, $order ) {
  if ( $email_id == 'customer_invoice' ) {
    // Create the PDF file
    $pdf_file = create_invoice_pdf( $order );
    // Add the PDF file as an attachment
    $attachments[] = $pdf_file;
    // Delete the PDF file after it has been emailed
    unlink( $pdf_file );
  }
  return $attachments;
}

function create_invoice_pdf( $order ) {
  // Include the FPDF library
  require_once('fpdf.php');
  
  // Create a new PDF document
  $pdf = new FPDF();
  
  // Add a page
  $pdf->AddPage();
  
  // Set the font and size
  $pdf->SetFont('Arial', 'B', 16);
  
  // Add the invoice number to the PDF
  $pdf->Cell(0, 10, 'Invoice #' . $order->get_order_number(), 0, 1);
  
  // Add the customer name to the PDF
  $pdf->Cell(0, 10, 'Customer: ' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(), 0, 1);
  
  // Add the order date to the PDF
  $pdf->Cell(0, 10, 'Date: ' . date_i18n( get_option( 'date_format' ), strtotime( $order->get_date_created() ) ), 0, 1);
  
  // Add a line break
  $pdf->Ln(10);
  
  // Set the font and size
  $pdf->SetFont('Arial', 'B', 12);
  
  // Add the table header cells
  $pdf->Cell(40, 10, 'Product', 1, 0, 'C');
  $pdf->Cell(40, 10, 'Quantity', 1, 0, 'C');
  $pdf->Cell(40, 10, 'Price', 1, 0, 'C');
  $pdf->Cell(40, 10, 'Total', 1, 1, 'C');
  
  // Set the font and size
  $pdf->SetFont('Arial', '', 12);
  
  // Add the order items to the table
  foreach ( $order->get_items() as $item ) {
    $product = $item->get_product();
    $quantity = $item->get_quantity();
    $price = $item->get_total();
    $total = $item->get_total();
    $pdf->Cell(40, 10, $product->get_name(), 1, 0, 'C');
    $pdf->Cell(40, 10, $quantity, 1, 0, 'C');
    $pdf->Cell(40, 10, wc_price( $price ), 1, 0, 'C');
    $pdf->Cell(40, 10, wc_price( $total ), 1, 1, 'C');
  }
  
  // Add the order total to the
  // Add the order total to the PDF
  $pdf->Cell(0, 10, 'Order Total: ' . $order->get_formatted_order_total(), 0, 1);
  
  // Get the custom PDF filename, or use a default value if it is not set
  $filename = get_option( 'invoice_pdf_filename', 'invoice.pdf' );
  
  // Save the PDF to the plugin folder
  $pdf->Output( dirname( __FILE__ ) . '/' . $filename, 'F' );
  
  // Return the PDF file path
  return dirname( __FILE__ ) . '/' . $filename;
}

