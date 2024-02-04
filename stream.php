<?php

$stream1 = "ffmpeg -re -stream_loop -1 -i https://clip-video-ngtv.s3.eu-west-3.amazonaws.com/226163_65bfd1d4d7f1b141665274 -c copy -f rtsp -rtsp_transport tcp rtsp://localhost:8554/stream1 > ./ffmpeg1.log 2>&1 < /dev/null &";
$stream2 = "ffmpeg -re -stream_loop -1 -i https://s1.ngtvexperience.com/0010/65bfaf86024ed535676542/event_31343_0.mp4 -c copy -f rtsp -rtsp_transport tcp rtsp://localhost:8554/stream2 > ./ffmpeg2.log 2>&1 < /dev/null &";

shell_exec($stream1);
shell_exec($stream2);
