// This is a code for 5 sensors to collect data, which was used in the current calibration procedure. 
// Users are free to delete or comment parts of the code to use the required number of sensors for calibration.

#define powersupply1 2                 //Supplying power to YL-69/YL-100 sensor through digital pin 2
#define powersupply2 3                 //Supplying power to YL-69/YL-100 sensor through digital pin 3
#define powersupply3 4                 //Supplying power to the golden sensor through digital pin 4
#define powersupply4 5                 //Supplying power to the golden sensor through digital pin 5
#define powersupply5 6                 //Supplying power to the golden sensor through digital pin 6
#define powersupply8 8                 //Providing reference to insensordata pin

// The analog pins that connect to the sensors (outsensordata) and measure the applied voltage(insensordata)
// Although the insensordata pin won't be able to detect the true voltage of the system as the reference voltage
// for the analog pins changes with the voltage of the module, it will show us if there are any discrepancies.
// The value received from insensordata should always be 1023.

#define insensordata 0                   //Input voltage (to the sensor) at analog 0
#define outsensordata1 1                 //Output voltage (from the sensor) at analog 1
#define outsensordata2 2                 //Output voltage (from the sensor) at analog 2
#define outsensordata3 3                 //Output voltage (from the sensor) at analog 3
#define outsensordata4 4                 //Output voltage (from the sensor) at analog 4
#define outsensordata5 5                 //Output voltage (from the sensor) at analog 5

// how many milliseconds between grabbing data and logging it. 1000 ms is once a second
#define LOG_INTERVAL  20000 // mills between entries (reduce to take more/faster data)

void setup() {
  // put your setup code here, to run once:

 Serial.begin(9600);
  Serial.println();

  pinMode(powersupply1, OUTPUT);
  pinMode(powersupply2, OUTPUT);
  pinMode(powersupply3, OUTPUT);
  pinMode(powersupply4, OUTPUT);
  pinMode(powersupply5, OUTPUT);
  pinMode(powersupply8, OUTPUT);    //Providing input to 

 Serial.println("millis\tinputvoltage\toutputvoltage_1\toutputvoltage_2\toutputvoltage_3\toutputvoltage_4\toutputvoltage_5");
}
void loop() {
  // The golden sensors and YL-69/YL-100 provide complementary values for the same moisture content.
  // Hence the YL-69/YL-100 sensor values are subtracted from 1023, the highest value, for standardization.

  

  uint32_t m = millis()/1000;
  Serial.print(m);         // milliseconds since start
  Serial.print("\t\t");  
  
  digitalWrite(powersupply8, HIGH);
  delay(2000); 
  int insensorVal8 = analogRead(insensordata);    
  digitalWrite(powersupply8, LOW);
  Serial.print(insensorVal8);
  Serial.print("\t\t");   
  
  digitalWrite(powersupply1, HIGH);
  delay(2000);
  int outsensorVal1 = 1023-analogRead(outsensordata1);    
  digitalWrite(powersupply1, LOW);
  Serial.print(outsensorVal1);
  Serial.print("\t\t");   

  digitalWrite(powersupply2, HIGH);
  delay(2000); 
  int outsensorVal2 = 1023-analogRead(outsensordata2);    
  digitalWrite(powersupply2, LOW);
  Serial.print(outsensorVal2);
  Serial.print("\t\t");   

  digitalWrite(powersupply3, HIGH);
  delay(2000); 
  int outsensorVal3 = analogRead(outsensordata3);    
  digitalWrite(powersupply3, LOW);
  Serial.print(outsensorVal3);
  Serial.print("\t\t");   

  digitalWrite(powersupply4, HIGH);
  delay(2000);  
  int outsensorVal4 = analogRead(outsensordata4);    
  digitalWrite(powersupply4, LOW);
  Serial.print(outsensorVal4);
  Serial.print("\t\t");   

  digitalWrite(powersupply5, HIGH);
  delay(2000);
  int outsensorVal5 = analogRead(outsensordata5);    
  digitalWrite(powersupply5, LOW);
  Serial.print(outsensorVal5);
  Serial.print("\t\t");   

  Serial.println();

  delay((LOG_INTERVAL -1) - (millis() % LOG_INTERVAL));

}

