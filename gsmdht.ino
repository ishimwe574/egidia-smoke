

#include <SoftwareSerial.h>
#include <DHT.h>

#define DHTPIN 7
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

SoftwareSerial gsm(2, 3); // RX, TX

bool smsSent = false;

void sendSMS(float temp, float hum) {
  gsm.println("AT+CMGF=1");
  delay(1000);

  gsm.println("AT+CMGS=\"+2507XXXXXXXX\"");
  delay(1000);

  gsm.print("Alert! Temp=");
  gsm.print(temp);
  gsm.print("C Hum=");
  gsm.print(hum);
  gsm.println("%");

  delay(1000);
  gsm.write(26); // Ctrl+Z
  delay(5000);
}

void setup() {
  Serial.begin(9600);
  gsm.begin(9600);

  dht.begin();

  Serial.println("System Ready");
}

void loop() {

  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();

  if (isnan(humidity) || isnan(temperature)) {
    Serial.println("DHT Error");
    return;
  }

  Serial.print("Temp: ");
  Serial.print(temperature);
  Serial.print(" C  Humidity: ");
  Serial.print(humidity);
  Serial.println(" %");

  if (temperature > 30) {

    if (!smsSent) {
      sendSMS(temperature, humidity);
      smsSent = true;
    }

  } else {
    smsSent = false;
  }

  delay(2000);
}
