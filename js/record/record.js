var gumStream; 						//stream from getUserMedia()
var recorder; 						//WebAudioRecorder object
var input; 							//MediaStreamAudioSourceNode  we'll be recording
var encodingType; 					//holds selected encoding for resulting audio (file)
var encodeAfterRecord = true;       // when to encode

// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext; //new audio context to help us record

var recordButton = document.getElementById("btnRecord");
var stopButton = document.getElementById("btnStop");

function startRecording(userID, resultID, questionNumber, maxTime=600) {
    log.innerHTML = "";
    // if ((maxTime===0) || (maxTime===null)|| (maxTime==='')) maxTime=600;
    var constraints = {audio: true, video: false}
    navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
        audioContext = new AudioContext();
        gumStream = stream;
        input = audioContext.createMediaStreamSource(stream);
        encodingType = "mp3";

        recorder = new WebAudioRecorder(input, {
            workerDir: "js/record/",
            encoding: encodingType,
            numChannels: 2,
            onEncoderLoading: function (recorder, encoding) {
                __log("Encoder loading");
            },
            onEncoderLoaded: function (recorder, encoding) {
                __log("Encoder loaded");
            }
        });

        recorder.onComplete = function (recorder, blob) {
            __log("Recording complete");
            gumStream.getAudioTracks()[0].stop();
            createDownloadLink(blob);
            uploadAudio(blob, userID, resultID, questionNumber);
        }

        recorder.setOptions({
            timeLimit: maxTime,
            encodeAfterRecord: encodeAfterRecord,
            ogg: {quality: 0.5},
            mp3: {bitRate: 64}
        });

        recorder.startRecording();
        __log("Recording started");

    }).catch(function (err) {
        recordButton.disabled = false;
        stopButton.disabled = true;
    });

    recordButton.disabled = true;
    stopButton.disabled = false;
}

function stopRecording() {
    console.log("stopRecording() called");
    gumStream.getAudioTracks()[0].stop();
    stopButton.disabled = true;
    recordButton.disabled = false;
    recorder.finishRecording();
}

function createDownloadLink(blob) {

    var url = URL.createObjectURL(blob);
    var au = document.createElement('audio');
    var div = document.createElement('div');

    au.controls = true;
    au.src = url;
    au.style = "margin-bottom: 5px;";

    div.appendChild(au);
    if (recordingsList.hasChildNodes()) {
        recordingsList.removeChild(recordingsList.childNodes[0]);
    }
    recordingsList.appendChild(div);
}

function uploadAudio(blob, userID, resultID, questionNumber) {
    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    xhr.onload = function (e) {
        if (this.readyState === 4) {
            console.log("Server returned: ", e.target.responseText);
        }
    };
    var fd = new FormData();
    fd.append("audio_data_" + userID, blob, resultID + "_" + questionNumber + ".mp3");
    xhr.open("POST", "index.php?module=testing", true);
    xhr.send(fd);
}

//helper function
function __log(e, data) {
    //log.innerHTML += "\n" + e + " " + (data || '');
}