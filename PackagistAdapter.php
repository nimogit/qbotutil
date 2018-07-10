<?php
namespace TelegramPagerfanta\Adapter;
use Pagerfanta\Adapter\AdapterInterface;
use Base32\Base32;
class PackagistAdapter implements AdapterInterface
{
    private $results;

    /**
     * Constructor.
     *
     * @param array $array The array.
     */
    public function __construct(array $results)
    {
        $this->results = $results;
    }

    public function getNbResults()
    {
        return $this->results['total'];
    }

    public function getSlice($offset, $length)
    {
        return array_slice($this->results['results'], $offset, $length);
    }
    
    public function getPageContent($pagerfanta, $query) {
        $offset = ($pagerfanta->getCurrentPage() - 1) * $pagerfanta->getMaxPerPage();
        if($offset >= count($this->results['results'])) {
            $offset = $offset % count($this->results['results']);
        }
        $length = $pagerfanta->getMaxPerPage();
        $results = array_slice($this->results['results'], $offset, $length);
        $text = "<b>Showing results for '$query'</b>";
        foreach($results as $result) {
            if(strlen(utf8_decode($result['description'])) > 66) {
                $result['description'] = substr(utf8_decode($result['description']), 0, 65) . '...';
            }
            //$encoded = rtrim(Base32::encode(gzdeflate($result['name'], 9)), '=');
            $encoded = str_replace(array('/', '-'), array('X', '_'), strtolower(utf8_decode($result['name'])));
            $text.=
                "\n\n".
                "<b>{$result['name']}</b>\n".
                (utf8_decode($result['description']) ? utf8_decode($result['description']) . "\n" : '').
                "<i>View: </i>/v_".$encoded;
        }
        return $text;
    }
    
	    public function getESPageContent($pagerfanta, $query) {
        $offset = ($pagerfanta->getCurrentPage() - 1) * $pagerfanta->getMaxPerPage();
        if($offset >= count($this->results['results'])) {
            $offset = $offset % count($this->results['results']);
        }
        $length = $pagerfanta->getMaxPerPage();
        $results = array_slice($this->results['results'], $offset, $length);
        $text = "<b>عرض النتائج للبحث عن: '$query'</b>";
        foreach($results as $result) {
            if(strlen((($result['description']))) > 800) {
                $descr = (substr((($result['description'])), 0, 800) . '...');
				$result['description'] = $descr;
				//$result['description'] = mb_convert_encoding(substr((($result['description'])), 0, 1499) . '...',"UTF-8","auto");
            }
			$result['description'] = utf8_decode($result['description']);
            //$encoded = rtrim(Base32::encode(gzdeflate($result['name'], 9)), '=');
            $encoded =  ($result['url']);
			//$encoded = substr($encoded, 0, strpos($encoded, "item/"));
			$encoded = strstr($encoded, 'item/');
			$encoded = strstr($encoded, '/');
						$encoded = substr($encoded, 0, strpos($encoded, "-"));
			$encoded = str_replace(array('/', '_'), array('', '/'), $encoded);

			$namen = utf8_decode($result['name']);
			
			//$descr = ($result['description']);
			$textvar = iconv("utf8","utf8",$result['description']);
            $text.=
                "\n\n".
                "<b>&#9830;&#32;</b><b>{$namen}</b>\n\n".
                ($textvar ? "<b>&#8968;</b>&#32;&#32;<i>".$textvar . "</i>&#32;&#32;<b>&#8971;</b>\n\n" : '').
               "<code>إضغط على هذا الرقم لقرائة المقال</code><b>&#32;&#8592;</b> /v_".$encoded."\n&#9562;";
        }
        return $text;
    }
	
	 public function getESCPageContent($pagerfanta, $query) {
        $offset = ($pagerfanta->getCurrentPage() - 1) * $pagerfanta->getMaxPerPage();
        if($offset >= count($this->results['results'])) {
            $offset = $offset % count($this->results['results']);
        }
        $length = $pagerfanta->getMaxPerPage();
        $results = array_slice($this->results['results'], $offset, $length);
        $text = "<b>عرض النتائج لقسم: '$query'</b>";
        foreach($results as $result) {
            if(strlen((($result['description']))) > 800) {
                $descr = (substr((($result['description'])), 0, 800) . '...');
				$result['description'] = $descr;
				//$result['description'] = mb_convert_encoding(substr((($result['description'])), 0, 1499) . '...',"UTF-8","auto");
            }
			$result['description'] = utf8_decode($result['description']);
            //$encoded = rtrim(Base32::encode(gzdeflate($result['name'], 9)), '=');
            $encoded =  ($result['url']);
			//$encoded = substr($encoded, 0, strpos($encoded, "item/"));
			$encoded = strstr($encoded, 'item/');
			$encoded = strstr($encoded, '/');
						$encoded = substr($encoded, 0, strpos($encoded, "-"));
			$encoded = str_replace(array('/', '_'), array('', '/'), $encoded);

			$namen = utf8_decode($result['name']);
			
			//$descr = ($result['description']);
			$textvar = iconv("utf8","utf8",$result['description']);
            $text.=
                "\n\n".
                "<b>&#9830;&#32;</b><b>{$namen}</b>\n\n".
                ($textvar ? "<b>&#8968;</b>&#32;&#32;<i>".$textvar . "</i>&#32;&#32;<b>&#8971;</b>\n\n" : '').
               "<code>إضغط على هذا الرقم لقرائة المقال</code><b>&#32;&#8592;</b> /v_".$encoded."\n&#9562;";
        }
        return $text;
    }
	
	
	
    public static function showPackage($result)
    {   mb_internal_encoding("UTF-8");
        if(strlen(utf8_decode($result['description'])) > 66) {
            $result['description'] = utf8_decode(mb_substr($result['description'], 0, 65) . '...');
        }

		            $encoded =  ($result['url']);
			//$encoded = substr($encoded, 0, strpos($encoded, "item/"));
			$encoded = strstr($encoded, 'item/');
			$encoded = strstr($encoded, '/');
						$encoded = substr($encoded, 0, strpos($encoded, "-"));
			$encoded = str_replace(array('/', '_'), array('', '/'), $encoded);
			$url = urlencode("https://kassiounpaper.com/affinityapp/afcontent/item/".$encoded);
		
		$namen = utf8_decode($result['name']);
		$textvar = iconv("utf8","utf8",$result['description']);
        $text =
            "https://t.me/iv?url=". $url. "&rhash=c9cef572355f5b";
        return $text;
    }
}