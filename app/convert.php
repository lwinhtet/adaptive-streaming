<?php
// $id = '10100';
// $name = 'checkmate';
$id = '11000';
$name = 'sprit_the_stallion';

$originalFormat = '.mp4';
$fileName = strtolower($name);
$folderName = "{$id}_{$fileName}";
$path = "public/adaptive_streaming/{$folderName}";
// Input and output file paths

// folder needed to be created ahead of time∂
// path to the original video file
$inputFile = "{$path}/original/{$fileName}{$originalFormat}";
// path where the transcoded video segments and HLS playlist will be stored. %v is a placeholder for the video rendition name.
$segmentFolder = "{$path}/vs_%v/out.m3u8";

// $ffmpegCommand = "ffmpeg -f flv -i {$inputFile} \
// -map 0:v:0 -map 0:a:0 -map 0:v:1 -map 0:a:1 -map 0:v:2 -map 0:a:2 -map 0:v:3 -map 0:a:3 \
// -c:v libx264 -crf 22 -c:a aac -ar 44100 \
// -filter:v:0 scale=w=480:h=360  -maxrate:v:0 600k -b:a:0 500k \
// -filter:v:1 scale=w=640:h=480  -maxrate:v:1 1500k -b:a:1 1000k \
// -filter:v:2 scale=w=1280:h=720 -maxrate:v:2 3000k -b:a:2 2000k \
// -filter:v:3 scale=w=1920:h=1080 -maxrate:v:3 4000k -b:a:2 3000k \
// -var_stream_map 'v:0,a:0,0:v:0name:360p v:1,a:1,name:480p v:2,a:2,name:720p v:3,a:3,name:1080p' \
// -preset fast -hls_list_size 10 -threads 0 -f hls \
// -hls_time 3 -hls_flags independent_segments \
// -master_pl_name master.m3u8 \
// -y {$segmentFolder}";

$ffmpegCommand = "ffmpeg -i {$inputFile} \
-map 0:v:0 -map 0:a:0 -map 0:v:0 -map 0:a:0 -map 0:v:0 -map 0:a:0 -map 0:v:0 -map 0:a:0 \
-c:v libx264 -crf 22 -c:a aac -ar 44100 \
-filter:v:0 scale=w=480:h=360 -maxrate:v:0 600k -b:a:0 500k \
-filter:v:1 scale=w=640:h=480 -maxrate:v:1 1500k -b:a:1 1000k \
-filter:v:2 scale=w=1280:h=720 -maxrate:v:2 3000k -b:a:2 2000k \
-filter:v:3 scale=w=1920:h=1080 -maxrate:v:3 4000k -b:a:2 3000k \
-var_stream_map 'v:0,a:0,name:360p v:1,a:1,name:480p v:2,a:2,name:720p v:3,a:3,name:1080p' \
-preset fast -hls_list_size 10 -threads 0 -f hls \
-hls_time 5 -force_key_frames 'expr:gte(t,n_forced*1)' \
-master_pl_name master.m3u8 \
-y {$segmentFolder}";

$result = shell_exec($ffmpegCommand);
// Check the result
if ($result === null) {
  echo "Conversion successful! \n";
} else {
  echo "Error during conversion: $result \n";
}

/*
Low Quality: Audio Bitrate: 64 Kbps
Standard Quality: Audio Bitrate: 128 Kbps
High Definition: Audio Bitrate: 192 Kbps

Low Quality:
Bitrate 1: 500 Kbps
Bitrate 2: 800 Kbps

Standard Quality:
Bitrate 1: 1200 Kbps
Bitrate 2: 2000 Kbps

High Definition:
Bitrate: 4000 Kbps
*/

/*
The -i flag is used to indicate the input file.
-c:v h264: Specifies the video codec to be used for encoding. In this case, it's H.264 for video compression.
-flags +cgop: Sets flags related to closed GOP (Group of Pictures) structure. The +cgop flag enables closed GOP, which can be beneficial for certain streaming scenarios.
-g 30: Sets the GOP size. In this case, the GOP size is set to 30 frames. The GOP is a group of consecutive frames in a video stream that consists of one I-frame (intra-frame, or key frame) followed by a series of P-frames (predicted frames) and B-frames (bidirectional frames).
hls_time duration - Set the target segment length. Default value is 2.
hls_segment_filename filename - Set the segment filename.
how to use -map (https://trac.ffmpeg.org/wiki/Map)

Segments will be cut at keyframes, so unless a keyframe exists each second, hls_time will not get honoured. 
Add -force_key_frames 'expr:gte(t,n_forced*1)' to add a keyframe at the start of each second. side-effects? 
Some rate-distortion will likely occur, since the encoder may not have placed a keyframe at that point otherwise.

-map: Selects specific streams from the input (video and audio).
-c:v libx264 -crf 22 -c:a aac -ar 44100: Sets video codec, constant rate factor, audio codec, and audio sample rate.
-filter:v: Applies video filters (scaling in this case) for different renditions.
-maxrate:v and -b:a: Sets the maximum video bitrate and audio bitrate.
-var_stream_map: Defines the mapping of video and audio streams to different renditions.
-preset fast: Specifies the encoding preset for speed.
-hls_list_size 10: Sets the maximum number of playlist entries in the master playlist.
-threads 0: Uses the maximum number of threads for encoding.
-f hls: Specifies the output format as HLS.
-hls_time 3: Sets the segment duration for HLS.
-hls_flags independent_segments: Flags for independent segments.
-master_pl_name master.m3u8: Sets the name for the master playlist.
-y {$segmentFolder}: Overwrites existing output files.
*/

?>