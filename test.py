import time
import RPi.GPIO as GPIO


GPIO.setmode(GPIO.BCM)

#button[0] - pin, button[1] - last state

buttons = [[18, 0], [23, 0], [24, 0]]
for button in buttons:
	GPIO.setup(button[0],GPIO.IN)

def pressed():
	for button in buttons:
		input = GPIO.input(button[0])
		if ((not button[1]) and input):
			pressed = button[0]
		else:
			pressed = False;
			
		button[1] = input
		time.sleep(0.05)
		if pressed:
			return pressed

while True:
	print pressed()

# #initialise a previous input variable to 0 (assume button not pressed last)
# prev_input = 0
# while True:
#   #take a reading
#   input = GPIO.input(18)
#   #if the last reading was low and this one high, print
#   if ((not prev_input) and input):
#     print("Button pressed")
#   #update previous input
#   prev_input = input
#   #slight pause to debounce
#   time.sleep(0.05)
		

