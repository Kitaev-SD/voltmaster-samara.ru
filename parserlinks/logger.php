<?php
class Logger
{
    public $f; // открытый файл
    public $name; // имя журнала
    public $lines = []; // накапливаемые строки

    public function __construct($name, $fname)
    {
        $this->name = $name;
        try {
            $this->f = @fopen($fname, "ab+");
            if(!$this->f) {
                throw new \Error('File not create!');
            }
        } catch (\Error $e) {
            echo "<pre>".($e)."</pre>";
        }

    }

    public function __destruct()
    {
        if($this->f) {
            fwrite($this->f, implode("", $this->lines));
            fclose($this->f);
        }
    }

    public function log($str)
    {
        $prefix = "[".date("Y-m-d_h:i:s ")."{$this->name}] ";
        $str = preg_replace('/^/m', $prefix, rtrim($str));
        $this->lines[] = $str."\n";
    }
	
	public function logRedirect($str)
	{
		$this->lines[] = $str."\n";
	}
}
?>