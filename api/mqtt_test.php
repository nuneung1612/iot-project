<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>

<script>
  client = new Paho.MQTT.Client("broker.hivemq.com", Number(8000), "/wss","clientId-65070131");
  client.onConnectionLost = onConnectionLost;
  client.onMessageArrived = onMessageArrived;

  client.connect({ onSuccess: onConnect });

  function onConnect() {
    console.log("onConnect");
    client.subscribe("sensor/temp");
    client.subscribe("sensor/humid");
    // message = new Paho.MQTT.Message("Hello MQTT");
    // message.destinationName = "sensor/temp";
    // client.send(message);
    // message.destinationName = "sensor/humid";
    // client.send(message);
  }

  function onConnectionLost(responseObject) {
    if (responseObject.errorCode !== 0) {
      console.log("onConnectionLost:" + responseObject.errorMessage);
    }
  }

  function onMessageArrived(message) {
    if (message.destinationName === "sensor/temp") {
      document.getElementById("tempValue").innerHTML = message.payloadString + "°C";
      updateWeatherIcon(parseFloat(message.payloadString));
    }
    if (message.destinationName === "sensor/humid") {
      document.getElementById("humidityValue").innerHTML = message.payloadString + "%";
    }
  }

  function updateWeatherIcon(temp) {
        const icon = document.getElementById("weatherIcon");
        if (temp >= 30) {
            icon.src = "sun.png"; // ใส่ URL ของไอคอนแดด
        } else if (temp >= 20) {
            icon.src = "cloudy.png"; // ใส่ URL ของไอคอนเมฆ
        } else {
            icon.src = "rain.png"; // ใส่ URL ของไอคอนฝน
        }
    }
</script>