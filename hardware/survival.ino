//na analogové piny 1 - 5 jsou napojené potenciometry
//na hw pin 2 je připojené tlačítko
//výstup z těchto hodnot (u analogových pinů normalizovaný na 0-100) je posílán po seriovém portu
const int pin=2;
const int maxval=20;
void setup()
{
  Serial.begin(9600);
    pinMode(pin, INPUT);  
}

void loop()
{
  Serial.print ("{");
  for(char a=1;a<=5;a++){
    
    int val = 0;           // variable to store the value read  
    float q=0;  
    val = analogRead(a);    // read the input pin
    if (a>1) Serial.print (",");
    q=val/(746/maxval); //max hodnota 746, tak takle dosáhnu 100;
    val=q;
    if (val>maxval) val=maxval; //trim natvrdo
    Serial.print(val );             
  } 
  Serial.print(",");
  Serial.print(!digitalRead(2) );
  Serial.println("}");
  delay(45);
}
