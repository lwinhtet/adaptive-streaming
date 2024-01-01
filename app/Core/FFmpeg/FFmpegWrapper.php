<?php

namespace Core\FFmpeg;

class FFmpegWrapper
{
  // protected $ffmpegPath;

  // protected $videoInfo = [];

  // public function __construct(string $ffmpegPath = '/usr/bin/ffmpeg', array $videoInfo)
  // {
  //   $this->ffmpegPath = $ffmpegPath;
  //   $this->videoInfo = $videoInfo;
  // }

  public static function generateHLSPlaylist($videoInfo, $dimensions = ['width' => 1920, 'height' => 1080])
  {
    $inputFile = "{$videoInfo['uploadDir']}/original/{$videoInfo['fileName']}";
    $segmentFolder = "{$videoInfo['uploadDir']}/vs_%v/out.m3u8";

    $variants = [
      ['width' => 480, 'height' => 360, 'maxrate' => 1000, 'audio_bitrate' => 500],
      ['width' => 640, 'height' => 480, 'maxrate' => 2000, 'audio_bitrate' => 1000],
      ['width' => 1280, 'height' => 720, 'maxrate' => 4000, 'audio_bitrate' => 2000],
      ['width' => 1920, 'height' => 1080, 'maxrate' => 8000, 'audio_bitrate' => 3000],
    ];

    $filterVariants = "";
    $maps = "";
    $streamsMap = "";

    foreach ($variants as $index => $variant) {
      // Check if the variant resolution is less than or equal to the original video
      if ($variant['width'] <= $dimensions['width'] && $variant['height'] <= $dimensions['height']) {
        $maps .= "-map 0:v:0 -map 0:a:0 "; // space at the end
        $filterVariants .= "-filter:v:{$index} scale=w={$variant['width']}:h={$variant['height']} -maxrate:v:{$index} {$variant['maxrate']}k -b:a:{$index} {$variant['audio_bitrate']}k \\";
        $streamsMap .= "v:{$index},a:{$index},name:{$variant['height']}p "; // space at the end
      }
    }

    $ffmpegCommand = "ffmpeg -i {$inputFile} \
    " . $maps . " \
    -c:v h264 -crf 22 -c:a aac -ar 44100 \
    " . $filterVariants . "
    -var_stream_map " . "'" . $streamsMap . "'" . " \
    -preset fast -hls_list_size 10 -threads 0 -f hls \
    -hls_time 5 -force_key_frames 'expr:gte(t,n_forced*1)' \
    -master_pl_name master.m3u8 \
    -y {$segmentFolder}";

    $result = shell_exec($ffmpegCommand);

    if ($result === null) {
      return true;
    }
    return false;
  }

  public static function getDimensions($uploadedFile)
  {
    $ffmpegCommand = "ffprobe -v error -select_streams v -show_entries stream=width,height -of json=compact=1 {$uploadedFile}";
    $result = shell_exec($ffmpegCommand);
    return json_decode($result, true)['streams'][0];
  }
}