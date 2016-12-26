<?php

namespace Livro\Core;

/**
 * Carrega a classe do framework
 */
class ClassLoader {

    protected $prefixes = array();

    public function register() {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Recebe o nome de um Namespace que deve ser carregado
     * 
     * @param string $prefix
     * @param string $base_dir
     * @param type $prepend
     */
    public function addNamespace($prefix, $base_dir, $prepend = false) {
        // normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // Normalize o diretório base com um separador à direita
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // Inicializar a matriz de prefixo de namespace
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // Manter o diretório base para o prefixo de namespace
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    public function loadClass($class) {
        // O prefixo de namespace atual
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {

            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // the rest is the relative class name
            $relative_class = substr($class, $pos + 1);

            // try to load a mapped file for the prefix and relative class
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // remove the trailing namespace separator for the next iteration
            // of strrpos()
            $prefix = rtrim($prefix, '\\');
        }

        // never found a mapped file
        return false;
    }

    protected function loadMappedFile($prefix, $relative_class) {
        // are there any base directories for this namespace prefix?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $base_dir) {

            // replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $base_dir
                    . str_replace('\\', '/', $relative_class)
                    . '.php';

            // if the mapped file exists, require it
            if ($this->requireFile($file)) {
                // yes, we're done
                return $file;
            }
        }

        // never found it
        return false;
    }

    protected function requireFile($file) {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }

}
