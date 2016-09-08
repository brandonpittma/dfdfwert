<?php
require 'fungsi.php';
header("Content-type: text/plain");

echo "User-Agent: *\n";
echo "Allow: /\n";
echo "Sitemap: ".home_url()."/sitemap.xml";
?>