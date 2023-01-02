<?php

$filename = 'feed.xml';

// Read the contents of the file into a string
$contents = file_get_contents($filename);

// Replace the strings
$contents = str_replace('<t>artofbanksy</t>', '<t>banksy</t>', $contents);
$contents = str_replace('<t>trends</t>', '<t>τάσεις</t>', $contents);
$contents = str_replace('<t>bestof2022</t>', '<t>τα καλύτερα του 22</t>', $contents);
$contents = str_replace('<t>artdeco</t>', '<t>διακόσμηση</t>', $contents);
$contents = str_replace('<t>dopaminowydom</t>', '<t>Μοναδικό σπίτι</t>', $contents);
$contents = str_replace('<t>pantone2021</t>', '<t>Κορυφαία 2021</t>', $contents);
$contents = str_replace('<t>colorsofnature</t>', '<t>χρώματα της φύσης</t>', $contents);
$contents = str_replace('<t>teenager</t>', '<t>νεολαία</t>', $contents);
$contents = str_replace('<t>PANTONE2022</t>', '<t>Έξτρα 2022</t>', $contents);
$contents = str_replace('<t>baby</t>', '<t>μωρό</t>', $contents);
$contents = str_replace('<t>gentlestories</t>', '<t>όμορφες ιστορίες</t>', $contents);
$contents = str_replace('<t>preschooler</t>', '<t>νήπιο</t>', $contents);
$contents = str_replace('<t>fulloflove</t>', '<t>γεμάτο αγάπη</t>', $contents);
$contents = str_replace('<t>nordicpower</t>', '<t>σκανδιναβικές επιρροές</t>', $contents);
$contents = str_replace('<t>japanesetouch</t>', '<t>japanese touch</t>', $contents);
$contents = str_replace('<t>dzieciecyswiat</t>', '<t>παιδικός κόσμος</t>', $contents);
$contents = str_replace('<t>backtoclassic</t>', '<t>Επιστροφή στο κλασσικό</t>', $contents);
$contents = str_replace('<t>freshflowers</t>', '<t>φρέσκα λουλούδια</t>', $contents);
$contents = str_replace('<t>flowersinhair</t>', '<t>λουλούδια στον αέρα</t>', $contents);
$contents = str_replace('<t>whoruntheworld</t>', '<t>ποιος κυβερνάει</t>', $contents);
$contents = str_replace('<t>aspaceodyssey</t>', '<t>Διαστημικό ταξίδι</t>', $contents);
$contents = str_replace('<t>housedressedinautumn</t>', '<t>Φθινοπωρινή ένδυση σπιτιού</t>', $contents);
$contents = str_replace('<t>lineart</t>', '<t>Τέχνη της γραμμής</t>', $contents);
$contents = str_replace('<t>backtoschool</t>', '<t>πίσω στο σχολείο</t>', $contents);
$contents = str_replace('<t>homeoffice</t>', '<t>γραφείο</t>', $contents);
$contents = str_replace('<t>asparkofmagic</t>', '<t>πινελιά μαγείας</t>', $contents);

echo "Tags updated/changed";
file_put_contents($filename, $contents);

?>
