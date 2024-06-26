<?php
/**
 * cloneRow()
 *
 * Copyright (c) 2013 Platonov Pavel (http://www.leng.ru)
 *
 * Extension for PHPWord_Template
 * New public method cloneRow() for clone rows in tables
 *
 *
 * @category   PHPWord Extension
 * @copyright  Copyright (c) 2013 Platonov Pavel (http://www.leng.ru)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    Beta 0.2, 25.12.2013
 *
 *
 * modified method setValue()
 * new pattern for replace: {MyPattern}
 * fixed problem with tags inside pattern: "{<tags...>My<tags...>Pattern<tags...>}"
 *
 * Copyright (c) 2013 Platonov Pavel (http://www.leng.ru)
 *
 *
 * PHPWord
 *
 * Copyright (c) 2011 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 010 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    Beta 0.6.3, 08.07.2011
 */


/**
 * PHPWord_DocumentProperties
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2009 - 2011 PHPWord (http://www.codeplex.com/PHPWord)
 */
class PHPWord_Template {

	/**
	* ZipArchive
	*
	* @var ZipArchive
	*/
	private $_objZip;

	/**
	* Temporary Filename
	*
	* @var string
	*/
	private $_tempFileName;

	/**
	* Document XML
	*
	* @var string
	*/
	private $_documentXML;


	/**
	* Create a new Template Object
	*
	* @param string $strFilename
	*/
	public function __construct($strFilename) {
		$path = dirname($strFilename);
		$this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.docx';

		copy($strFilename, $this->_tempFileName); // Copy the source File to the temp File

		$this->_objZip = new ZipArchive();
		$this->_objZip->open($this->_tempFileName);

		$this->_documentXML = $this->_objZip->getFromName('word/document.xml');
	}

	/**
	* Set a Template value
	*
	* @param mixed $search
	* @param mixed $replace
	*/
	public function setValue($search, $replace) {
		if(!is_array($replace)) {
            $replace = utf8_encode($replace);
			$replace = str_replace('&','&amp;',$replace);
        }

        $this->_documentXML = str_replace($search, $replace, $this->_documentXML);
		
		if(substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${'.$search.'}';
        }
        preg_match_all('/\$[^\$]+?}/', $this->_documentXML, $matches);
		
		for ($i=0;$i<count($matches[0]);$i++){
			$matches_new[$i] = preg_replace('/(<[^<]+?>)/','', $matches[0][$i]);
			$this->_documentXML = str_replace($matches[0][$i], $matches_new[$i], $this->_documentXML);//str_replace('&','&amp;',$this->_documentXML));
		}
		
		$this->_documentXML = str_replace($search, $replace, $this->_documentXML);
		$this->_documentXML = str_replace('${', '', $this->_documentXML);
		$this->_documentXML = str_replace('}', '', $this->_documentXML);
		$this->_documentXML = str_replace('\n', '<w:br/>', $this->_documentXML);
    }
	
	public function replaceStrToImg( $strKey, $arrImgPath, $panjang= "108", $tinggi= "108"){
        $strKey = '${'.$strKey.'}';
        if( !is_array($arrImgPath) )
            $arrImgPath = array($arrImgPath);
        
        $rels = $this->_objZip->getFromName('word/_rels/document.xml.rels'); 
        $types = $this->_objZip->getFromName('[Content_Types].xml'); 
        
        $count =  substr_count($rels, 'Relationship') - 1;
        $relationTmpl = '<Relationship Id="RID" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/IMG"/>';
        $imgTmpl = '<w:pict><v:shape type="#_x0000_t75" style="width:'.$panjang.'px;height:'.$tinggi.'px"><v:imagedata r:id="RID" o:title=""/></v:shape></w:pict>';
        $typeTmpl = ' <Override PartName="/word/media/IMG" ContentType="image/EXT"/>';
        $toAdd = $toAddImg = $toAddType = '';
        $aSearch = array('RID', 'IMG');
        $aSearchType = array('IMG', 'EXT');
        
        foreach($arrImgPath as $index => $img ){
            $imgExt = array_pop( explode('.', $img) );
            if( in_array($imgExt, array('jpg', 'JPG') ) )
                $imgExt = 'jpeg';
            $imgName = 'img' . ( time() + $index ) . '.' . $imgExt;
            $rid = 'rId' . ($count + $index);
            
            $this->_objZip->addFile($img, 'word/media/' . $imgName);
            
            $toAddImg .= str_replace('RID', $rid, $imgTmpl) ;
            
            $aReplace = array($imgName, $imgExt);
            $toAddType .= str_replace($aSearchType, $aReplace, $typeTmpl) ;
            
            $aReplace = array($rid, $imgName);
            $toAdd .= str_replace($aSearch, $aReplace, $relationTmpl);
        }
        
        $this->_documentXML = str_replace( '<w:t>' . $strKey . '</w:t>', $toAddImg, $this->_documentXML);
        $types = str_replace('</Types>', $toAddType, $types) . '</Types>';
        $rels = str_replace('</Relationships>', $toAdd, $rels) . '</Relationships>';
       
        $this->_objZip->addFromString('word/_rels/document.xml.rels', $rels);
        $this->_objZip->addFromString('[Content_Types].xml', $types);
        $this->_objZip->addFromString('word/document.xml', $this->_documentXML);
    }
	
	public function replaceStrArrayToImg( $strKey, $arrImgPath, $panjang= "108", $tinggi= "108"){
        if( !is_array($arrImgPath) )
            $arrImgPath = array($arrImgPath);
        
        $rels = $this->_objZip->getFromName('word/_rels/document.xml.rels'); 
        $types = $this->_objZip->getFromName('[Content_Types].xml'); 
        
        $count =  substr_count($rels, 'Relationship') - 1;
        $relationTmpl = '<Relationship Id="RID" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/IMG"/>';
        $imgTmpl = '<w:pict><v:shape type="#_x0000_t75" style="width:'.$panjang.'px;height:'.$tinggi.'px"><v:imagedata r:id="RID" o:title=""/></v:shape></w:pict>';
        $typeTmpl = ' <Override PartName="/word/media/IMG" ContentType="image/EXT"/>';
        $toAdd = $toAddImg = $toAddType = '';
        $aSearch = array('RID', 'IMG');
        $aSearchType = array('IMG', 'EXT');
        
		$index_row=0;
        foreach($arrImgPath as $index => $img ){
            $imgExt = array_pop( explode('.', $img) );
            if( in_array($imgExt, array('jpg', 'JPG') ) )
                $imgExt = 'jpeg';
            $imgName = 'img' . ( time() + $index ) . '.' . $imgExt;
            $rid = 'rId' . ($count + $index);
            
            $this->_objZip->addFile($img, 'word/media/' . $imgName);
            
			$toAddImg[$index_row]= str_replace('RID', $rid, $imgTmpl);
            
            $aReplace = array($imgName, $imgExt);
            $toAddType .= str_replace($aSearchType, $aReplace, $typeTmpl) ;
            
            $aReplace = array($rid, $imgName);
            $toAdd .= str_replace($aSearch, $aReplace, $relationTmpl);
			$index_row++;
        }
        
		for($x=0; $x<count($strKey); $x++)
		{
			$this->_documentXML= str_replace("{".$strKey[$x]."}", $toAddImg[$x], $this->_documentXML);
		}
		
        $types = str_replace('</Types>', $toAddType, $types) . '</Types>';
        $rels = str_replace('</Relationships>', $toAdd, $rels) . '</Relationships>';
       
        $this->_objZip->addFromString('word/_rels/document.xml.rels', $rels);
        $this->_objZip->addFromString('[Content_Types].xml', $types);
        $this->_objZip->addFromString('word/document.xml', $this->_documentXML);
    }
	
	public function replaceStrImg( $replace){
		$this->_documentXML= str_replace("{".$replace."}", "", $this->_documentXML);
	}
	
	public function replaceStrArrayPanjangTinggiToImg( $strKey, $arrImgPath, $panjang, $tinggi){
        if( !is_array($arrImgPath) )
            $arrImgPath = array($arrImgPath);
        
        $rels = $this->_objZip->getFromName('word/_rels/document.xml.rels'); 
        $types = $this->_objZip->getFromName('[Content_Types].xml'); 
        
        $count =  substr_count($rels, 'Relationship') - 1;
        $relationTmpl = '<Relationship Id="RID" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/IMG"/>';
        //$imgTmpl = '<w:pict><v:shape type="#_x0000_t75" style="width:'.$panjang.'px;height:'.$tinggi.'px"><v:imagedata r:id="RID" o:title=""/></v:shape></w:pict>';
        $typeTmpl = ' <Override PartName="/word/media/IMG" ContentType="image/EXT"/>';
        $toAdd = $toAddImg = $toAddType = '';
        $aSearch = array('RID', 'IMG');
        $aSearchType = array('IMG', 'EXT');
        
		$index_row=0;
        foreach($arrImgPath as $index => $img ){
            $imgExt = array_pop( explode('.', $img) );
            if( in_array($imgExt, array('jpg', 'JPG') ) )
                $imgExt = 'jpeg';
            $imgName = 'img' . ( time() + $index ) . '.' . $imgExt;
            $rid = 'rId' . ($count + $index);
            
            $this->_objZip->addFile($img, 'word/media/' . $imgName);
            
			$imgTmpl = '<w:pict><v:shape type="#_x0000_t75" style="width:'.$panjang[$index_row].'px;height:'.$tinggi[$index_row].'px"><v:imagedata r:id="RID" o:title=""/></v:shape></w:pict>';
			$toAddImg[$index_row]= str_replace('RID', $rid, $imgTmpl);
            
            $aReplace = array($imgName, $imgExt);
            $toAddType .= str_replace($aSearchType, $aReplace, $typeTmpl) ;
            
            $aReplace = array($rid, $imgName);
            $toAdd .= str_replace($aSearch, $aReplace, $relationTmpl);
			$index_row++;
        }
        
		for($x=0; $x<count($strKey); $x++)
		{
			$this->_documentXML= str_replace("{".$strKey[$x]."}", $toAddImg[$x], $this->_documentXML);
		}
		
        $types = str_replace('</Types>', $toAddType, $types) . '</Types>';
        $rels = str_replace('</Relationships>', $toAdd, $rels) . '</Relationships>';
       
        $this->_objZip->addFromString('word/_rels/document.xml.rels', $rels);
        $this->_objZip->addFromString('[Content_Types].xml', $types);
        $this->_objZip->addFromString('word/document.xml', $this->_documentXML);
    }
	
	/**
	* Save Template
	*
	* @param string $strFilename
	*/
	public function save($strFilename) {
		if(file_exists($strFilename)) {
			unlink($strFilename);
		}

		$this->_objZip->addFromString('word/document.xml', $this->_documentXML);

		// Close zip file
		if($this->_objZip->close() === false) {
			throw new Exception('Could not close zip file.');
		}

		rename($this->_tempFileName, $strFilename);
	}

	/**
	* Clone Rows in tables
	*
	* @param string $search
	* @param array $data
	*/
	public function setValueClone($search, $replace, $limit=-1) {
		if(substr($search, 0, 1) !== '{' && substr($search, -1) !== '}') {
			$search = '{'.$search.'}';
		}
		preg_match_all('/\{[^}]+\}/', $this->_documentXML, $matches);
		foreach ($matches[0] as $k => $match) {
			$no_tag = strip_tags($match);
			if ($no_tag == $search) {
				$match = '{'.$match.'}';
				$this->_documentXML = preg_replace($match, $replace, $this->_documentXML, $limit);	
				if ($limit == 1) {
					break;
				}			
			}
		}
		$this->_documentXML = str_replace('\n', '<w:br/>', $this->_documentXML);
	}
	
	public function cloneRow($search, $data=array()) {		
		// remove ooxml-tags inside pattern				
		foreach ($data as $nn => $fieldset) {
			foreach ($fieldset as $field => $val) {
				$key = '{'.$search.'.'.$field.'}';
				$this->setValueClone($key, $key, 1);
			}
		}
		// how many clons we need
		$numberOfClones = 0;
		if (is_array($data)) {
			foreach ($data as $colName => $dataArr) {
				if (is_array($dataArr)) {
					$c = count($dataArr);
					if ($c > $numberOfClones)
						$numberOfClones = $c;
				}
			}
		}
		if ($numberOfClones > 0) {
			// read document as XML
			$xml = DOMDocument::loadXML($this->_documentXML, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);

			// search for tables
			$tables = $xml->getElementsByTagName('tbl');
			foreach ($tables as $table) {
				$text = $table->textContent;
				// search for pattern. Like {TBL1.
				if (mb_strpos($text, '{'.$search.'.') !== false) {
					// search row for clone
					$patterns = array();
					$rows = $table->getElementsByTagName('tr');
					$isUpdate = false;
					$isFind = false;
					foreach ($rows as $row) {
						$text = $row->textContent;
						$TextWithTags = $xml->saveXML($row);
						if (
							mb_strpos($text, '{'.$search.'.') !== false // Pattern found in this row
							OR
							(mb_strpos($TextWithTags, '<w:vMerge/>') !== false AND $isFind) // This row is merged with upper row (Upper row have pattern)
						)
						{
							// This row need to clone
							$patterns[] = $row->cloneNode(true);
							$isFind = true;
						} else {
							// This row don't have any patterns. It's table header or footer
							if (!$isUpdate and $isFind) {
								// This is table footer
								// Insert new rows before footer								
								$this->InsertNewRows($table, $patterns, $row, $numberOfClones);
								$isUpdate = true;
							}
						}
					}
					// if table without footer					
					if (!$isUpdate and $isFind) {
						$this->InsertNewRows($table, $patterns, $row, $numberOfClones);
					}
				}
			}
			// save document
			$res_string = $xml->saveXML();
			$this->_documentXML = $res_string;
	
			// parsing data
			foreach ($data as $colName => $dataArr) {
				$pattern = '{' . $search . '.' . $colName . '}';
				foreach ($dataArr as $value) {
					$this->setValueClone($pattern, $value, 1);
				}
			}
		}
	}
	
	/**
	* Insert new rows in table
	*
	* @param object &$table
	* @param object $patterns
	* @param object $row
	* @param int $numberOfClones
	*/
	protected function InsertNewRows(&$table, $patterns, $row, $numberOfClones)	{
		for ($i = 1; $i < $numberOfClones; $i++) {
			foreach ($patterns as $pattern) {
				$new_row = $pattern->cloneNode(true);
				$table->insertBefore($new_row, $row);
			}
		}
	}
}
?>