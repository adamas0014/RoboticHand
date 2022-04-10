from cvzone.HandTrackingModule import HandDetector
import cv2
import paho.mqtt.client as mqtt
import serial
import time


def on_connect(mqttc, obj, flags, rc):
    print("rc: " + str(rc))


def on_message(mqttc, obj, msg):
    print(msg.topic + " " + str(msg.qos) + " " + str(msg.payload))


def on_publish(mqttc, obj, mid):
    print("mid: " + str(mid))
    pass


def on_subscribe(mqttc, obj, mid, granted_qos):
    print("Subscribed: " + str(mid) + " " + str(granted_qos))


def on_log(mqttc, obj, level, string):
    print(string)


# If you want to use a specific client id, use
# mqttc = mqtt.Client("client-id")
# but note that the client id must be unique on the broker. Leaving the client
# id parameter empty will generate a random id for you.
mqttc = mqtt.Client()
mqttc.on_message = on_message
mqttc.on_connect = on_connect
mqttc.on_publish = on_publish
mqttc.on_subscribe = on_subscribe
# Uncomment to enable debug messages
# mqttc.on_log = on_log
mqttc.connect("test.mosquitto.org", 1883, 60)

mqttc.loop_start()

cap = cv2.VideoCapture(0)
detector = HandDetector(detectionCon=0.8, maxHands=1)
mySerial = serial.Serial(port='COM7', baudrate=115200, timeout=.1)

fingersStrPrev = ["0", "0", "0", "0", "0"]

while True:
    time.sleep(0.1) #Had some issues where the arduino wasnt sampling fast enough, this helps aleviate that
    
    # Get image frame
    success, img = cap.read()
    # Find the hand and its landmarks
    hands, img = detector.findHands(img)  # with draw
    # hands = detector.findHands(img, draw=False)  # without draw

    if hands:
        # Hand 1
        hand1 = hands[0]
        lmList1 = hand1["lmList"]  # List of 21 Landmark points
        bbox1 = hand1["bbox"]  # Bounding box info x,y,w,h
        centerPoint1 = hand1['center']  # center of the hand cx,cy
        handType1 = hand1["type"]  # Handtype Left or Right

        fingers = detector.fingersUp(hand1)

        fingersStr = ["", "", "", "", ""]
        index = 0
        for x in fingers:
            if x == 0:
                fingersStr[index] = "1"
            else:
                fingersStr[index] = "0"
            index += 1

        if fingersStr != fingersStrPrev:
            #Concatenate data for movement instruction
            fingersStr2 = "$" + str(fingersStr[0]) + str(fingersStr[1]) + str(fingersStr[2]) + str(fingersStr[3]) + str(fingersStr[4])
            #convert string to bytes for the serial.write()
            fingersB = bytes(fingersStr2, 'utf-8')
            mySerial.write(fingersB)
            #print to terminal
            print(fingersB)
            #set previous value = current value
            fingersStrPrev = fingersStr
            #set up jstring for mqtt
            jsonVar = "{ \"thumbFinger\": " + "\"" + fingersStr[0] + "\"" + ", \"indexFinger\": " + "\"" + fingersStr[
            1] + "\"" + ", \"middleFinger\": " \
                  + "\"" + fingersStr[2] + "\"" + ", \"ringFinger\": " + "\"" + fingersStr[
                      3] + "\"" + ", \"pinkyFinger\": " + "\"" + fingersStr[4] + "\"" + " }"
            #publish
            infot = mqttc.publish("robothand", jsonVar, qos=2)
            infot.wait_for_publish()

    cv2.imshow("Image", img)
    cv2.waitKey(1)

cap.release()
cv2.destroyAllWindows()

