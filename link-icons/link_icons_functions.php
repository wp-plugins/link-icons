
	<?php
function link_icons($content)
{
	$D = new DOMDocument;
	$content=mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8"); 
	$D->loadHTML($content);
	processElement($D);
	
	return $D->saveHTML();
}	

function processElement(DOMNode $element){
    foreach($element->childNodes as $child){
		if($child instanceOf DOMElement){
           switch($child->nodeName){
            case 'a':
				foreach ($child->attributes as $attr) {
					$name = $attr->nodeName;
					$value = $attr->nodeValue;	
					if($name=="href")
					{
						linkType($value,$child);
						
					}
					
					}
                break;           
            default:
                processElement($child);
            }
		}
    }
}

function linkType($linkURL,$child){

	if(substr($linkURL,0,7)=="http://")
	{
		$linkURL=substr($linkURL,7,strlen($linkURL)-7);	
		
		//getting just the host/domain
		$linkURL=explode("/",$linkURL);
		$linkURL=$linkURL[0];
		
		if($linkURL!=$_SERVER['HTTP_HOST'])
		{
			$child->setAttribute("class", "link-icons-external");
		}
		else
		{
			$child->setAttribute("class", "link-icons-internal");
		}
	}
	else // if the href does not have http:// it's internal
	{
		$child->setAttribute("class", "link-icons-internal");
	}
	
}
		
?>