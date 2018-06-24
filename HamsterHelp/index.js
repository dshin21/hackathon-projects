var keywords = {
	devices: [
		{ word: /macbook/i, category: 'laptop' },
		{ word: /laptop/i, category: 'laptop' },
		{ word: /phone/i, category: 'phone' },
		{ word: /fridge/i, category: 'fridge' }
	]
};

// Imports the Google Cloud client library
const speech = require('@google-cloud/speech');
const fs = require('fs'), url = require('url');
var http = require('http');
var https = require('https');
const express = require('express');
const app = express();

// Creates a client
const client = new speech.SpeechClient();

// The name of the audio file to transcribe
const fileName = './resources/alex.flac';

// Reads a local audio file and converts it to base64
const file = fs.readFileSync(fileName);
const audioBytes = file.toString('base64');

// The audio file's encoding, sample rate in hertz, and BCP-47 language code
const audio = {
	content: audioBytes,
};
const config = {
	encoding: 'FLAC',
	sampleRateHertz: 44100,
	languageCode: 'en-US',
};
const request = {
	audio: audio,
	config: config,
};

app.use(express.static(__dirname));

app.get('/', (req, res, next) => {
	var options = {
		root: __dirname,
		dotfiles: 'deny',
		headers: {
			'x-timestamp': Date.now(),
			'x-sent': true
		}
	};
	res.sendFile('index.html', options);
	// res.end();
});

app.get('/getstring', (req, res, next) => {
	// Detects speech in the audio file
	var found = null;
	client
		.recognize(request)
		.then(data => {
			const response = data[0];
			const transcription = response.results
				.map(result => result.alternatives[0].transcript)
				.join('\n');
			
				// sending text
			app.get('/anything', (req, res, next) => {
				var twilio = require('twilio');

				// Find your account sid and auth token in your Twilio account Console.
				var client = new twilio('', '');

				// Send the text message.
				client.messages.create({
					to: '',
					from: '',
					body: transcription
				});
				res.writeHead(200, { "Content-Type": "text/plain" });
				res.end(found);
				console.log("done");
			});

			console.log(`Transcription: ${transcription}`);

			for (var i = 0; i < keywords.devices.length; ++i) {
				found = transcription.match(keywords.devices[i].word);
				if (found != null) {
					found = found[0] + ',' + keywords.devices[i].category;
					break;
				}
			}
			if (found === null) {
				found = "unrecognizable";
			}
			res.writeHead(200, { "Content-Type": "text/plain" });
			res.end(found);
			console.log("end");
		});
});

var server = app.listen(8888, function () {
	var host = server.address().address
	var port = server.address().port

	console.log("Example app listening at http://%s:%s", host, port)
})
