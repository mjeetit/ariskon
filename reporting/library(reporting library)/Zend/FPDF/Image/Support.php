<?php
class Zend_FPDF_Image_Support extends ImageSupport  {
	var $unifontSubset;	
	var $extraFontSubsets = 0;
	var $t1asm;
	var $page;               //current page number
	var $n;                  //current object number
	var $offsets;            //array of object offsets
	var $buffer;             //buffer holding in-memory PDF
	var $pages;              //array containing pages
	var $state;              //current document state
	var $compress;           //compression flag
	var $k;                  //scale factor (number of points in user unit)
	var $DefOrientation;     //default orientation
	var $CurOrientation;     //current orientation
	var $PageFormats;        //available page formats
	var $DefPageFormat;      //default page format
	var $CurPageFormat;      //current page format
	var $PageSizes;          //array storing non-default page sizes
	var $wPt,$hPt;           //dimensions of current page in points
	var $w,$h;               //dimensions of current page in user unit
	var $lMargin;            //left margin
	var $tMargin;            //top margin
	var $rMargin;            //right margin
	var $bMargin;            //page break margin
	var $cMargin;            //cell margin
	var $x,$y;               //current position in user unit
	var $lasth;              //height of last printed cell
	var $LineWidth;          //line width in user unit
	var $CoreFonts;          //array of standard font names
	var $fonts;              //array of used fonts
	var $FontFiles;          //array of font files
	var $diffs;              //array of encoding differences
	var $FontFamily;         //current font family
	var $FontStyle;          //current font style
	var $underline;          //underlining flag
	var $CurrentFont;        //current font info
	var $FontSizePt;         //current font size in points
	var $FontSize;           //current font size in user unit
	var $DrawColor;          //commands for drawing color
	var $FillColor;          //commands for filling color
	var $TextColor;          //commands for text color
	var $ColorFlag;          //indicates whether fill and text colors are different
	var $ws;                 //word spacing
	var $images;             //array of used images
	var $PageLinks;          //array of links in pages
	var $links;              //array of internal links
	var $AutoPageBreak;      //automatic page breaking
	var $PageBreakTrigger;   //threshold used to trigger page breaks
	var $InHeader;           //flag set when processing header
	var $InFooter;           //flag set when processing footer
	var $ZoomMode;           //zoom display mode
	var $LayoutMode;         //layout display mode
	var $title;              //title
	var $subject;            //subject
	var $author;             //author
	var $keywords;           //keywords
	var $creator;            //creator
	var $AliasNbPages;       //alias for total number of pages
	var $PDFVersion;         //PDF version number
 	
	
	public function CheckImage($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='')
	{	
		//Put an image on the page
		if(!isset($this->images[$file]))
		{
			//First use of this image, get info
			if($type=='')
			{
				$pos=strrpos($file,'.');
				if(!$pos)
					$this->ImageError('Image file has no extension and no type was specified: '.$file);
				$type=substr($file,$pos+1);
			}
			$type=strtolower($type);
			if($type=='jpeg')
				$type='jpg';
			$mtd='_checkparse'.$type;
			
			if(!method_exists(Zend_FPDF_Image_Support,$mtd))
				self::ImageError('Unsupported image type: '.$type);
			$info=self::$mtd($file);
			$info['i']=count($this->images)+1;
			$this->images[$file]=$info;
			return $this->images[$file];
		}
	}
 
	public function _checkparsejpg($file)
	{
		//Extract info from a JPEG file
		
		$a = getimagesize($file);
		if(!$a)
			$this->ImageError('Missing or incorrect image file: '.$file);
		if($a[2]!=2)
			$this->ImageError('Not a JPEG file: '.$file);
		if(!isset($a['channels']) || $a['channels']==3)
			$colspace='DeviceRGB';
		elseif($a['channels']==4)
			$colspace='DeviceCMYK';
		else
			$colspace='DeviceGray';
		$bpc=isset($a['bits']) ? $a['bits'] : 8;
		//Read whole file
		$f=fopen($file,'rb');
		$data='';
		while(!feof($f))
			$data.=fread($f,8192);
		fclose($f);
		return array('w'=>$a[0], 'h'=>$a[1], 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'DCTDecode', 'data'=>$data);
	}
	
	public function _checkparsepng($file)
	{
		$context = stream_context_create(array(
				'http' => array(
					'header'  => "Authorization: Basic " . base64_encode("test:test")
				)
		));
	
		//Extract info from a PNG file
		$f=fopen($file,'rb',false,$context);
		if(!$f)
			self::ImageError('Can\'t open image file: '.$file);
		//Check signature
		if(self::_readstream($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
			$this->ImageError('Not a PNG file: '.$file);
		//Read header chunk
		self::_readstream($f,4);
		if(self::_readstream($f,4)!='IHDR')
			self::ImageError('Incorrect PNG file: '.$file);
		$w=self::_readint($f);
		$h=self::_readint($f);
		$bpc=ord(self::_readstream($f,1));
		if($bpc>8)
			self::ImageError('16-bit depth not supported: '.$file);
		$ct=ord(self::_readstream($f,1));
		if($ct==0)
			$colspace='DeviceGray';
		elseif($ct==2)
			$colspace='DeviceRGB';
		elseif($ct==3)
			$colspace='Indexed';
		else
			self::ImageError('Alpha channel not supported: '.$file);
		if(ord(self::_readstream($f,1))!=0)
			self::ImageError('Unknown compression method: '.$file);
		if(ord(self::_readstream($f,1))!=0)
			self::ImageError('Unknown filter method: '.$file);
		if(ord(self::_readstream($f,1))!=0)
			self::ImageError('Interlacing not supported: '.$file);
		self::_readstream($f,4);
		$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
		//Scan chunks looking for palette, transparency and image data
		$pal='';
		$trns='';
		$data='';
		do
		{
			$n=self::_readint($f);
			$type=self::_readstream($f,4);
			if($type=='PLTE')
			{
				//Read palette
				$pal=self::_readstream($f,$n);
				self::_readstream($f,4);
			}
			elseif($type=='tRNS')
			{
				//Read transparency info
				$t=self::_readstream($f,$n);
				if($ct==0)
					$trns=array(ord(substr($t,1,1)));
				elseif($ct==2)
					$trns=array(ord(substr($t,1,1)), ord(substr($t,3,1)), ord(substr($t,5,1)));
				else
				{
					$pos=strpos($t,chr(0));
					if($pos!==false)
						$trns=array($pos);
				}
				self::_readstream($f,4);
			}
			elseif($type=='IDAT')
			{
				//Read image data block
				$data.=self::_readstream($f,$n);
				self::_readstream($f,4);
			}
			elseif($type=='IEND')
				break;
			else
				self::_readstream($f,$n+4);
		}
		while($n);
		if($colspace=='Indexed' && empty($pal))
			self::ImageError('Missing palette in '.$file);
		fclose($f);
		return array('w'=>$w, 'h'=>$h, 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'FlateDecode', 'parms'=>$parms, 'pal'=>$pal, 'trns'=>$trns, 'data'=>$data);
	}
 
	public function _checkparsegif($file)
	{ 
	    //Extract info from a GIF file (via PNG conversion)
		if(!function_exists('imagepng'))
			self::ImageError('GD extension is required for GIF support');
		if(!function_exists('imagecreatefromgif'))
			self::ImageError('GD has no GIF read support');
		$im=imagecreatefromgif($file);
		if(!$im)
			self::ImageError('Missing or incorrect image file: '.$file);
		imageinterlace($im,0);
		$tmp=tempnam('.','gif');
		if(!$tmp)
			self::ImageError('Unable to create a temporary file');
		if(!imagepng($im,$tmp))
			self::ImageError('Error while saving to temporary file');
		imagedestroy($im);
		$info=self::_parsepng($tmp);
		unlink($tmp);
		return $info;
    }
 
    public function _parsepng($file)
    {
        $context = stream_context_create(array('http' => array('header'  => "Authorization: Basic " . base64_encode("test:test"))));
        
		//Extract info from a PNG file
        $f = fopen($file,'rb',false,$context);
        
        if(!$f)
            self::Error('Can\'t open image file: '.$file);

        //Check signature
        if(self::_readstream($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
            self::Error('Not a PNG file: '.$file);

        //Read header chunk
        self::_readstream($f,4);
        if(self::_readstream($f,4)!='IHDR')
                $this->Error('Incorrect PNG file: '.$file);
        $w=self::_readint($f);
        $h=self::_readint($f);
        $bpc=ord(self::_readstream($f,1));
        
        if($bpc>8)
            self::Error('16-bit depth not supported: '.$file);

        $ct=ord(self::_readstream($f,1));

        if($ct==0)
            $colspace='DeviceGray';
        elseif($ct==2)
            $colspace='DeviceRGB';
        elseif($ct==3)
            $colspace='Indexed';
        else
            self::Error('Alpha channel not supported: '.$file);

        if(ord(self::_readstream($f,1))!=0)
            self::Error('Unknown compression method: '.$file);

        if(ord(self::_readstream($f,1))!=0)
            self::Error('Unknown filter method: '.$file);

        if(ord(self::_readstream($f,1))!=0)
            self::Error('Interlacing not supported: '.$file);

        self::_readstream($f,4);
        $parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
        //Scan chunks looking for palette, transparency and image data
        $pal='';
        $trns='';
        $data='';
        do
        {
            $n=self::_readint($f);
            $type=self::_readstream($f,4);
            if($type=='PLTE') {
                //Read palette
                $pal=self::_readstream($f,$n);
                self::_readstream($f,4);
            }
            elseif($type=='tRNS') {
                //Read transparency info
                $t=self::_readstream($f,$n);
                if($ct==0)
                    $trns=array(ord(substr($t,1,1)));
                elseif($ct==2)
                    $trns=array(ord(substr($t,1,1)), ord(substr($t,3,1)), ord(substr($t,5,1)));
                else {
                    $pos=strpos($t,chr(0));
                    if($pos!==false)
                        $trns=array($pos);
                }
                self::_readstream($f,4);
            }
            elseif($type=='IDAT') {
                //Read image data block
                $data.=self::_readstream($f,$n);
                self::_readstream($f,4);
            }
            elseif($type=='IEND')
                break;
            else
                self::_readstream($f,$n+4);
        }
        while($n);
        if($colspace=='Indexed' && empty($pal))
            self::Error('Missing palette in '.$file);
        fclose($f);
        return array('w'=>$w, 'h'=>$h, 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'FlateDecode', 'parms'=>$parms, 'pal'=>$pal, 'trns'=>$trns, 'data'=>$data);
    }
 
    public function _readstream($f, $n)
    {
	    //Read n bytes from stream
	    $res='';
	    while($n>0 && !feof($f))
 	    {
			$s=fread($f,$n);
			if($s===false)
				self::ImageError('Error while reading stream');
			$n-=strlen($s);
			$res.=$s;
	    }
	
		if($n>0)
			self::ImageError('Unexpected end of stream');
		return $res;
    }
 
    public function _readint($f)
    {
	    //Read a 4-byte integer from stream
	    $a=unpack('Ni',self::_readstream($f,4));
	    return $a['i'];
    }

 
    public function ImageError($msg)
    {	
	    //Fatal error
		$_SESSION[ERROR_MSG] = "Image has been not uploaded successfully. Since $msg. Please try other image.";
		//$file = strrev((substr(strrev($msg),0,strpos(strrev($msg),'/'))));
		CommonFunction::page_redirect('Accountmanager/customer');
    }
}

?>