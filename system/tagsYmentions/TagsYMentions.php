<?php

namespace TagsYMentions;

/**
 * TagsYMentions
 * Twitter-like Hashtag and mentions extracting package with unicode support 
 * 
 * @author Kator Bryan `16kb` James <kator95@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * github: https://github.com/16kilobyte
 * 
 */
class TagsYMentions {
    
    /**
     * The text to parse
     * 
     * @var string
     */
    protected $str;
    /**
     * The formatted text
     * 
     * @var string
     */
    protected $formattedStr = '';
    
    /** 
	* @var $hashtags[]
	* An array of string objects for storing hashtags inside it. 
	*/ 
	protected $hashtags = array();
	
    /** 
	* @var $mentions[]
	* An array of string objects for storing mentions inside it. 
	*/ 
	protected $mentions = array();
	
	/**
	 * @var boolean
	 * A flag to determine if hastags has been extracted from $str
	 */
	private $tagsExtracted = false;
	
	/**
	 * @var boolean
	 * A flag to determine if mentions has been extracted from $str
	 */
	private $mentionsExtracted = false;
	
	/**
	 * @var boolean
	 * A flag to determine if str has been formatted before
	 */
	private $strFormatted = false;
    
    /**
	 * @var string
	 * the url for tags
	 */
	private $hashtagsUrl;
    
    /**
	 * @var string
	 * the url for mentions
	 */
	private $mentionsUrl;
    
    /**
	 * @var integer|string
	 * mentions link title
	 */
	private $mentionsTitle;
    
    /**
	 * @var interger|string
	 * tags link title
	 */
	private $hashtagsTitle;
	
	/**
	 * @var integer
	 * No title
	 */
	const NO_TITLE = 0;
	/**
	 * @var integer
	 * Use the name of the tag or mention as the title
	 */
	const USE_NAME = 1;
    
    /**
     * Create a new validator instance.
     *
     * @param string $str
     * the input string to be proccess and export the hashtags from it
     * 
     * @param mixed $options
     * the options to work with as array
     * 
     * @return void
     */
    public function __construct($str, $options = ['tags' => ['url' => '/tags/', 'title' => 1], 'mentions' => ['url' => '/users/', 'title' => 1]])
    {
        $this->str = $str;
        
        $this->hashtagsUrl = isset($options['tags']['url']) ? $options['tags']['url'] : '/tags/';
        $this->mentionsUrl = isset($options['mentions']['url']) ? $options['mentions']['url'] : '/mentions/';
        
        $this->hashtagsTitle = isset($options['tags']['title']) ? isset($options['tags']['title']) : self::USE_NAME;
        $this->mentionsTitle = isset($options['mentions']['title']) ? isset($options['mentions']['title']) : self::USE_NAME;
    }
    
    /**
     * Set new string to be parsed and reset flags
     * 
     * @param string
     * The new string to work on
     * 
     * @return void
     */
    public function setString($str)
    {
    	$this->str = $str;
    	$this->hashtags = array();
    	$this->mentions = array();
    	
    	// Reset flags
    	$this->tagsExtracted = false;
    	$this->mentionsExtracted = false;
    	$this->strFormatted = false;
    }
    
    /**
    * Extract the hashtags of a given string
    * 
    * @return array Returns the array of the hashtags extracted
    */
    protected function tagsExtractor()
    {
    	if (!$this->tagsExtracted)
    	{
    		// Only extract tags if they've not been extracted before
    		
	    	/** 
	    	 *
	    	 * @var string $tagPattern
	    	 * regular expression pattern for hashtags
	    	 * works even with unicode characters!
	    	 */
	    	$tagPattern = '%(\A#(\w|(\p{L}\p{M}?)|-)+\b)|((?<=\s)#(\w|(\p{L}\p{M}?)|-)+\b)|((?<=\[)#.+?(?=\]))%u';
	    	
	
			/** 
			*
			* @var strArray[] 
			* An array of string objects that will save the words of the string argument.  
			*
			*/
			$strArray = explode(' ', $this->str);
		
			foreach ($strArray as $b)
			{
				// match the word with our hashtag pattern
				preg_match_all($tagPattern, ($b), $matches);
			   	
				/** 
				*
				* @var hashtag[] 
				* An array of string objects that will save the hashtags.
				*
				*/ 
				$hashtag	= implode(', ', $matches[0]);
		
				// add to array if hashtag is not empty
				if (!empty($hashtag) or $hashtag != '')
					array_push($this->hashtags, $hashtag);
			}
			
			// Toggle the flag to indicate tags have been extracted
			$this->tagsExtracted = true;
    	}
		
		return $this->hashtags;
    }
    
    /**
    * Extract the mentions of a given string
    * 
    * @return array Returns the array of the mentions extracted
    */
    protected function mentionsExtractor()
    {
    	if (!$this->mentionsExtracted)
    	{
    		// Only extract mentions if they've not been extracted before
    	
	    	/** 
	    	 *
	    	 * @var string $mentionPattern
	    	 * regular expression pattern for mentions.
	    	 * works with unicode
	    	 */
	    	$mentionPattern = '%(\A@(\w|(\p{L}\p{M}?)|-)+\b)|((?<=\s)@(\w|(\p{L}\p{M}?)|-)+\b)|((?<=\[)@.+?(?=\]))%u';
	    	
	    	/** 
			*
			* @var strArray[] 
			* An array of string objects that will save the words of the string argument.  
			*
			*/
			$strArray = explode(" ", $this->str);
			
			foreach ($strArray as $b)
			{
				
				// match the word with our mention pattern
				preg_match_all($mentionPattern, ($b), $matches);
				
				/** 
				*
				* @var mention[] 
				* An array of string objects that will save the mentions.
				*
				*/
				$mention	= implode(', ', $matches[0]);
					
				// add to array if mention is not empty
				if (!empty($mention) or $mention != "")
					array_push($this->mentions, $mention);
			}
			
			// Toggle the flag to indicate mentions have been extracted
			$this->mentionsExtracted = true;
			
    	}
    	
		return $this->mentions;
    }
    
    /**
     * Returns the extracted tags in an array
     * 
     * @return array Returns the array of the hashtags
     */
    public function getHashTags()
    {
		// only extract hashtags if hashtags have not been added to the hashtags member functions;
		return (isset($this->hashtags)) ? $this->tagsExtractor() : $this->hashtags;
    }
    
    /**
     * Returns the extracted mentions as an array
     * 
     * @return array Returns the array of the hashtags
     */
    public function getMentions()
    {
		// only extract hashtags if hashtags have not been added to the hashtags member functions;
		return (isset($this->hashtags)) ? $this->tagsExtractor() : $this->hashtags;
    }
    
    /**
     * Returns the extracted hashtags and mentions as an array.
     * 
     * @param bool $single
     * Using 'false' as default will return the tags and mentions as an array of two arrays; that of hashtags and mentions
     * 
     * @return string[]|array[]
     */
    public function getHashTagsYMentions($single = false)
    {
    	$hashtags = $this->tagsExtractor();
    	$mentions = $this->mentionsExtractor();
    	
    	if ($single) {
    		// Merge mentions and hashtags
    		return array_merge($mentions, $hashtags);
    	}
    	
    	return ['mentions' => $mentions, 'tags' => $hashtags];
    	
    }
    
    /**
     * Formats the hashtags and mentions and returns a full href'd text.
     * The hrefs used are those supplied to the constructor when the class
     * was initialized.
     * 
     * @param array[]	$options
     * 
     * @return string The formated text
     */
    public function taggedYMentioned($options = [])
    {
    	if (!$this->strFormatted) {
    		// Only format string if it has not been formated
    		
    		// Run extractors
    		$this->tagsExtractor();
    		$this->mentionsExtractor();
    		
	    	// Use the url param if passed
	    	if (isset($options['tags']['url']))
	    		$this->hashtagsUrl = $options['tags']['url'];
	    		
	    	if (isset($options['mentions']['url']))
	    		$this->mentionsUrl = $options['mentions']['url'];
	    		
	    	/**
	    	 * @var string
	    	 * The string we've been working with
	    	 */
	    	$str = $this->str;
	    	
    		// now we have found all hashtags in the string
			// so we have to replace them and built a new string :
			foreach ($this->hashtags as $hashtag)
			{
				/** 
			    *
			    * @var string $hashtagTitle
			    * container for the exported hashtags without # sign (to insert to db or etc) 
			    */ 
				$hashtagTitle = ltrim($hashtag, "#");
				
				/**
				 * @var string
				 * A href
				 */
				$href = '<a href="' . $this->hashtagsUrl . $hashtagTitle . '"';
				$href .= ($this->hashtagsTitle == self::NO_TITLE) ? '' : ' title="';
				$href .= ($this->hashtagsTitle == self::USE_NAME) ? $hashtagTitle : $this->hashtagsTitle;
				$href .= '">' . $hashtag . '</a>';
				//create links for hashtags
				$str = str_replace($hashtag, $href, $str);
			}
			
			// now we have found all hashtags in the string
			// so we have to replace them and built a new string :
			foreach ($this->mentions as $mention)
			{
				/** 
			    *
			    * @var string $mentionTitle
			    * container for the exported mentions without # sign (to insert to db or etc) 
			    */ 
				$mentionTitle = ltrim($mention, "@");
				
				/**
				 * @var string
				 * A href
				 */
				$href = '<a href="' . $this->mentionsUrl . '"';
				$href .= ($this->mentionsTitle == self::NO_TITLE) ? '' : ' title="';
				$href .= ($this->mentionsTitle == self::USE_NAME) ? $mentionTitle : $this->mentionsTitle;
				//create links for mentions
				$str = str_replace($hashtag, $href, $str);
			}
	    	
	    	$this->formattedStr = $str;
	    	
	    	// Toggle flag to indicate string has been formated
	    	$this->strFormatted = true;
    	}
    	
    	return $this->formattedStr;
    }
	
}
