<?php
class MarkdownEditor extends TextareaField {
    protected $rows=30;

    /**
     * Default site-wide theme for the ace editor.
     * @var string - See {@link http://ace.ajax.org/#nav=howto}
    **/
    static protected $default_theme = "ace/theme/twilight";

    /**
     * MarkdownEditor instance theme
     * @var string - See {@link http://ace.ajax.org/#nav=howto}
    **/
    protected $theme;

    /**
     * Set the default site-wide theme of the Ace editor.
     * @param $theme string - path to the theme. See {@link http://ace.ajax.org/#nav=howto}
    **/
    public static function set_default_theme($theme)
    {
        self::$default_theme = (string) $theme;
    }

    /**
     * Returns the ace editor default theme.
     * @return string
    **/
    public static function get_default_theme()
    {
        return (string) self::$default_theme;
    }

    /**
     * Set a theme for the current instance.
     * @param $theme string
    **/
    public function setTheme($theme)
    {
        $this->theme = (string) $theme;
    }

    /**
     * Return the current instance theme.
     * @return string
    **/
    public function getTheme()
    {
        if(!$this->theme)
            return self::get_default_theme();
        return (string) $this->theme;
    }

    /**
     * Returns the field holder used by templates
     * @return {string} HTML to be used
     */
    public function FieldHolder($properties=array()) {
        $this->extraClasses['stacked'] = 'stacked';

        Requirements::css(MARKDOWN_MODULE_BASE.'/css/MarkdownEditor.css');

        Requirements::javascript(MARKDOWN_MODULE_BASE.'/javascript/external/ace/ace.js');
        Requirements::javascript(MARKDOWN_MODULE_BASE.'/javascript/external/ace/mode-markdown.js');

        $vars = array(
            "Theme" => "'" . $this->getTheme() . "'"
        );
        Requirements::javascriptTemplate(MARKDOWN_MODULE_BASE.'/javascript/MarkdownEditor.js', $vars);

        return parent::FieldHolder($properties);
    }

    /**
     * Generates the attributes to be used on the field
     * @return {array} Array of attributes to be used on the form field
     */
    public function getAttributes() {
        return array_merge(
                parent::getAttributes(),
                array(
                    'style'=>'width: 97%; max-width: 100%; height: '.($this->rows * 16).'px; resize: none;', // prevents horizontal scrollbars
                )
        );
    }

    public function performReadonlyTransformation()
    {
        $field = parent::performReadonlyTransformation();
        $field->dontEscape = true;
        $field->setValue(nl2br(Convert::raw2xml($field->value)));
        return $field;
    }
}
?>