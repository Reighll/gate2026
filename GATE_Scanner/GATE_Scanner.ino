#include "UNIT_UHF_RFID.h"
#include <WiFi.h> // Include the ESP32 Wi-Fi library
#include <HTTPClient.h> // Include HTTP Client for sending data
#include <WiFiClientSecure.h>

// --- PRIMARY Wi-Fi & Server ---
const char* ssid1 = "Nothing Phone (1)";
const char* pass1 = "password";
String server1 = "https://tuptgate.tech/api/scan"; // 

// --- SECONDARY Wi-Fi & Server ---
const char* ssid2 = "Piang";
const char* pass2 = "piajanejao";
String server2 = "https://tuptgate.tech/api/scan"; //

// --- TERTIARY Wi-Fi & Server ---
const char* ssid3 = "UITC_ADMIN";
const char* pass3 = "UITCP@ss*2025";
String server3 = "https://tuptgate.tech/api/scan"; //

// This variable will hold whichever server we actually connect to
String activeServerName = "";

// --- HARDWARE PINS ---
const int BUTTON_PIN = 4;  // Your breadboard tactile button
const int BUZZER_PIN = 13; // The "Quiet" pin for the Active-Low buzzer

// --- BATCH SCAN MEMORY ---
const int MAX_BATCH = 50; 
String batchEPCs[MAX_BATCH];
int batchCount = 0;
int lastButtonState = HIGH; 

// --- NON-BLOCKING BEEP VARIABLES ---
unsigned long beepStartTime = 0;
bool isBeeping = false;
const int BEEP_DURATION = 15; // 15ms quick beep for speed!

Unit_UHF_RFID uhf;

// Function to check if a tag is already in the current batch
bool isDuplicate(String epc) {
    for (int i = 0; i < batchCount; i++) {
        if (batchEPCs[i] == epc) {
            return true;
        }
    }
    return false;
}

// Blocking double-beep used for one-off status events (Wi-Fi connect, etc.)
void doubleBeep() {
    for (int i = 0; i < 2; i++) {
        digitalWrite(BUZZER_PIN, LOW);  // Beep ON (active-low)
        delay(80);
        digitalWrite(BUZZER_PIN, HIGH); // Beep OFF
        delay(80);
    }
}
void singleBeep() {
    digitalWrite(BUZZER_PIN, LOW);  // Beep ON (active-low)
    delay(120);
    digitalWrite(BUZZER_PIN, HIGH); // Beep OFF
}

void setup() {
    // --- SILENCE THE BUZZER FIRST, BEFORE ANYTHING ELSE ---
    // This runs as early as code can possibly run. Note: on power-up/reset,
    // GPIO13 can float or be briefly toggled by the ESP32 boot ROM *before*
    // this line executes, which is why you may still hear a tiny blip at
    // power-on even with this here. If that bothers you, add a physical
    // 10k pull-up resistor from this pin to 3.3V — that keeps the buzzer
    // definitively OFF during boot, before the firmware ever takes over.
    pinMode(BUZZER_PIN, OUTPUT_OPEN_DRAIN); // Fixes the 3.3V vs 5V clash
    digitalWrite(BUZZER_PIN, HIGH);         // HIGH means OFF for a low-level trigger buzzer

    Serial.begin(115200);
    delay(2000);

    // --- Initialize Button ---
    pinMode(BUTTON_PIN, INPUT_PULLUP);

    Serial.println("\n--- Gatepass System Initialization ---");

    // --- SMART WI-FI FALLBACK ---
    Serial.print("Attempting Primary Wi-Fi: ");
    Serial.println(ssid1);
    WiFi.begin(ssid1, pass1);
    
    int retries = 0;
    while (WiFi.status() != WL_CONNECTED && retries < 20) {
        delay(500);
        Serial.print(".");
        retries++;
    }

    if (WiFi.status() == WL_CONNECTED) {
        Serial.println("\n[SUCCESS] Connected to Primary Wi-Fi!");
        activeServerName = server1;
        doubleBeep(); // 2x fast buzz on successful connection
    } else {
        Serial.println("\n[FAILED] Primary Wi-Fi not found.");
        Serial.print("Attempting Secondary Wi-Fi: ");
        Serial.println(ssid2);
        
        WiFi.disconnect(); 
        delay(1000);
        WiFi.begin(ssid2, pass2);
        
        retries = 0;
        while (WiFi.status() != WL_CONNECTED && retries < 20) {
            delay(500);
            Serial.print(".");
            retries++;
        }

        if (WiFi.status() == WL_CONNECTED) {
            Serial.println("\n[SUCCESS] Connected to Secondary Wi-Fi!");
            activeServerName = server2;
            doubleBeep(); // 2x fast buzz on successful connection
        } else {
            Serial.println("\n[FAILED] Secondary Wi-Fi not found.");
            Serial.print("Switching to Tertiary Wi-Fi: ");
            Serial.println(ssid3);
            
            WiFi.disconnect(); 
            delay(1000);
            WiFi.begin(ssid3, pass3);
            
            while (WiFi.status() != WL_CONNECTED) {
                delay(500);
                Serial.print(".");
            }
            Serial.println("\n[SUCCESS] Connected to Tertiary Wi-Fi!");
            activeServerName = server3;
            doubleBeep(); // 2x fast buzz on successful connection
        }
    }
    
    Serial.print("ESP32 IP Address: ");
    Serial.println(WiFi.localIP());
    Serial.print("Active Backend Server: ");
    Serial.println(activeServerName);
    Serial.println("--------------------------------------");

    Serial.println("Starting UHF module on pins 16(RX) and 17(TX)...");
    
    uhf.begin(&Serial2, 115200, 16, 17, false);
    Serial.print("Waiting for UHF module to respond");
    
    while (true) {
        String version = uhf.getVersion();
        if (version != "ERROR") {
            Serial.println("\n[SUCCESS] UHF Module Online.");
            break; 
        }
        Serial.print("."); 
        delay(500);
    }
    
    uhf.setTxPower(2600); // Max power
    Serial.println("✅ Scanner Ready! Hold button to scan students...");
}

void loop() {
    // --- NON-BLOCKING BEEP CHECK ---
    // Turns off the buzzer automatically when time is up, WITHOUT freezing the scanner
    if (isBeeping && (millis() - beepStartTime >= BEEP_DURATION)) {
        digitalWrite(BUZZER_PIN, HIGH); // Turn beep OFF
        isBeeping = false;
    }

    int buttonState = digitalRead(BUTTON_PIN);
    
    // --- 1. BUTTON IS HELD DOWN: SCANNING MODE ---
    if (buttonState == LOW) {
        uint8_t tagsFound = uhf.pollingOnce();
        if (tagsFound > 0) {
            for (uint8_t i = 0; i < tagsFound; i++) {
                String currentEPC = uhf.cards[i].epc_str;
                
                // If it's a new tag we haven't scanned in this batch yet
                if (!isDuplicate(currentEPC) && batchCount < MAX_BATCH) {
                    batchEPCs[batchCount] = currentEPC;
                    batchCount++;
                    
                    // Start the quick success beep WITHOUT stopping the scanner!
                    digitalWrite(BUZZER_PIN, LOW); // Turn beep ON (Low Trigger)
                    beepStartTime = millis();      // Start the stopwatch
                    isBeeping = true;
                    
                    Serial.print("📦 ITEM ADDED: ");
                    Serial.println(currentEPC);
                }
            }
        }
    }
    
    // --- 2. BUTTON IS RELEASED: UPLOAD MODE ---
    // If button was just let go AND we actually scanned some items
    else if (buttonState == HIGH && lastButtonState == LOW) {
        if (batchCount > 0) {
            Serial.println("\n====================================");
            Serial.print("🚀 UPLOADING BATCH! Total Items: ");
            Serial.println(batchCount);
            
            // Construct a comma-separated list of EPCs (e.g. "EPC1,EPC2,EPC3")
            String payloadList = "";
            for (int i = 0; i < batchCount; i++) {
                if (i > 0) payloadList += ",";
                payloadList += batchEPCs[i];
            }
            
            if (WiFi.status() == WL_CONNECTED) {
                HTTPClient http;
                http.begin(activeServerName); // Uses the automatically selected server IP!
                http.addHeader("Content-Type", "application/x-www-form-urlencoded");
                
                // We send them all under the "epc" variable separated by commas
                String httpRequestData = "epc=" + payloadList;
                Serial.print("Sending payload: ");
                Serial.println(httpRequestData);
                
                int httpResponseCode = http.POST(httpRequestData);
                
                if (httpResponseCode > 0) {
                    Serial.print("Success! HTTP Code: ");
                    Serial.println(httpResponseCode);
                    String serverResponse = http.getString();
                    Serial.println("Server says: " + serverResponse);
                    
                    // Long success beep for successful upload
                    digitalWrite(BUZZER_PIN, LOW);
                    delay(500); 
                    digitalWrite(BUZZER_PIN, HIGH);
                } else {
                    Serial.print("Error connecting to server. Code: ");
                    Serial.println(httpResponseCode);
                    
                    // Three quick angry beeps for error
                    for(int i=0; i<3; i++) {
                        digitalWrite(BUZZER_PIN, LOW);
                        delay(100); 
                        digitalWrite(BUZZER_PIN, HIGH); 
                        delay(100);
                    }
                }
                http.end();
            } else {
                Serial.println("⚠️ Wi-Fi Disconnected!");
            }
            Serial.println("====================================\n");
            
            // Clear the batch ready for the next student
            batchCount = 0;
        }
    }
    
    lastButtonState = buttonState;
    // Only rest when the button is NOT pressed. When held down, scan at max speed!
    if (buttonState == HIGH) {
        delay(20); 
    }
}
