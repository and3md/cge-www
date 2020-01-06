<?php
require_once 'castle_engine_functions.php';
castle_header('Conversion output');
?>

<div class="single-column-page">

<div class="convert-form convert-output jumbotron">

<?php

/* Show error.

   Note that $error_message is assumed to be already-safe HTML,
   it is not sanitized here anymore.

   $conversion_log may be NULL if empty. Otherwise it will output (after sanitization).
*/
function output_error($error_message, $conversion_log)
{
  echo '<p><b>Failure: ' . $error_message . '</b>';

  if (!empty($conversion_log)) {
    ?>
    <br>
    <a id="toggle-details" href="#">Click to see the details.</a>
    <pre style="display:none" id="details"><?php echo htmlspecialchars($conversion_log); ?></pre>
    <?php
  }
}

/* Show success.

   $output_file_id (string) is an id for convert-download.php?id=xxx.

   $output_file_size (integer) is the size in bytes.

   $encoding is 'classic' or 'xml'.

   $conversion_log will be output (after sanitization).
*/
function output_success($output_file_id, $output_file_size, $encoding, $conversion_log)
{
  $output_extension = $encoding == 'xml' ? '.x3d' : '.x3dv';

  ?>
  <p><b>Success!</b><br>
  The resulting X3D file size: <?php echo readable_byte_size($output_file_size); ?>.<br>
  <a id="toggle-details" href="#">Click to see the conversion details.</a>

<pre style="display:none" id="details">
<?php echo $conversion_log; ?>
</pre>

  <p><a href="convert-download.php?id=<?php echo htmlspecialchars($output_file_id); ?>&amp;encoding=<?php echo htmlspecialchars($encoding); ?>" class="btn btn-primary btn-lg">Download the resulting X3D file.</a></p>

  <div class="convert-patreon">
    <a class="btn btn-success btn-lg btn-patreon" href="<?php echo PATREON_URL; ?>">Do you like this tool?<br><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Support us on Patreon.</a>';
  </div>
  <?php
}


/* Random alphanumeric string.
   See https://code.tutsplus.com/tutorials/generate-random-alphanumeric-strings-in-php--cms-32132
   https://www.php.net/manual/en/function.random-int.php
*/
function random_alphanum($length)
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $permitted_chars_len = strlen($permitted_chars);
  $result = '';
  for($i = 0; $i < $length; $i++) {
    $random_character = $permitted_chars[random_int(0, $permitted_chars_len - 1)];
    $result .= $random_character;
  }
  return $result;
}

/* Perform conversion.

   $encoding (string) is 'classic' or 'xml', just like --encoding parameter of tovrmlx3d.

   $files is the PHP uploaded files structure for the appropriate form field
   (see https://www.php.net/manual/en/features.file-upload.multiple.php ).

   $conversion_log (multiline string) is set.

   Returns boolean, whether converting was successfull.
*/
function convert_to_x3d($encoding, $files, &$conversion_log,
  &$output_file_id, &$output_file_size)
{
  // TODO: run without any security for server,
  // make even input files downloadable,
  // and don't check whether we are used multiple times

  $output_file_id = random_alphanum(24);

  for ($i = 0; $i < count($files['tmp_name']); $i++) {
    $temp_name = $files['tmp_name'][$i];
    $dest_name = '/var/cge-convert/' . basename($files['name'][$i]);
    if (!move_uploaded_file($temp_name, $dest_name)) {
      $conversion_log = 'Cannot move uploaded file';
      return false;
    }
  }

  $main_file = $files['name'][0]; // TODO: assume 0th is the main

  shell_exec(
    'cd /var/cge-convert/ && /usr/local/bin/tovrmlx3d ' .
    '"' . escapeshellcmd($main_file) . '" --force-x3d ' .
    '--encoding="' . escapeshellcmd($encoding) . '" ' .
    '> ' . $output_file_id . ' ' .
    '2> error.log');
  // TODO: check process exit status to get result

  $conversion_log = file_get_contents('/var/cge-convert/error.log');

  $output_file_size = filesize('/var/cge-convert/' . $output_file_id);

  return !empty($output_file_size);
}

/* Process form input, call either output_error or output_success */
function process_form_post()
{
  if (!isset($_POST['encoding']) ||
      !isset($_FILES['input-file'])) {
    output_error('The uploaded file was too large.', NULL);
    return;
  }

  $encoding = $_POST['encoding'];
  $files = $_FILES['input-file'];

  if ($encoding != 'xml' &&
      $encoding != 'classic') {
    output_error('Invalid encoding specified.', NULL);
  } else
  if (!isset($files['name'][0])) {
    output_error('No input files to convert.', NULL);
  } else
  {
    $conversion_success = convert_to_x3d($encoding, $files, $conversion_log,
      $output_file_id, $output_file_size);
    if ($conversion_success) {
      output_success($output_file_id, $output_file_size, $encoding, $conversion_log);
    } else {
      output_error('Conversion failed.', $conversion_log);
    }
  }
}

process_form_post();
?>

  <p><a href="convert.php">Convert another file.</a></p>
</div>

</div>

<?php
castle_footer();
?>
