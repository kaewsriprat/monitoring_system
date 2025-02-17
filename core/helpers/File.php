<?php

/** 
 * 
 * USAGE:
 * 
 * $file = new File();
 * $file->setMaxFileSize(50); // In MB
 * $file->setTargetDir($targetDir);
 * $file->setTargetFileName($targetFileName);
 * $file->setAllowedFileTypes('pdf', 'doc', 'docx');
 * $result = $file->upload($_FILES);
 * 
 * **/

declare(strict_types=1);

class File
{
    private const ROOT_UPLOAD_DIR = 'public/uploads/';

    private array $files;
    private string $targetDir;
    private string $targetFileName;
    private string $fileExt;
    private string $completeUploadPath;
    private int $maxFileSize = 52428800; // Default max size 50MB
    private array $allowedFileMimeTypes = [];

    public function upload($files)
    {
        if ($this->isFileEmpty($files)) {
            return ['status' => false, 'error' => 'File is empty.'];
        }

        // if ($this->exeededSystemFileSizeLimit($this->maxFileSize)) {
        //     return ['status' => false, 'error' => 'Request max file size exceeded system limit.'];
        // }

        if (!$this->validateFile()) {
            return ['status' => false, 'error' => $this->getError()];
        }

        $this->createTargetDir();
        $this->setFileExt();
        $this->completeUploadPath = $this->setCompleteUploadPath();

        if (move_uploaded_file($this->files['tmp_name'], $this->completeUploadPath)) {
            return ['status' => true, 'filePath' => $this->completeUploadPath];
        }

        return ['status' => false, 'error' => 'File upload failed.'];
    }

    public function setTargetDir(string $targetDir): void
    {
        $this->targetDir = rtrim(self::ROOT_UPLOAD_DIR, '/') . '/' . trim($targetDir, '/');
    }

    public function setAllowedFileTypes(string ...$allowedFileTypes): void
    {
        $this->allowedFileMimeTypes = $this->mimeMapping($allowedFileTypes);
    }

    public function setTargetFileName(string $targetFileName): void
    {
        $this->targetFileName = $targetFileName;
    }

    private function setFileExt(): void
    {
        $this->fileExt = pathinfo($this->files['name'], PATHINFO_EXTENSION);
    }

    private function setCompleteUploadPath(): string
    {
        return $this->targetDir . '/' . $this->targetFileName . '.' . $this->fileExt;
    }

    public function setMaxFileSize(int $maxFileSize): void
    {
        $this->maxFileSize = $this->mbToBytes($maxFileSize);
    }

    public function setAllowedFileMimeTypes(array $allowedFileMimeTypes): void
    {
        $this->allowedFileMimeTypes = $allowedFileMimeTypes;
    }

    private function validateMimeType(): bool
    {
        return in_array($this->files['type'], $this->allowedFileMimeTypes, true);
    }

    private function validateFileSize(): bool
    {
        return $this->files['size'] <= $this->maxFileSize;
    }

    private function validateFile(): bool
    {
        return $this->validateMimeType() && $this->validateFileSize();
    }

    private function createTargetDir(): void
    {
        if (!is_dir($this->targetDir) && !mkdir($this->targetDir, 0777, true) && !is_dir($this->targetDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $this->targetDir));
        }
    }

    private function getError(): string
    {
        if (!$this->validateMimeType()) {
            return 'Invalid file type.';
        }

        if (!$this->validateFileSize()) {
            return 'File size exceeded.';
        }

        return 'Unknown error.';
    }

    private function mbToBytes(int $mb): int
    {
        return $mb * 1024 * 1024;
    }

    private function exeededSystemFileSizeLimit(int $maxFileSize): bool
    {
        $systemMaxFileSize = ini_get('upload_max_filesize');
        $systemMaxFileSize = (int) substr($systemMaxFileSize, 0, -1);

        if ($maxFileSize >= $this->mbToBytes($systemMaxFileSize)) {
            return true;
        } else {
            return false;
        }
    }

    private function isFileEmpty($files): bool
    {
        if (empty($files['name'])) {
            return true;
        }
        $this->files = $files;
        return false;
    }

    //mime mapping
    private function mimeMapping(array $allowedFileMimeTypes): array
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'txt' => 'text/plain',
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'mp3' => 'audio/mpeg',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
        ];

        $allowedMimeTypes = [];
        foreach ($allowedFileMimeTypes as $type) {
            if (array_key_exists($type, $mimeTypes)) {
                $allowedMimeTypes[] = $mimeTypes[$type];
            }
        }

        return $allowedMimeTypes;

    }
}
