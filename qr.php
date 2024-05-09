<?php
include('phpqrcode/qrlib.php');
$text = $_GET["link"];
QRcode::png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 10, $margin = 4, $saveandprint = false);
//echo'<hr/>';
//QRcode::png($text);
