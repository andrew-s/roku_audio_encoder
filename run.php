<?php

/**
 * @param $needle
 * @param $haystack
 * @return array
 */
function array_search_partial($needle, $haystack)
{
    return array_filter($haystack, function($el) use ($needle) {
        return ( strpos($el, $needle) !== false );
    });
}

// specify our configs
$path_ffmpeg = '/wherever/FFmpeg';
$path_ffprobe = '/wherever/ffprobe';

$path_input_folder = 'Y:\Input';
$path_output_folder = 'Y:\Output';

// loop through our folder, selecting our files
foreach (scandir($path_input_folder) as $file)
{
    $arrOutput = null;
    $path_file = $path_input_folder . '\\' . $file;

    // skip
    if ('.' === $file) continue;
    if ('..' === $file) continue;

    // get the file info
    exec($path_ffprobe . ' -i "' . $path_file . '" 2>&1', $arrOutput);

    // do we already have an AAC audio stream?
    if(!empty(array_search_partial('Audio: aac', $arrOutput)))
    {
        echo("Skipping file ({$path_file}) - already has an AAC audio stream\n\r");
    }
    else
    {
        // we need to add an AAC audio stream (we'll take the first stream as it's usually
        // guaranteed to be a DTS-HD master audio track and not a audio descriptive track
        echo("Processing file ({$path_file}) - AAC stream missing\n\r");

        $audio_streams = array_search_partial('Audio:', $arrOutput);

        if(empty($audio_streams))
        {
            die('File has no audio streams - can\'t proceed');
        }

        // run FFmpeg (output isn't surpressed)
        $file_name = end(explode('\\', $path_file));
        exec($path_ffmpeg . ' -i "' . $path_file . '" -map 0 -c:v copy -c:s copy -map 0:a:0 -c:a copy -c:a:' . count($audio_streams) . ' aac -strict -2 -b:a:' . count($audio_streams) . ' 386k "' . $path_output_folder . '\\' . $file_name . '"');
    }
}

?>