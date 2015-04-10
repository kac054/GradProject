import dfxml,fiwalk
import sys,os,os.path,datetime,getopt

if __name__=="__main__":
    from optparse import OptionParser
    parser = OptionParser()
    parser.usage = '%prog [options] xmlfile fileid [x1 x2 x3]\nAdd tags x1, x2, x3 ... to xml object'
    (options,args) = parser.parse_args()

	if len(args)<3:
		parser.print_help()
		exit(1)
	    

	tags = set([fn.lower() for fn in args[2:]])


	f = open("/var/www/html/Cases/Case1/Dropbox/primaryXML.xml")
	(doc,fobjs) = fiwalk.fileobjects_using_dom(xmlfile=f)
	for fi in fobjs:
		if (fi.id() in targets):
			print fi.filename()


