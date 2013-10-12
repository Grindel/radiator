#!/usr/bin/env python
# -*- coding: utf-8 -*-

import daemon, radiator
import sys, time, tempfile


class RadiatorDaemon(daemon.Daemon):
	def run(self):
		r = radiator.Radiator('/home/pi/radiator/radiator.sqlite3')
		while True:
			r.action()

if __name__ == '__main__':
	pidFile = tempfile.gettempdir() + '/radiatorDaemon.pid'
	daemon = RadiatorDaemon(pidFile)
	if len(sys.argv) == 2:
		if 'start' == sys.argv[1]:
			print 'Daemon starting..'
			daemon.start()
			print 'Daemon started!'
		elif 'stop' == sys.argv[1]:
			print 'Daemon stopping..'
			daemon.stop()
			print 'Daemon stopped!'
		elif 'restart' == sys.argv[1]:
			print 'Daemon restarting..'
			daemon.restart()
			print 'Daemon restarted!'
		else:
			print 'Unknown command'
			sys.exit(2)
		sys.exit(0)
	else:
		print 'usage: %s start|stop|restart' % sys.argv[0]
		sys.exit(2)
		