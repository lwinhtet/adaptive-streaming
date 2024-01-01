<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path('views/partials/banner.php') ?>

<?php
// $externalLinks = [
//   'https://demo.unified-streaming.com/k8s/features/stable/video/tears-of-steel/tears-of-steel.mp4/.m3u8',
//   'https://cph-p2p-msl.akamaized.net/hls/live/2000341/test/master.m3u8'
// ];
?>

<main class="container mx-auto p-4">
  <div class="mt-4 mb-4">
    <a href="upload-video" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Video</a>
  </div>

  <ul class="divide-y divide-gray-300">

    <?php foreach ($videos as $video) { ?>
      <li class="flex items-center justify-between py-2">
        <span class="text-lg">
          <?= $video['title']; ?>
        </span>
        <a href="video?id=<?= $video['id'] ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Watch</a>
      </li>
    <?php } ?>

  </ul>
</main>


<?php require base_path('views/partials/footer.php') ?>