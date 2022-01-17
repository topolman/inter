<?php
/**
 * Создать PHP страницу upload.php
 * с формой загрузки CSV-файла. В CSV-файле должны быть 2 столбца:
 * 1 - название файла, 2 - содержимое.
 * рядом с файлом создать каталог /upload/.
 * Требуется создать в каталоге файлы, прочитав содержимое CSV-файла.
 *
 * Проблемы:
 * 1. Злоумышленик может вызвать переполнение дискового пространства.
 * 2. Если есть доступ по прямым ссылкам к содержимому каталога UPLOAD,
 *    есть риск отдать злодеям контроль над сервером и всеми данными на сервере.
 *
 */

$csv_filename = "";
$errors = [];

if (isset($_POST["file"])) {
  $upload_dir = "./upload";
  $allowed_extensions = ["log", "txt", "html"];
  $file_array = [];

  // Если каталога UPLOAD нет, создаем его.
  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0666, true);
  }

  $csv_filename = $_POST["file"];

  // Проверки содержимого файла, перед генерацией.
  $handle = fopen($_POST["file"], "r");
  $i = 0;
  while (($data = fgetcsv($handle)) !== false) {
    $i++;
    if (sizeof($data) != 2) {
      $errors[] = "Ошибка заполнения. Строка {$i}.";
    }
    $filename_parts = explode(".", $data[0]);
    if (
      sizeof($filename_parts) != 2 ||
      in_array($filename_parts[1], $allowed_extensions) !== true
    ) {
      $errors[] = "Ошибка именования файла \"{$data[0]}\".";
    }
    $file_array[$data[0]] = $data[1];
  }

  // При наличии ошибок, файлы не генерятся.
  if (sizeof($errors) === 0) {
    foreach ($file_array as $filename => $content) {
      file_put_contents($upload_dir . "/" . $filename, $content);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h3>002 UPLOAD</h3>
  <?php if (sizeof($errors) === 0 && $csv_filename !== "") { ?>
    <h4>Загрузка файла <?php echo $csv_filename; ?> успешна!</h4>
  <?php } elseif (sizeof($errors) !== 0 && $csv_filename !== "") { ?>
    <h4 style="color: red;">Загрузка файла <?php echo $csv_filename; ?> не удалась!</h4>
    <h4 style="color: red;">Ошибки:</h4>
    <ul style="color: red;">
      <?php foreach ($errors as $key => $value) { ?>
      <li><?php echo $value; ?></li>
      <?php } ?>
    </ul>
  <?php } ?>
  <form method="POST">
    <input type="file" name="file"/>
    <button type="submit">UPLOAD</button>
  </form>
</body>
</html>