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
				if($child->textContent!=""){
					foreach ($child->attributes as $attr) {
						$name = $attr->nodeName;
						$value = $attr->nodeValue;	
						if($name=="href")
						{
							linkType($value,$child);
						}
					}
				}
                break;           
            default:
                processElement($child);
            }
		}
    }
}

function linkType($url,$child){
	
	$linkURL = $url;
	$header=get_headers($url,1);	
	$contentType=$header['Content-Type'];
	
	// is the link a directlink to an image?
	// I could here just check if the url ends in jpg/jpeg/gif/png, but what if it is link to a php file with imaagick? 
	// so we gonna check the mimetipe.
	
	if(substr($contentType,0,5)=="image")
	{
		$child->setAttribute("class", "link-icons-image");	
	}
	
	// is the link a directlink to an video?
	// we gonna check the mimetipe.
	elseif(substr($contentType,0,5)=="video" || preg_match('/\youtube\.com\/watch.*/',$url))
	{
		$child->setAttribute("class", "link-icons-video");	
	}
	
	// checking if it is a internal or external link
	elseif(substr($linkURL,0,7)=="http://")
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