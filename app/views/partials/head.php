<!doctype html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <title>
        <?= $heading ?? 'My App' ?>
    </title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <?php
    if (isset($additionalJsLinks) && !empty($additionalJsLinks)) {
        foreach ($additionalJsLinks as $link) {
            echo $link;
        }
    }
    ?>

</head>

<body class="h-full">
    <div class="min-h-full">