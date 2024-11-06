#include <Wire.h>
#include <Adafruit_SHT31.h>
#include <LoRa.h>
#include <LiquidCrystal_I2C.h>  // Include the LiquidCrystal_I2C library

Adafruit_SHT31 sht31 = Adafruit_SHT31();

// LoRa Configuration
const long LORA_BAND = 915E6;  // Adjust to your region

// Initialize the LCD (address 0x27, 16 columns, and 2 rows)
LiquidCrystal_I2C lcd(0x27, 16, 2);

void setup() {
  Serial.begin(9600);

  // Initialize the SHT31 sensor
  if (!sht31.begin(0x44)) {  // Address 0x44 for SHT31
    Serial.println("Could not find SHT31 sensor!");
    while (1);
  }
  Serial.println("SHT31 sensor initialized.");

  // Initialize LoRa
  if (!LoRa.begin(LORA_BAND)) {
    Serial.println("LoRa initialization failed!");
    while (1);
  }
  Serial.println("LoRa initialized successfully.");
  lcd.init();
  lcd.backlight();
  // Initialize the LCD
  lcd.begin(16, 2);  // Initialize the LCD screen with 16 columns and 2 rows
  lcd.clear();       // Clear the LCD screen
}

void loop() {
  // Read temperature and humidity from SHT31
  float temperature = sht31.readTemperature();
  float humidity = sht31.readHumidity();

  if (!isnan(temperature) && !isnan(humidity)) {
    // Format the message to send (e.g., "temp:25.5,humid:60")
    String message = "temp:" + String(temperature, 1) + ",humid:" + String(humidity, 1);

    // Send the message via LoRa
    sendLoRaMessage(message);
    
    // Print for debugging
    Serial.print("Sending message: ");
    Serial.println(message);

    // Display temperature and humidity on LCD
    lcd.clear();  // Clear the LCD screen to avoid overwriting
    lcd.setCursor(0, 0);  // Set cursor to the first row
    lcd.print("Temp: ");
    lcd.print(temperature, 1);  // Display temperature with 1 decimal place

    lcd.setCursor(0, 1);  // Set cursor to the second row
    lcd.print("Humid: ");
    lcd.print(humidity, 1);  // Display humidity with 1 decimal place
  } else {
    Serial.println("Failed to read from SHT31 sensor.");
  }

  delay(5000);  // Send data every 5 seconds
}

void sendLoRaMessage(String message) {
  LoRa.beginPacket();
  LoRa.print(message);
  LoRa.endPacket();
}
