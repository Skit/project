<?php


namespace blog\fileManager\entities;

use Yii;

class Path {

    private
        /**
         * Path after replace
         * @var string
         */
        $_replaced,
        /**
         * Path after changes
         * @var string
         */
        $_result,
        /**
         * Raw path from constructor
         * @var string
         */
        $_path;

    public function __construct(string $path)
    {
        $this->_result = $this->_path = $path;
    }

    public function getRaw(): string
    {
        return $this->_path;
    }

    public function getPath(): string
    {
        return $this->_result;
    }

    public function getUrl(): string
    {
        return  Yii::$app->params['baseUrl'] . "/{$this->getReplaced()}";
    }

    public function convertAlias(): self
    {
        $this->_result = Yii::getAlias($this->_result);
        return $this;
    }

    public function getReplaced()
    {
        return $this->_replaced;
    }

    /**
     * @param string $path
     * @param string $place before path if ^ else or $ string will be concatenates after path
     * @return Path
     */
    public function concat(string $path, string $place = '^'): self
    {
        $this->_result = $place === '^' ? "{$path}/{$this->_result}" : "{$this->_result}/{$path}";
        return $this;
    }

    /**
     * @param array $replaceData
     * @return self
     */
    public function replacer(array $replaceData = []): self
    {
        if (preg_match_all('~{[^\{\}]+}~', $this->_path, $variables)) {
            foreach ($variables[0] as $variable) {

                if (strpos($variable, ':')) {

                    if (preg_match('~\{\:(?<function>[^\[\]]+)(?:\[(?<params>.*)\])?}~', $variable, $matches)) {
                        switch ($matches['function']) {
                            case 'date':
                                if (! empty($matches['params'])) {
                                    $replace = date(str_replace('\\', '/', $matches['params']));
                                }
                                break;
                            case 'salt':
                                $length = empty($matches['params']) ? 6 : $matches['params'];
                                $replace = substr(md5(time()), 0, $length);
                                break;
                            case 'time':
                                $replace = time();
                                break;
                        }
                    }
                } else {
                    $replace = $replaceData[$variable] ?? '';
                }

                $this->_replaced = $this->_result = str_replace($variable, $replace ?? '', $this->_result);
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function normalizePath(): self
    {
        $this->_result = str_replace(['//', '\\'], ['/', '/'], rtrim($this->_result, '/'));
        return $this;
    }

    public function getDir(): string
    {
        return rtrim(str_replace($this->getFileName(), '', $this->_result), '/');
    }

    public function getFileName(): string
    {
        return basename($this->_result);
    }
}