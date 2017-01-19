//na analogové piny jsou napojené potenciometry
const int pin=2;
const int maxval=10;
void setup()
{
  Serial.begin(9600);
    pinMode(pin, INPUT);  
}
int t[7];
void loop()
{
  Serial.print ("{");
  for(char a=1;a<=5;a++){
    
    int val = 0;           // variable to store the value read  
    float q=0;  
    val = analogRead(a);    // read the input pin
    if (a>1) Serial.print (",");
    
    q=val/(760/maxval); //max hodnota 746, tak takle dosáhnu 100;
    val=q;
    
    if (val>maxval) val=maxval; //trim natvrdo
    if(t[a]==val){
        Serial.print(val );             
      } else{
        Serial.print(t[a] );             
        }

    t[a]=val;
    
  } 
  Serial.print(", ");
  Serial.print(!digitalRead(2) );
  Serial.println("}");
  delay(90);
}
