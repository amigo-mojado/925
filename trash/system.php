<?php

class system
{

    protected $filename;
    protected $fileContentAsArray = array();
    protected $line;
    protected $vars = array();

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function setVars($vars)
    {
        $this->vars = $vars;
    }

    public function reader()
    {
        $this->getArrayFromFile();
        echo "<pre>\n";
        $lineNumber = 1;
        $pLine = "";
        foreach ($this->fileContentAsArray as $i => $line) {
            if (trim($line) == '//hide next line:' || trim($pLine) == '//hide next line:') {
                $pLine = $line;
                continue;
            } else {
                $pLine = "";
            }

            echo $lineNumber . ": ";
            $lineNumber++;

            $line = htmlspecialchars($line) . "\n";
            $line = str_replace('&lt;/strong&gt;', '<strong>', $line);
            $line = str_replace('&lt;/strong&gt;', '</strong>', $line);
            foreach ($this->vars as $replace) {
                $line = str_replace("$" . $replace, "<strong><a href='#" . $replace . "'>$" . $replace . "</a></strong>", $line);
            }
            echo $line;
        }
        echo "</pre>\n";
    }

    protected function getArrayFromFile()
    {
        // Open the file
        $fp = @fopen($this->filename, 'r');

        // Add each line to an array
        if ($fp) {
            $this->fileContentAsArray = explode("\n", fread($fp, filesize($this->filename)));
        }
    }

    public function dumpVarsInThisFile($vars)
    {
        $html = '';
        foreach ($this->vars as $key) {
            //output buffering
            ob_start();
            var_dump($vars[$key]);
            $dump = ob_get_clean();

            $html .= "<a name=\"" . $key . "\"><strong>$" . $key . "</strong></a><br>\n";
            $html .= "<pre>\n";
            $html .= $dump;
            $html .= "</pre>\n";
        }
        return $html;
    }
}