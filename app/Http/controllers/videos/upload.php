<?php

use Core\App;
use Core\FFmpeg\FFmpegWrapper;
use Http\Forms\VideoUploadForm;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  $title = $_POST['title'];
  $uploadedFile = $_FILES['file'];
  $dimensions = FFmpegWrapper::getDimensions($uploadedFile['tmp_name']);

  $form = VideoUploadForm::validate($attributes = [
    'file' => $uploadedFile
  ]);

  $isUploaded = $form->upload($uploadedFile);
  $videoInfo = $form->getVideoInfo();

  if (!$isUploaded) {
    removeDirectory($videoInfo['uploadDir']);
    $form->addError(
      'file',
      'Something went wrong, File not uploaded !!!'
    )->throw();
  }

  $isGenerated = FFmpegWrapper::generateHLSPlaylist($videoInfo, $dimensions);
  if (!$isGenerated) {
    removeDirectory($videoInfo['uploadDir']);
    throw new Exception("Error: FFmpeg command failed to generate.");
  }

  App::resolve('mysql')->query('INSERT INTO videos (title, original_file, master_file, folder_name) 
    VALUES (:title, :original_file, :master_file, :folder_name)', [
    'title' => $title,
    'original_file' => $videoInfo['originalFilePath'],
    'master_file' => $videoInfo['masterFilePath'],
    'folder_name' => $videoInfo['folderName']
  ]);
  // redirect to video-list
  redirect('/upload-video');

} else {
  echo "Invalid request.";
}