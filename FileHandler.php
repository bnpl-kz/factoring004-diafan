<?php

if ( ! defined('DIAFAN'))
{
    $path = __FILE__;
    while(! file_exists($path.'/includes/404.php'))
    {
        $parent = dirname($path);
        if($parent == $path) exit;
        $path = $parent;
    }
    include $path.'/includes/404.php';
}

class FileHandler
{
   public function upload($file)
   {
       $fileTmpPath = $file['tmp_name'];
       $fileName = $file['name'];
       $newFileName = time() . $fileName;
       $destPath = USERFILES .'/'. $newFileName;

       if (move_uploaded_file($fileTmpPath, $destPath)) {
           echo json_encode(['success'=>true,'message' => 'Успех', 'filename' => $newFileName]);
       } else {
           echo json_encode(['success'=>false,'message' => 'Неуспех']);
       }
   }

   public function destroy($fileName)
   {
       if (!unlink(USERFILES .'/'. $fileName)) {
           echo json_encode(['success'=>false,'message' => 'Неуспех']);
       }
       else {
           echo json_encode(['success'=>true,'message' => 'Успех']);
       }
   }
}