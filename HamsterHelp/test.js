var twilio = require('twilio');

// Find your account sid and auth token in your Twilio account Console.
var client = new twilio('AC493bf7cdaa62f67dfb724285d6d4028f', '803d635c85de60e23bfa0f936942bb89');

// Send the text message.
client.messages.create({
    to: '16049280590',
    from: '16046701831',
    body: 'Hi Katie'
});