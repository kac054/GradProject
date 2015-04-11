import xml.etree.cElementTree as ET	
tagfile="/var/www/html/tags.xml"
root = ET.Element("root")
tree = ET.ElementTree(root)
tree.write(tagfile)
