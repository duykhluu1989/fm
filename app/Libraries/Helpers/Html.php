<?php

namespace App\Libraries\Helpers;

class Html
{
    public static function a($innerHtml, $attributes)
    {
        return self::renderHtmlOpenCloseTag('a', $innerHtml, $attributes);
    }

    public static function button($innerHtml, $attributes)
    {
        return self::renderHtmlOpenCloseTag('button', $innerHtml, $attributes);
    }

    public static function span($innerHtml, $attributes)
    {
        return self::renderHtmlOpenCloseTag('span', $innerHtml, $attributes);
    }

    public static function i($innerHtml, $attributes)
    {
        return self::renderHtmlOpenCloseTag('i', $innerHtml, $attributes);
    }

    protected static function renderHtmlOpenCloseTag($htmlTag, $innerHtml, $attributes)
    {
        $html = '<' . $htmlTag;

        foreach($attributes as $name => $value)
            $html .= ' ' . $name . '="' . $value . '"';

        $html .= '>' . $innerHtml . '</' . $htmlTag . '>';

        return $html;
    }

    public static function input($data, $attributes)
    {
        $html = '<input';

        foreach($attributes as $name => $value)
            $html .= ' ' . $name . '="' . $value . '"';

        $html .= ' value="' . htmlentities($data) . '" />';

        return $html;
    }

    public static function select($data, $options, $attributes, $emptyOption = true)
    {
        $html = '<select';

        foreach($attributes as $name => $value)
            $html .= ' ' . $name . '="' . $value . '"';

        $html .= '>';

        if($emptyOption)
            $html .= '<option value=""></option>';

        foreach($options as $option => $label)
        {
            if($data !== '' && $data == $option)
                $html .= '<option value="' . $option . '" selected="selected">' . $label . '</option>';
            else
                $html .= '<option value="' . $option . '">' . $label . '</option>';
        }

        $html .= '</select>';

        return $html;
    }
}