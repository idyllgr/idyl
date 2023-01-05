<img alt="" src="https://cdn.shopify.com/s/files/1/0675/6928/7480/files/75.png?v=1668132496" style="float:right" />
<b>Small XML work for product feed<br/></b> 
Change description, price update, find colors etc</p>
<br>
<b>Basic manipulation XML</b> <br>
1. Change specific areas from a XML using php. <br>
2. Calculate with your own formula some places of the XML. <br>
<b>Advanced Color </b><br>
In case products dont have a color for your thousand images on a XML file you can find it easily with the above php. <br>
<i>Keep in mind:</i><br>
use of a library<br> Install: <b>composer require ksubileau/color-thief-php</b>
<br>
<b>Modify code at your needs </b>

DOC: <br> <br>
Defines an array of colors stored as hex codes. <br>
Defines a function colorDistance that calculates the distance between two colors based on their red, green, and blue values. <br>
Tries to load an XML file called feed.xml. If it fails, it will print an error message. <br>
Loads the ColorThief library. If it fails, it will print an error message. <br>
Gets the total number of products in the XML file and stores it in the variable $total. <br>
Loops through each product in the XML file. For each product, it does the following: <br>
Tries to get the URL of the first image for the product. If the product doesn't have any images, it tries to get the first image for the first variant of the product  <br>instead. If the product or variant doesn't have any images, it sets the image URL to null. <br>
If the image URL is null or the image doesn't exist, it continues to the next product. <br>
Uses the ColorThief library to extract the 5 dominant colors in the image. <br>
Maps the extracted colors to hex codes and stores them in the array $paletteColors. <br>
For each color in $paletteColors, it finds the closest color in the array of colors defined at the beginning of the script. It stores all the closest colors in the array $closestColors. <br>
It sets the variable $colorToUse to the first element in $closestColors. <br>
It gets the unique colors in $closestColors and stores them in the array $uniqueColors. <br>
It updates the product's color field to be the value of $colorToUse <br>.
It increments the variable $num by 1. <br>
At the end, it prints the number of products that have been updated. <br>
