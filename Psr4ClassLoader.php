<?php namespace Classloader;

if (!\defined('NAMESPACE_SEPARATOR')) {
    \define('NAMESPACE_SEPARATOR', '\\');
}

/**
 * A PSR-4 compatible class loader.
 * See http://www.php-fig.org/psr/psr-4/
 * See https://github.com/symfony/class-loader
 */
class Psr4ClassLoader
{

    private $prefixes = array();

    /**
     * Add prefix and basedirs
     *
     * @param string $prefix
     * @param array|string $baseDirs
     */
    public function addPrefix($prefix, $baseDirs)
    {
        $prefix = \trim($prefix, NAMESPACE_SEPARATOR).NAMESPACE_SEPARATOR;
        foreach ((array)$baseDirs as $baseDir) {
            $this->prefixes[] = array($prefix, \rtrim($baseDir, \DIRECTORY_SEPARATOR).\DIRECTORY_SEPARATOR);
        }
    }

    /**
     * Load classes file
     * @param string $class
     * @return bool
     */
    public function loadClass($class)
    {
        $class = \ltrim($class, NAMESPACE_SEPARATOR);
        foreach ($this->prefixes as $prefix_dir) {
            //prefix_dir: [0] => prefix, [1] => basedir
            if (\strpos($class, $prefix_dir[0]) === 0) {
                $className = \substr($class, \strlen($prefix_dir[0]));
                $file = $prefix_dir[1].\str_replace(NAMESPACE_SEPARATOR, \DIRECTORY_SEPARATOR, $className).'.php';
                if (\file_exists($file)) {
                    require $file;
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Registers this instance as an autoloader.
     *
     * @param bool $prepend
     */
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    /**
     * Removes this instance from the registered autoloaders.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }
}
