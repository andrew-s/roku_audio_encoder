roku_audio_encoder
==================

PHP Script to add a AAC audio stream to your files - the script takes the first audio stream, duplicates it and then
encodes it to an AAC audio stream (keeping all of the original streams intact - including subtitles)

Requirements
====
 * PHP 5.5+
 * FFmpeg with ffprobe

Running
====

Update the variables in run.php with your directories and application locations, then call;

```
  php run.php
```

and that's it, your files should be Roku ready with AAC audio!