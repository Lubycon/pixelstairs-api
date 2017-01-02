<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/3.2/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('fd5230aae8574f4d60cc', {
      cluster: 'ap1',
      encrypted: true
    });

    var channel = pusher.subscribe('my_channel');
    channel.bind('my_event', function(data) {
      alert(data.message);
    });
  </script>
</head>
