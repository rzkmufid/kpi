// === FILE: includes/functions.php ===
<?php
function format_percent($value) {
  return number_format($value, 2) . '%';
}

function redirect($url) {
  header("Location: $url");
  exit;
}
?>