import subprocess
import time
import os.path
import os
import signal
import sys
import getopt

#handle arguments
def main(argv):
	disk = ''
	savepath = ''
	threads=0
	case=''
	try:
		opts, args = getopt.getopt(argv,"hi:o:t:f:",["disk=","save=", "thread=", "folder="])
	except getopt.GetoptError:
		print 'pyrun.py -i <disk> -o <savepath> -t <threads> -f <folder>'
		sys.exit(2)
	for opt, arg in opts:
		if opt == '-h':
			print 'test.py -i <disk> -o <savepath> -t <thread> -f <folder>'
			sys.exit()
		elif opt in ("-i", "--disk"):
			disk = arg
		elif opt in ("-o", "--save"):
			savepath = arg
		elif opt in ("-t", "--thread"):
			threads=arg
		elif opt in ("-f", "--folder"):
			folder=arg
	#get blockcount
	Bcount= subprocess.check_output(["blockdev --getsize64 "+str(disk)+""], shell=True)
	corecount=int(subprocess.check_output(["nproc"],shell=True))
	#if user specifies threadcount, overwrite
	if (int(threads) != 0):
		corecount=int(threads);
	counter= corecount -1

	blocks = int(Bcount)/512

	#size each imager performs on
	chunk = blocks / corecount

	#starting location of each imager, increments based on chunk size
	start = 0

	#start first imager
	firstimage= subprocess.Popen(["dd if="+str(disk)+" of="+str(savepath)+" bs=512 count="+str(chunk)+""], shell=True)
	start=start+chunk
	i=1

	#to-do=on each process start, record process id in array for later reference and operations

	#launch middle imagers, last started outside of loop because no count, it finishes whatever is left
	while i < counter:
		image= subprocess.Popen(["dd if="+str(disk)+" of="+str(savepath)+" bs=512 seek="+str(start)+" skip="+str(start)+" count="+str(chunk)+""], shell=True)
	#increment starting position of imagers based on chunk size	
		start=start+ chunk
		i = i+1
	#last image started, finishes whatever is left in the image. allows for uneven distribution
	lastimage= subprocess.Popen(["dd if="+str(disk)+" of="+str(savepath)+" bs=512 seek="+str(start)+" skip="+str(start)+" "], shell=True)

	time.sleep(20)

	#create XML save names and start first fiwalk
	primeXMLname=folder+"/primaryXML.xml"
	secondXMLname=folder+"/secondaryXML.xml"
	tagfile=folder+"/tags.xml"
	root = ET.Element("root")
	tree = ET.ElementTree(root)
	tree.write(tagfile)
	p= subprocess.Popen(["fiwalk -X "+str(primeXMLname)+" "+str(savepath)+""], shell=True)
	time.sleep(10)

	#redo later
	#while first imager is still running, restart fiwalk every 20 seconds
	while firstimage.poll() is None:
		p.kill()
	#if a second file is already created, make it primary and make new secondary	
		if os.path.exists(case+"SecondaryXML.xml") ==True:		
			os.rename(case+"SecondaryXML.xml",case+"PrimaryXML.xml")
	#if there is a primary but no secondary, dont remove secondary	
		elif os.path.exists(case+"PrimaryXML.xml") ==True:			
			p= subprocess.Popen(["fiwalk -X " +str(secondXMlname)+" "+str(savepath)+""], shell=True)
		time.sleep(20)
#wait for last image pass to finish	
	p.wait()
#replace primary
	os.remove(case+"PrimaryXML.xml")
	os.rename(case+"SecondaryXML.xml",case+"PrimaryXML.xml")

#final XML run over finished image
	p= subprocess.Popen(["fiwalk -X " +str(secondXMlname)+" "+str(savepath)+""], shell=True)
	p.wait()
	os.rename(case+"SecondaryXML.xml",case+"PrimaryXML.xml")
#attempt to close running processes, cleanup
	try:
		p.kill()
		firstimage.kill()
		lastimage.kill()
		image.kill()
	except:
		pass

if __name__ == "__main__":
   main(sys.argv[1:])
