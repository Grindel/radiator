
from mpd import MPDClient
import sqlite3

ids = []

db = sqlite3.connect('radiator.sqlite3')
c = db.cursor()

mpd = MPDClient()               
mpd.timeout = 10                
mpd.idletimeout = None          
mpd.connect("localhost", 6600)

mpd.clear()
c.execute('SELECT * FROM stations')
for row in c:
	ids.append( [ row[0], mpd.addid(row[1]) ] )

mpd.play()
print(mpd.playlistinfo())
# mpd.playid(1)