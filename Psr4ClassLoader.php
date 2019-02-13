<?php

namespace Classloader;

if (!\defined('NAMESPACE_SEPARATOR')) {
    \define('NAMESPACE_SEPARATOR', '\\');
}

/**
 * A PSR-4 compatible class loader.
 * @see http://www.php-fig.org/psr/psr-4/
 * @see https://github.com/symfony/class-loader
 *
 * @example
 * require_once __DIR__ . '/' . 'Psr4ClassLoader.php';
 * (new \ClassLoader\Psr4ClassLoader())
 *     ->addNamespace('NS1', ['dir1', 'dir2', 'dir3', ...])
 *     ->addNamespace('NS2', ['dir4', 'dir5', 'dir6', ...])
 *     ->register();
 */
class Psr4ClassLoader
{

    private $prefixes = [];

    /**
     * Link namespace with basedirs
     *
     * @param $prefix
     * @param $baseDirs
     * @return \Classloader\Psr4ClassLoader
     */
    public function addNamespace($prefix, $baseDirs)
    {
        $prefix = $this->_normalizeNamespace($prefix);
        foreach ((array)$baseDirs as $baseDir) {
            $this->prefixes[] = [$prefix, $this->_normalizeDirname($baseDir)];
        }
        return $this;
    }

    /**
     * Add prefix and basedirs
     * @deprecated For compatibility with old code
     *
     * @param string       $prefix
     * @param array|string $baseDirs
     * @return \Classloader\Psr4ClassLoader
     */
    public function addPrefix($prefix, $baseDirs)
    {
        return $this->addNamespace($prefix, $baseDirs);
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
                $file = $prefix_dir[1] . \str_replace(NAMESPACE_SEPARATOR, \DIRECTORY_SEPARATOR, $className) . '.php';
                if (\file_exists($file)) {
                    require($file);
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
     * @return \Classloader\Psr4ClassLoader
     */
    public function register($prepend = false)
    {
        spl_autoload_register([$this, 'loadClass'], true, $prepend);
        return $this;
    }

    /**
     * Removes this instance from the registered autoloaders.
     */
    public function unregister()
    {
        spl_autoload_unregister([$this, 'loadClass']);
        return $this;
    }

    /**
     * @param $prefix
     * @return string
     */
    private function _normalizeNamespace($prefix)
    {
        return \trim($prefix, NAMESPACE_SEPARATOR) . NAMESPACE_SEPARATOR;
    }

    /**
     * @param $baseDir
     * @return string
     */
    private function _normalizeDirname($baseDir)
    {
        return \rtrim($baseDir, \DIRECTORY_SEPARATOR) . \DIRECTORY_SEPARATOR;
    }
}
