<?php

namespace Http\Forms;

use Core\Errors\FileUploadException;
use Http\Traits\FormErrorTrait;

class VideoUploadForm
{
  protected $maxFileSize = 15 * 1024 * 1024; // 15 MB

  protected $allowedFileTypes = ['mp4', 'webm', 'mov'];

  // adaptive_streaming/' . uniqid('', true) . '/original/
  protected $videoInfo = [];

  use FormErrorTrait;
  public function __construct(public array $attributes)
  {
    if ($attributes['file']['error'] !== UPLOAD_ERR_OK) {
      $this->errors['file'] = 'File upload failed. Error code: ' . $attributes['file']['error'];
    }

    // Invalid file type.
    $uploadedFileType = pathinfo($attributes['file']['name'], PATHINFO_EXTENSION);
    if (!in_array(strtolower($uploadedFileType), $this->allowedFileTypes)) {
      $this->errors['file'] = 'Invalid file type.';
    }

    // File size exceeds the allowed limit.
    if ($attributes['file']['size'] > $this->maxFileSize) { // 5 MB)
      $this->errors['file'] = 'File size exceeds the allowed limit.';
    }

    // Unique File Naming

    // secure destination directory
    // if (!is_dir($this->destinationDirectory) || !is_writable($this->destinationDirectory)) {
    //   $this->errors['file'] = 'Destination directory is not writable.';
    // }
  }

  public static function validate(array $attributes)
  {
    $instance = new static($attributes);
    return $instance->failed() ? $instance->throw() : $instance;
  }

  public function failed(): bool
  {
    return count($this->errors) > 0;
  }

  public function throw (): void
  {
    FileUploadException::throw($this->errors);
  }

  public function upload($file): bool
  {
    $uniqueID = uniqid('', true);
    $uploadDir = 'adaptive_streaming/' . $uniqueID;
    $uploadOriginalDir = $uploadDir . '/original/';
    $filename = strtolower(str_replace(' ', '_', $file['name']));
    $targetPath = $uploadOriginalDir . $filename;

    if (!is_dir($uploadOriginalDir)) {
      mkdir($uploadOriginalDir, 0755, true);
    }

    $this->setVideoInfo([
      'fileName' => $filename,
      'uploadDir' => $uploadDir,
      'folderName' => $uniqueID,
      'originalFilePath' => $targetPath,
      'masterFilePath' => $uploadDir . '/master.m3u8'
    ]);

    // Move the uploaded file to the specified directory
    return move_uploaded_file($file['tmp_name'], $targetPath);
    // echo "File uploaded successfully. File path: " . $targetPath;
    // echo "Error moving the uploaded file.";
  }

  public function setVideoInfo($data): void
  {
    $this->videoInfo = $data;
  }

  public function getVideoInfo(): array|null
  {
    return $this->videoInfo;
  }
}