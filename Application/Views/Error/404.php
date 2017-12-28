<?php
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 NOT FOUND</title>
<link rel="stylesheet" href="<?php echo Config::CSS_PATH  ?>mediafriend.css">
<link rel="stylesheet" href="<?php echo Config::CSS_PATH  ?>main.css">
</head>

<body>
<?php include(ROOT . Config::PARTIAL_VIEW_PATH . "Header.php"); ?>
<div class='main'>
<?php include(ROOT. Config::PARTIAL_VIEW_PATH . "MobileMenu.inc.php") ?>

<div class="mf-wrapper">
<div class="mf-fixedContainer mf-pad">
<div class='image404'>&nbsp;</div>
<h2 class="mf-center">404 - Not Found</h2>
<div class='text404'>You have gone through the wrong way !</div>
</div>
</div>

</div>
<script type="text/javascript" src="<?php echo Config::JS_PATH  ?>app.js"></script>
<script type="text/javascript" src="<?php echo Config::JS_PATH  ?>Actions/GeneralAction.js"></script>
</body>
</html>