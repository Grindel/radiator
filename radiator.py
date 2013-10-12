#!/usr/bin/env python

from mpd import MPDClient
from subprocess import call
import sqlite3, time
import RPi.GPIO as GPIO

class Radiator:

	def __init__(self, db = '/home/pi/radiator/radiator.sqlite3'):
		
		self.conf		= {}
		self.stations	= []
		self.homeDir	= "/home/pi/radiator"	
		
		#DB SETUP
		self.db = sqlite3.connect(db)
		self.c = self.db.cursor()
		
		#GET CONFIG
		self.c.execute('SELECT key, value FROM conf')
		for row in self.c:
			self.conf[row[0]] = row[1]

		#MPD SETUP
		self.mpd = MPDClient()
		self.mpd.connect("/var/run/mpd/socket", 6600)
		
		#SET STATIONS
		self.c.execute('SELECT id, name, url FROM stations ORDER BY stations.id DESC')
		for row in self.c: 
			self.stations.append({'id': row[0], 'name': row[1], 'url': row[2]})

		if self.conf['stationsV'] != self.conf['stationsU'] or len(self.mpd.playlistinfo()) != len(self.stations):
			self.mpd.clear()
			for st in self.stations:
				print st['url']
				st['pl_id'] = self.mpd.addid(st['url'])

			self.c.execute("UPDATE conf SET value = '%s' WHERE key = 'stationsU'" % self.conf['stationsV'])
			self.db.commit()
		else:
			for st in self.stations:
				st['pl_id'] = self.getPlStationByUrl(st['url'])['id']

		#AUTO PLAY!
		self.play()

		#SET BUTTONS AND LED
		GPIO.setwarnings(False)
		GPIO.setmode(GPIO.BCM)
		#button[0] - pin, button[1] - last state
		self.buttons = [[18, 0], [23, 0], [24, 0]]
		self.LED = 4;
		GPIO.setup(self.LED,GPIO.OUT)
		GPIO.output(self.LED, False)
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

	def getPlStationByUrl(self, url):
		plStations = self.mpd.playlistinfo()
		for st in plStations:
			if st['file'] == url:
				return st;
		return False

	def bookmark(self):
		if self.mpd.status()['state'] != 'play':
			return
		try:
			station = self.getStByPl_id(self.mpd.status()['songid'])
			songTitle = self.mpd.currentsong()['title']
			sampleName = str(int(time.time()))
		except:
			return
		GPIO.output(self.LED, True)
		self.c.execute("INSERT INTO bookmarks (station_id, name) VALUES (?, ?)", [station['id'], songTitle])
		bookmarkId = self.c.lastrowid
		self.db.commit()
		call(['streamripper', station['url'], '-d', "%s/www/bookmarks" % self.homeDir, '-A', '-a', "%s_" % sampleName ,'-l', '10'])
		call(['lame', "%s/www/bookmarks/%s_.mp3" % (self.homeDir, sampleName), "%s/www/bookmarks/%s.mp3" % (self.homeDir, sampleName) ])
		call(['rm', "%s/www/bookmarks/%s_.cue" % (self.homeDir, sampleName)])
		call(['rm', "%s/www/bookmarks/%s_.mp3" % (self.homeDir, sampleName)])
		self.c.execute("UPDATE bookmarks SET sample = '%s' WHERE id = %s" % (sampleName, bookmarkId))
		self.db.commit()
		GPIO.output(self.LED, False)

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
    while True:
    	r.action()