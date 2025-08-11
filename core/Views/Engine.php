<?php
namespace Rivulet\Views;

/**
 * Template Engine
 *
 * Handles basic template parsing with variable replacement and loops
 */
class Engine
{
    protected $content;
    protected $data;

    /**
     * Initialize template engine
     * @param string $content Template content
     * @param array $data Template variables
     */
    public function __construct($content, $data)
    {
        $this->content = $content;
        $this->data    = $data;
    }

    /**
     * Parse template content
     * Processes variables and loops in the template
     * @return string Parsed content
     */
    public function parse()
    {
        // Replace simple variables {{$var}}
        $this->content = preg_replace_callback(
            '/\{\{ ?\$(\w+) ?\}\}/',
            function ($matches) {
                $var = $matches[1];
                return $this->data[$var] ?? '';
            },
            $this->content
        );

        // Handle loops {{map $array}} ... {{end map}}
        $this->content = preg_replace_callback(
            '/\{\{map ?\$(\w+)\}\}(.*?)\{\{end map\}\}/s',
            function ($matches) {
                $arrayVar    = $matches[1];
                $loopContent = $matches[2];
                $array       = $this->data[$arrayVar] ?? [];

                if (! is_array($array)) {
                    return '';
                }

                $output = '';
                foreach ($array as $item) {
                    $itemContent = $loopContent;
                    // Replace $array.item with current item value
                    $itemContent = preg_replace_callback(
                        '/\{\{ ?\$' . $arrayVar . '\.(\w+) ?\}\}/',
                        function ($itemMatches) use ($item) {
                            $key = $itemMatches[1];
                            return $item[$key] ?? '';
                        },
                        $itemContent
                    );
                    $output .= $itemContent;
                }
                return $output;
            },
            $this->content
        );

        return $this->content;
    }
}
