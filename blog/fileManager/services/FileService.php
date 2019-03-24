<?php


namespace blog\fileManager\services;


class FileService
{
    /**
     * @param string $string
     * @param array $replaceData
     * @return string
     */
    public function pathReplacer(string $string, array $replaceData = []): string
    {
        if (preg_match_all('~{[^\{\}]+}~', $string, $variables)) {
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

                $string = str_replace($variable, $replace ?? '', $string);
            }
        }

        return $this->normalizePath($string);
    }

    /**
     * @param string $path
     * @return string
     */
    public function normalizePath(string $path): string
    {
        return str_replace(['//', '\\'], ['/', '/'], rtrim($path, '/'));
    }

    /**
     * @param string $basePath
     * @param string $fullPath
     * @return mixed
     */
    public function pathToRelative(string $basePath, string $fullPath)
    {
        return str_replace($basePath, '', $fullPath);
    }
}