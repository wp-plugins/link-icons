<?php
function link_icons($content)
{
	if(strlen($content)>1)
	{
		//error_log($content, 0);
		$D = new DOMDocument;
		$content=mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8"); 
		$D->loadHTML($content);
		processElement($D);
		return $D->saveHTML();
	}
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
	$useHTTPHeader=0;
	//error_log(get_option('icon-links-use-http-header'), 0);
	
	if(get_option('icon-links-use-http-header')) $useHTTPHeader=1;
	
	
	
	
	
	if(is_array($contentType)) $contentType = $contentType[0];
	
	// is the link a directlink to an image?
	// I could here just check if the url ends in jpg/jpeg/gif/png, but what if it is link to a php file with imaagick? 
	// so we gonna check the mimetipe.
	
	if(isImage($url,$useHTTPHeader))
	{
		$child->setAttribute("class", "link-icons-image");	
	}
	
	// is the link a directlink to an video?
	// we gonna check the mimetipe.
	elseif(	isVideo($url,$useHTTPHeader) || preg_match('/\youtube\.com\/watch.*/',$url) || preg_match('/\youtu\.be\/.*/',$url))
	{
		$child->setAttribute("class", "link-icons-video");	
	}
	
	// checking if it is a internal or external link
	elseif(substr($linkURL,0,7)=="http://" || substr($linkURL,0,8)=="https://")
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

function isImage($url, $useHTTPHeader)
{
	// if we gonna use http headers
	if($useHTTPHeader==1)
	{
		$header=get_headers($url,1);
		$contentType=$header['Content-Type'];
		if(is_array($contentType)) $contentType = $contentType[0];
		if(substr($contentType,0,5)=="image") return true;
	}
	else
	{
		// Since http header can't tell us we check the file ending
		$exts=array('jpg','jpeg','gif','png');		
		
		// removing potential variables
		$urlbits=explode("?",$url);
		$url=$urlbits[0];
		
		// getting extention
		$urlbits=explode(".",$url);
		$ext=strtolower($urlbits[count($urlbits)-1]);
		
		if(in_array($ext,$exts)) return true;
	}
	return false;
}

function isVideo($url, $useHTTPHeader)
{
	// if we gonna use http headers
	if($useHTTPHeader==1)
	{
		$header=get_headers($url,1);
		$contentType=$header['Content-Type'];
		if(is_array($contentType)) $contentType = $contentType[0];
		if(substr($contentType,0,5)=="video") return true;
	}
	else
	{
		// Since http header can't tell us we check the file ending
		$exts=array('mkv','mpeg','mpg','avi','mp4','mov');		
		
		// removing potential variables
		$urlbits=explode("?",$url);
		$url=$urlbits[0];
		
		// getting extention
		$urlbits=explode(".",$url);
		$ext=strtolower($urlbits[count($urlbits)-1]);
		
		if(in_array($ext,$exts)) return true;
	}
	return false;
}

?>