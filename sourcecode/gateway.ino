#include <WiFiS3.h>
#include <MQTTClient.h>
#include <LoRa.h>

const char WIFI_SSID[] = "";          // CHANGE TO YOUR WIFI SSID
const char WIFI_PASSWORD[] = "";  // CHANGE TO YOUR WIFI PASSWORD

const char MQTT_BROKER_ADRRESS[] = "broker.hivemq.com";  // CHANGE TO MQTT BROKER'S ADDRESS
const int MQTT_PORT = 1883;
const char MQTT_CLIENT_ID[] = "arduino-uno-r4-client-65070131";    // CHANGE IT AS YOU DESIRE

// MQTT Topics
const char PUBLISH_TOPIC[] = "sensor/temp";        // CHANGE IT AS YOU DESIRE
const char SUBSCRIBE_TOPIC[] = "sensor/temp";   // CHANGE IT AS YOU DESIRE

// LoRa Configuration
const long LORA_BAND = 915E6;  // LoRa frequency, adjust to your region

WiFiClient network;
MQTTClient mqtt(256);

void setup() {
  Serial.begin(9600);

  // Initialize WiFi
  connectToWiFi();

  // Initialize MQTT
  connectToMQTT();

  // Initialize LoRa
  if (!LoRa.begin(LORA_BAND)) {
    Serial.println("LoRa initialization failed!");
    while (1);
  }
  Serial.println("LoRa initialized successfully.");
}

void loop() {
  mqtt.loop();

  // Check for incoming LoRa messages
  if (LoRa.parsePacket()) {
    String loraMessage = "";
    while (LoRa.available()) {
      loraMessage += (char)LoRa.read();
    }
    
    Serial.print("Received LoRa message: ");
    Serial.println(loraMessage);
    
    // Publish the LoRa message to MQTT
    sendToMQTT(loraMessage);
  }
}

void connectToWiFi() {
  int status = WL_IDLE_STATUS;
  while (status != WL_CONNECTED) {
    Serial.print("Connecting to WiFi SSID: ");
    Serial.println(WIFI_SSID);
    status = WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
    delay(10000);  // Wait for connection
  }
  Serial.print("Connected to WiFi, IP Address: ");
  Serial.println(WiFi.localIP());
}

void connectToMQTT() {
  mqtt.begin(MQTT_BROKER_ADRRESS, MQTT_PORT, network);
  mqtt.onMessage(messageHandler);

  Serial.print("Connecting to MQTT broker");
  while (!mqtt.connect(MQTT_CLIENT_ID)) {
    Serial.print(".");
    delay(100);
  }
  Serial.println();
  Serial.println("Connected to MQTT broker");

  // Subscribe to the topic
  if (mqtt.subscribe(SUBSCRIBE_TOPIC)) {
    Serial.print("Subscribed to topic: ");
    Serial.println(SUBSCRIBE_TOPIC);
  } else {
    Serial.print("Failed to subscribe to topic: ");
    Serial.println(SUBSCRIBE_TOPIC);
  }
}

void sendToMQTT(String message) {
  mqtt.publish(PUBLISH_TOPIC, message);
  Serial.println("Published to MQTT:");
  Serial.print("- Topic: ");
  Serial.println(PUBLISH_TOPIC);
  Serial.print("- Message: ");
  Serial.println(message);
}

void messageHandler(String &topic, String &payload) {
  Serial.println("Received message from MQTT:");
  Serial.print("- Topic: ");
  Serial.println(topic);
  Serial.print("- Payload: ");
  Serial.println(payload);

  // Process incoming data to control something if needed
}
