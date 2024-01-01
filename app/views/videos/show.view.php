<?php

$additionalJsLinks = [
  '<link href="css/videojs.css" rel="stylesheet">',
];

?>

<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path('views/partials/banner.php') ?>

<main class="">
  <form class="container mx-auto p-4" method="POST">
    <input type="hidden" name="id" value="<?= $video['id'] ?>">
    <button class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
  </form>

  <div class="container mx-auto p-4 flex justify-center items-center">
    <div class="text-center">
      <video id="my-video" class="video-js" controls preload="metadata" width="1000" height="750" muted="muted"
        data-setup='{}'>
        <source src="<?= $video['master_file'] ?>" type="application/x-mpegURL">
        <!-- <source src="https://demo.unified-streaming.com/k8s/features/stable/video/tears-of-steel/tears-of-steel.mp4/.m3u8"
        type="application/x-mpegURL"> -->

        <button class="vjs-my-custom-button" title="My Custom Button">Custom Button</button>
        Your browser does not support the video tag.
      </video>
    </div>
  </div>
</main>

<script src="dist/bundle.js"></script>
<link href="css/quality-selector.css" rel="stylesheet">

<?php require base_path('views/partials/footer.php') ?>