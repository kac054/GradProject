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
	xmlroot=tree.getroot()
	for item in tmp:
		for elem in tree.iter():
			if (elem.id == item):
				ET.SubElement(elem, "Tag").text = args.tag
				
#		doc = ET.SubElement(root, "fileobject")
#		ET.SubElement(doc, "ID").text = item
#		ET.SubElement(doc, "Tag").text = args.tag

#		tree = ET.ElementTree(root)
#		tree.write(save)
