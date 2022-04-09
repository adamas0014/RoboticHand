#include <Servo.h>

#define numOfValsRec 5
#define digitsPerValRec 1

Servo servoThumb;
Servo servoIndex;
Servo servoMiddle;
Servo servoRing;
Servo servoPinky;

int valsRec[numOfValsRec];
int stringLength = numOfValsRec * digitsPerValRec + 1;
int counter = 0;
bool counterStart = false;
String receivedString;

void setup() 
{
  Serial.begin(115200);
  pinMode(7, OUTPUT);
  pinMode(6, OUTPUT);
  pinMode(5, OUTPUT);
  pinMode(4, OUTPUT);
  pinMode(3, OUTPUT);
  servoThumb.attach(12);
  servoIndex.attach(11);
  servoMiddle.attach(10);
  servoRing.attach(9);
  servoPinky.attach(8);
}

void receiveData()
{
  while(Serial.available())
  {
    
    char data[5];
    char c = Serial.read();

    if (c == '$')
    {
        for(int i = 0; i < 5 /*&& i != '\n'*/; i++){
        data[i] = Serial.read();
        }
   
    }
    if(data[0] == '0'){/* for servo change to servo.Write, for High its servo.Write(180) and for low its servo.Write(0) */
      servoThumb.write(180);
      digitalWrite(7, HIGH);
    }
    else{
      servoThumb.write(0);
      digitalWrite(7, LOW);
    }

    
    
    if(data[1] == '0'){
      servoIndex.write(180);
      digitalWrite(6, HIGH);
    }
    else{
      servoIndex.write(0);
      digitalWrite(6, LOW);
    }

    
    if(data[2] == '0'){
      servoMiddle.write(180);
      digitalWrite(5, HIGH);
    }
    else{
      servoMiddle.write(0);
      digitalWrite(5, LOW);
    }


    if(data[3] == '0'){
      servoRing.write(180);
      digitalWrite(4, HIGH);
    }
    else{
      servoRing.write(0);
      digitalWrite(4, LOW);
    }

    if(data[4] == '0'){
      servoPinky.write(180);
      digitalWrite(3, HIGH);
    }
    else{
      servoPinky.write(0);
      digitalWrite(73, LOW);
    }
  }
    /*
    

    
    if (counterStart)
    {
      if (counter < stringLength)
      {
        receivedString = String(receivedString + c);
        counter++;
      }
      if (counter >= stringLength)
      {
        for (int i = 0; i < numOfValsRec; i++)
        {
          int num = (i * digitsPerValRec) + 1;
          valsRec[i] = receivedString.substring(num,num + digitsPerValRec).toInt();
        }
        receivedString = "";
        counter = 0;
        counterStart = false;
      }
    }
    */
  
}

void loop() 
{
  receiveData();
  if (valsRec[0] == 1) {servoThumb.write(180);} else{servoThumb.write(0);}
  if (valsRec[0] == 1) {servoIndex.write(180);} else{servoIndex.write(0);}
  if (valsRec[0] == 1) {servoMiddle.write(180);} else{servoMiddle.write(0);}
  if (valsRec[0] == 1) {servoRing.write(180);} else{servoRing.write(0);}
  if (valsRec[0] == 1) {servoPinky.write(180);} else{servoPinky.write(0);}
}
