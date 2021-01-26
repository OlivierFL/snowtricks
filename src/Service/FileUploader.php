<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private SluggerInterface $slugger;
    private string $targetDirectory;
    private Filesystem $fileSystem;

    /**
     * FileUploader constructor.
     *
     * @param SluggerInterface $slugger
     * @param string           $targetDirectory
     */
    public function __construct(SluggerInterface $slugger, string $targetDirectory)
    {
        $this->slugger = $slugger;
        $this->targetDirectory = $targetDirectory;
        $this->fileSystem = new Filesystem();
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid('', false).'.'.$file->guessExtension();

        if (!$this->fileSystem->exists($this->targetDirectory)) {
            $this->fileSystem->mkdir($this->targetDirectory);
        }

        try {
            $file->move(
                $this->targetDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            $this->fileSystem->remove($newFilename);
        }

        return $newFilename;
    }

    /**
     * @param string $fileName
     *
     * @return void
     */
    public function remove(string $fileName): void
    {
        $this->fileSystem->remove($this->targetDirectory.'/'.$fileName);
    }
}
