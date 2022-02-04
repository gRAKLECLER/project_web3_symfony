<?php 

namespace App\Utils;
use Gedmo\Sluggable\Util\Urlizer as Urlizer;

class UploadUtils {

    const FORM_IMAGE = 'public/uploads';
    const DEFAULT_IMAGE = 'images/img.jpg';

    // private $publicPath;

    // public function __construct(string $publicPath) {
    //     $this->publicPath = $publicPath;
    // }

    /**
     * @param UploadedFile $file
     * @return string
     */

    public function uploadFiles($file, $request) {
        $newFile = $request->files->get('parameters');
        // $destination = $this->publicPath . '/' . self::FORM_IMAGE;
        // $originalFileName = $file->getClientOriginalName();
        $baseFileName = pathinfo($file, PATHINFO_FILENAME);
        $fileName = $baseFileName . '-' . uniqid() . '.' . $newFile->guessExtension();
  
        // $file->move($destination, $fileName);
  
        return $fileName;
    }
    
}

?>