#!/usr/bin/env python

from mpd import MPDClient
from subprocess import call
import sqlite3, time
import RPi.GPIO as GPIO

class Radiator:

	def __init__(self, db = 'radiator.sqlite3'):
		
		self.stations      = []
		
		#DB SETUP
		self.db = sqlite3.connect(db)
		self.c = self.db.cursor()
		#MPD SETUP
		self.mpd = MPDClient()
		self.mpd.timeout = 10
		self.mpd.idletimeout = None
		self.mpd.connect("localhost", 6600)
		self.mpd.clear()
		self.play()
		
		#GET STATIONS
		self.c.execute('SELECT id, name, url FROM stations')
		for row in self.c:
			self.stations.append({'id': row[0], 'name': row[1], 'url': row[2], 'pl_id': self.mpd.addid(row[2])})

		#SET BUTTONS
		#button[0] - pin, button[1] - last state
		GPIO.setmode(GPIO.BCM)
		self.buttons = [[18, 0], [23, 0], [24, 0]]
		for button in self.buttons:
			GPIO.setup(button[0],GPIO.IN)


	def pressed(self):
		for button in self.buttons:
			input = GPIO.input(button[0])
			if ((not button[1]) and input):
				pressed = button[0]
			else:
				pressed = False;
			
			button[1] = input
			time.sleep(0.05)
			if pressed:
				return pressed
		return False

	def play(self):
		self.mpd.play();

	def next(self):
		playlist = self.mpd.playlistinfo();
		if self.mpd.currentsong()['id'] == playlist[len(playlist) - 1]['id']:
			self.mpd.playid(playlist[0]['id'])
		else:
			self.mpd.next()

	def previous(self):
		playlist = self.mpd.playlistinfo();
		if self.mpd.currentsong()['id'] == playlist[0]['id']:
			self.mpd.playid(playlist[len(playlist) - 1]['id'])
		else:
			self.mpd.previous()


	def getStByPl_id(self, id):
		for st in self.stations:
			if st['pl_id'] == id:
				return st;
		return False

	def bookmark(self):
		if self.mpd.status()['state'] != 'play':
			return
		station = self.getStByPl_id(self.mpd.status()['songid'])
		songTitle = self.mpd.currentsong()['title']
		sampleName = str(int(time.time()))
		call(['streamripper', station['url'], '-d', 'bookmarks', '-A', '-a', "%s_.mp3" % sampleName ,'-l', '5'])
		call(['lame', '-f', '-q', '9', "bookmarks/%s_.mp3" % sampleName, "bookmarks/%s.mp3" % sampleName ])
		call(['rm', "bookmarks/%s_.cue" % sampleName])
		call(['rm', "bookmarks/%s_.mp3" % sampleName])
		self.c.execute("INSERT INTO bookmarks (station_id, name, sample) VALUES ('%s', '%s', '%s')" % (station['id'], songTitle, sampleName))
		self.db.commit()

	def action(self):
		button = self.pressed()
		if not button:
			return

		if button == 23:
			self.next()
		elif button == 24:
			self.previous()
		elif button == 18:
			self.bookmark()



if __name__ == '__main__':
    r = Radiator()
    r.play()