<?php
namespace Project\Controllers\Tools;

class File
{
    private $_fileName; // Name of the file
    private $_fileSize; // Size
    private $_fileTmp; // Temporary
    private $_fileDim; // Dimension
    private $_fileError; // Error of transfert (0 if there are no error)
    private $_fileExtension; // Extension of file
    private $_extensions; // Extensions allows
    private $_size; // Size max allow
    private $_dim; // Dimension max allow
    private $_dir; // Directory of save file
    public  $fileNameHash; // Name of the file with hash
    private $_error = [];

    public function __construct($file, $size, $extensions, $dim, $dir)
    {
        // Fill the attributes
        $this->_fileName      = $this->getFileName($file['name']);
        $this->_fileSize      = $file['size'];
        $this->_fileTmp       = $file['tmp_name'];
        $this->_fileDim       = getimagesize($file['tmp_name']);
        $this->_fileError     = $file['error'];
        $this->_fileExtension = '.' . strtolower(  substr(  strrchr($file['name'], '.')  ,1)  );
        $this->_extensions    = $extensions;
        $this->_size          = $size;
        $this->_dim           = $dim;
        $this->_dir           = $dir;
    }

    public function getFileName($file)
    {
        // Get the file name (use preg_replace to change unwanted words)
        $fileName = strtr($file,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        $fileName = Tools::pregReplace($fileName,'file');
        $fileName = explode('.',$fileName)[0];

        return $fileName;
    }

    public function check()
    {
        // Check if has no error in file (name, size, extension...). You can add more verification
        if ($this->_fileError > 0) $this->_error['transfert'] = true;
        if (!in_array($this->_fileExtension, $this->_extensions)) $this->_error['extension'] = true;
        if ($this->_fileSize > $this->_size) $this->_error['size'] = true;
        if ($this->_fileDim[0] > $this->_dim[0] || $this->_fileDim[1] > $this->_dim[1]) $this->_error['dim'] = true;
    }

    public function upload()
    {
        // If has no error, upload the file and add the chmod 755
        if (empty($this->_error))
        {
            $this->fileNameHash = hash('sha1', microtime($this->_fileName)) . $this->_fileExtension;
            move_uploaded_file($this->_fileTmp, APP_PATH . $this->_dir . $this->fileNameHash);
            chmod(APP_PATH . $this->_dir . $this->fileNameHash, 755);

            return $this->fileNameHash;
        }
        else
        {
            /*
             * Else, send an error msg like ['error' => 'extension']
             * You can edit and add a code error if you prefer (to avoid has a many msg)
             */
            foreach ($this->_error as $k => $v)
            {
                return ['error' => $k];
            }
        }
    }
}