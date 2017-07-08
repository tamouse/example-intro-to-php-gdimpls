<?php
class Route
{
    /**
     * Route constructor.
     * @param string $method
     * @param string $path
     * @param string $action
     */
    function __construct($method='', $path='', $action='')
    {
        $this->method     = $method;
        $this->path       = $path;
        $this->path_parts = explode('/', $path);
        $this->action     = $action;
    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    /**
     * @return string
     */
    function component_name()
    {
        return $this->action . '.inc.php';
    }

    /**
     * @param string $path
     * @return array
     */
    function translate_path($path='')
    {
        $path_parts = explode('/', $path);
        $translation = [];
        foreach ($this->path_parts as $index => $part) {
            $matches = [];
            if (preg_match('/^:([a-z]+)$/', $part, $matches)) {
                $translation[$matches[1]] = $path_parts[$index];
            };
        };
        return $translation;
    }

    /**
     * @param string $method
     * @param string $path
     * @return bool
     */
    function match_route($method='', $path='') {
        if (!$path || !$method) return false;
        if ($this->method != $method) return false;
        $path_parts = explode('/', $path);
        if (count($this->path_parts) != count($path_parts)) return false;
        foreach ($this->path_parts as $index => $part) {
            if (preg_match('/^:([a-z]+)$/', $part)) {
                // skip
            } elseif ($part == $path_parts[$index]) {
                // skip
            } else {
                return false;
            }
        }
        return true;
    }
}
