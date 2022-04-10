#include <Servo.h>
#include <String.h>

Servo myservo1;  // create servo object to control a servo
// twelve servo objects can be created on most boards
Servo myservo2;
Servo myservo3;
Servo myservo4;
Servo myservo5;
int pos = 0;    // variable to store the servo position

String mystring;

void setup() {
  Serial.begin(115200);
  myservo1.attach(7);  // attaches the servo on pin 7 to the servo object
  myservo2.attach(6);
  myservo3.attach(5);
  myservo4.attach(4);
  myservo5.attach(3);
}

void loop() {
if(Serial.available( ) > 0){
  mystring = Serial.readString();
  if(mystring[0] == '$'){
    if(mystring[1] == '1'){
      myservo1.write(180);
    }
    else{
      myservo1.write(0);
    }
    if(mystring[2] == '1'){
      myservo2.write(0);
    }
    else{
      myservo2.write(180);
    }
    if(mystring[3] == '1'){
      myservo3.write(180);
    }
    else{
      myservo3.write(0);
    }
    if(mystring[4] == '1'){
      myservo4.write(180);
    }
    else{
      myservo4.write(0);
    }
    if(mystring[5] == '1'){
      myservo5.write(180);
    }
    else{
      myservo5.write(0);
    }
  }
}
}
