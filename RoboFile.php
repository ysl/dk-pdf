<?php

class RoboFile extends \Robo\Tasks
{

    public function build()
    {
        // create a temporary directory and copy composer.json
        $result = $this->taskFilesystemStack()
            ->mkdir('tmp-build')
            ->copy('composer.json', 'tmp-build/composer.json')
            ->run();

        if (!$result->wasSuccessful()) {
            return $result;
        }

        // run composer install in the temporary directory
        $result = $this->_exec('composer install -d ./tmp-build --no-dev -o');
        if (!$result->wasSuccessful()) {
            return $result;
        }

        // create dk-pdf.zip
        $result = $this->taskPack("dk-pdf.zip")
            ->addDir('dk-pdf/vendor', 'tmp-build/vendor')
            ->addDir('dk-pdf/assets', 'assets')
            ->addDir('dk-pdf/languages', 'languages')
            ->addDir('dk-pdf/src', 'src')
            ->addDir('dk-pdf/templates', 'templates')
            ->addFile('dk-pdf/dk-pdf.php', 'dk-pdf.php')
            ->addFile('dk-pdf/readme.txt', 'readme.txt')
            ->run();

        if (!$result->wasSuccessful()) {
            return $result;
        }

        // delete temporary folder
        $result = $this->taskDeleteDir('tmp-build')->run();
        return $result;
    }
}
