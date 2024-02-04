<?php

$streams = [
    "stream1" => "rtsp://localhost:8554/stream1",
    "stream2" => "rtsp://localhost:8554/stream2",
    // Add more streams as needed
];

$destination = "rtmp://localhost/live/test"; // Your Twitch stream key
$currentProcess = null;

function startStreaming($sourceUrl) {
    global $currentProcess, $destination;

    // Ensure any existing process is stopped before starting a new one
    if ($currentProcess !== null) {
        stopStreaming();
    }

    $descriptorSpec = [
        0 => ["pipe", "r"],  // STDIN is a pipe that the child will read from
        1 => ["pipe", "w"],  // STDOUT is a pipe that the child will write to
        2 => ["pipe", "w"]   // STDERR is a pipe that the child will write to
    ];

    $command = "ffmpeg -rtsp_transport tcp -i $sourceUrl -c copy -f flv $destination";
    $process = proc_open($command, $descriptorSpec, $pipes);

    if (is_resource($process)) {
        echo "Started streaming: $sourceUrl\n";
        $currentProcess = $process;
    }
}

function stopStreaming() {
    global $currentProcess;

    if (is_resource($currentProcess)) {
        // Terminate the process
        proc_terminate($currentProcess);
        // Close the process
        proc_close($currentProcess);
        echo "Stopped streaming.\n";
        $currentProcess = null;
    }
}

function switchStream($streamKey) {
    global $streams;

    if (array_key_exists($streamKey, $streams)) {
        echo "Switching to $streamKey...\n";
        startStreaming($streams[$streamKey]);
    } else {
        echo "Invalid stream key.\n";
    }
}

function runStreamSwitcher() {
    global $streams;
    while (true) {
        foreach ($streams as $streamKey => $streamUrl) {
            switchStream($streamKey);
            sleep(30); // Adjust the delay as needed
        }
    }
}

// Cleanup before exiting
register_shutdown_function('stopStreaming');

runStreamSwitcher();
