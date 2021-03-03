<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public const AVATARS_DIRECTORY = 'avatars';
    public const TRICKS_DIRECTORY = 'tricks';
    private SluggerInterface $slugger;
    private string $baseDirectory;
    private Filesystem $fileSystem;

    /**
     * FileUploader constructor.
     *
     * @param SluggerInterface $slugger
     * @param string           $baseDirectory
     */
    public function __construct(SluggerInterface $slugger, string $baseDirectory)
    {
        $this->slugger = $slugger;
        $this->baseDirectory = $baseDirectory;
        $this->fileSystem = new Filesystem();
    }

    /**
     * @param UploadedFile $file
     * @param string       $directory
     *
     * @return string
     */
    public function upload(UploadedFile $file, string $directory = self::TRICKS_DIRECTORY): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid('', false).'.'.$file->guessExtension();

        $targetDirectory = $this->baseDirectory.\DIRECTORY_SEPARATOR.$directory;

        if (!$this->fileSystem->exists($targetDirectory)) {
            $this->fileSystem->mkdir($targetDirectory);
        }

        try {
            $file->move(
                $targetDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            $this->fileSystem->remove($newFilename);
        }

        return $newFilename;
    }

    /**
     * @param string $fileName
     * @param string $directory
     */
    public function remove(string $fileName, string $directory = self::TRICKS_DIRECTORY): void
    {
        $this->fileSystem->remove($this->baseDirectory.\DIRECTORY_SEPARATOR.$directory.\DIRECTORY_SEPARATOR.$fileName);
    }
}
