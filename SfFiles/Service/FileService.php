<?php

namespace Newageerp\SfFiles\Service;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Symfony\Component\Filesystem\Filesystem;
use Newageerp\SfS3Client\SfS3Client;

class FileService
{
    protected string $className = 'App\Entity\File';
    protected string $localStorage;

    protected string $frontUrl = '';

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $config = ConfigService::getConfig('main');
        $this->frontUrl = $config['url'];
        
        $this->localStorage = LocalConfigUtilsV3::getUserStoragePath();

        $this->entityManager = $entityManager;
    }

    public function cacheFileToPublicFolder($file): string
    {
        $fileUrl = $this->frontUrl . '/app/nae-core/files/viewById?id=' . $file->getId();

        $fileName = 'caspian/cache/' . $file->getId() . '_' . $file->getFileName();

        $url = SfS3Client::fileExists($fileName);
        if (!$url) {
            $url = SfS3Client::saveBase64File($fileName, base64_encode(file_get_contents($fileUrl)), 'public-read');
        }

        return $url;
    }

    public function getTmpDir()
    {
        $dir = $this->localStorage . '/tmp';
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        return $dir;
    }

    public static function getOrigin()
    {
        if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $origin = $_SERVER['HTTP_ORIGIN'];
        } else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $origin = $_SERVER['HTTP_REFERER'];
        } else {
            $origin = $_SERVER['REMOTE_ADDR'];
        }
        return $origin;
    }


    public function createFolder(string $folder)
    {
        $path = $this->localStorage . '/' . ltrim($folder, '/');

        $filesystem = new Filesystem();
        if (!$filesystem->exists($path)) {
            $filesystem->mkdir($path);
        }

        return $path;
    }

    public function uploadFile($creator, string $fileName, string $folder, $fileData)
    {
        $className = $this->className;

        $path = $this->createFolder($folder);

        $orm = new $className();
        $orm->setCreator($creator);

        $newFileName = random_int(0, 1000) . '' . time() . '___' . mb_strtolower($fileName);
        $filePath = $path . '/' . $newFileName;
        $localPath = $folder . '/' . $newFileName;

        file_put_contents(
            $filePath,
            $fileData
        );

        $orm->setFolder($folder);
        $orm->setFileName($newFileName);
        $orm->setOrgFileName(mb_strtolower($fileName));
        $orm->setPath(ltrim($localPath, '/'));

        return $orm;
    }

    /**
     *
     */
    public function listFolderFiles(string $folder, bool $skipDeleted = false)
    {
        $fileRepository = $this->entityManager->getRepository($this->className);

        $folderTrim = ltrim($folder, '/');

        $files = $fileRepository->findByFolder($folderTrim);

        $contents = [];
        foreach ($files as $file) {
            $ext = explode(".", $file->getOrgFileName());
            $ext = $ext[count($ext) - 1];

            if ($skipDeleted && $file->getDeleted()) {
                continue;
            }

            $contents[] = [
                'fullPath' => $this->localStorage . '/' . $file->getPath(),
                'path' => $file->getPath(),
                'filename' => $file->getOrgFileName(),
                'extension' => $ext,
                'deleted' => $file->getDeleted(),
                'id' => $file->getId()
            ];
        }
        return $contents;
    }

    /**
     * Get the value of localStorage
     *
     * @return string
     */
    public function getLocalStorage(): string
    {
        return $this->localStorage;
    }
}
