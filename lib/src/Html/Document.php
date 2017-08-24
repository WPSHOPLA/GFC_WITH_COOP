<?php
namespace GoFetchCode\Html;

/**
 * Represents an Html document.
 * 
 * An attempt to provide some structure and convenience to rendering an HTML document.
 */
class Document
{
    /**
     * An array of script includes.
     *
     * @var  array
     */
    public $scripts = [];
    
    /**
     * An array of inline scripts.
     *
     * @var  array
     */
    public $script = [];

    /**
     * An array of css includes.
     *
     * @var  array
     */
    public $styles = [];
    
    /**
     * An array of inline styles.
     *
     * @var  array
     */
    public $style = [];

    /**
     * Add a script include.
     *
     * @param  string  $url  A url.
     */
    public function addScript($url)
    {
        $this->scripts[$url] = $url;
    }

    /**
     * Add an inline script.
     *
     * @param  string  $content  An inline script.
     */
    public function addScriptDeclaration($content)
    {
        $this->script[] = $content;
    }
    
    /**
     * Add a CSS include.
     *
     * @param  string  $url  A url.
     */
    public function addStyle($url)
    {
        $this->styles[$url] = $url;
    }

    /**
     * Add an inline CSS style.
     *
     * @param  string  $content  An inline CSS style.
     */
    public function addStyleDeclaration($content)
    {
        $this->style[] = $content;
    }
    
    public function renderCss()
    {
        $styles = [];

        foreach ($this->styles as $style) {
            $styles[] = '<link href="'.$style.'" media="all" rel="stylesheet" type="text/css"/>';
        }

        $buffer = implode("\n", $styles)."\n";

        if (count($this->style)) {
            $buffer .= '<style>'."\n";
            
            $buffer .= implode("\n", $this->style);
            
            $buffer .= "</style>\n";
        }

        return $buffer;
    }
    
    public function renderJs()
    {
        $scripts = [];
        
        foreach ($this->scripts as $script) {
            $scripts[] = '<script type="text/javascript" language="Javascript" src="'.$script.'"></script>';
        }
        
        $buffer = implode("\n", $scripts)."\n";
        
        if (count($this->script)) {
            $buffer .= '<script language="Javascript" type="text/javascript">'."\n";
            
            $buffer .= implode("\n", $this->script);
            
            $buffer .= "\n</script>\n";
        }
        
        return $buffer;
    }
}
