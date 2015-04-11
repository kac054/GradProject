import argparse
import xml.etree.cElementTree as ET
import os.path

if __name__=="__main__":
	parser = argparse.ArgumentParser(description="Add tag to a files XML")
	parser.add_argument('filepath', metavar='filepath', help='folder path for evidence item')
	parser.add_argument('tag', metavar='tag', help='tag to add to files')
	parser.add_argument('ids', nargs='+', help='ID\'s to add tags to')
	args=parser.parse_args()

	save= args.filepath
	save = save + "/tags.xml"
	tmp = args.ids
	tree=ET.ElementTree(file=save)
	root=tree.getroot()
	for item in tmp:
		fileexists=False
		tagexists=False
		for elem in root.iter('fileobject'):			
			if(elem[0].text == item):
				print "File exists, checking if wanted tag exists"
				fileexists=True
				for child in elem.iter('tag'):				
					#if tag already exists, it is removed instead of added					
					if(child.text == args.tag):
						print "deleting tag"
						elem.remove(child)
						tagexists=True
				if(tagexists==False):
					#tag didnt exist, create it
					print "creating new tag"
					newtag = ET.Element("tag")
					newtag.text= args.tag
					elem.append(newtag)
		if(fileexists==False):
			#create fileobject for tagging
			print "file didnt exist, creating object"	
			fileelem= ET.Element("fileobject")
			tmp=ET.Element("id")
			tmp.text=item
			fileelem.append(tmp)
			tmp=ET.Element("tag")
			tmp.text=args.tag
			fileelem.append(tmp)
			root.append(fileelem)

					
	tree.write(save)				

