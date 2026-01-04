<?php

namespace Core;

/**
 * File Upload Handler - Manages secure file uploads
 * 
 * Features:
 *   - File validation (size, type, name)
 *   - Secure upload handling
 *   - Multiple file formats support
 *   - Virus-like attack prevention
 * 
 * Usage:
 *   $upload = new FileUpload($_FILES['avatar'], 'avatars');
 *   if ($upload->isValid()) {
 *       $path = $upload->save();
 *   } else {
 *       $errors = $upload->getErrors();
 *   }
 */
class FileUpload
{
    private array $file;
    private string $uploadDir = 'storage/uploads';
    private array $allowedTypes = [];
    private int $maxSize = 5242880; // 5MB default
    private array $errors = [];
    private string $filename = '';

    private const ALLOWED_EXTENSIONS = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'zip' => 'application/zip',
        'txt' => 'text/plain',
        'csv' => 'text/csv',
    ];

    public function __construct(array $file, string $folder = '')
    {
        $this->file = $file;

        if (!empty($folder)) {
            $this->uploadDir = 'storage/uploads/' . trim($folder, '/');
        }

        $this->allowedTypes = self::ALLOWED_EXTENSIONS;
    }

    /**
     * Validate the uploaded file
     */
    public function isValid(): bool
    {
        // Check if file exists
        if (!isset($this->file['tmp_name']) || !isset($this->file['name'])) {
            $this->errors[] = 'Fichier non reçu';
            return false;
        }

        // Check for upload errors
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadErrorMessage($this->file['error']);
            return false;
        }

        // Check file size
        if ($this->file['size'] > $this->maxSize) {
            $this->errors[] = 'Fichier trop volumineux (max: ' . $this->formatSize($this->maxSize) . ')';
            return false;
        }

        // Check file type
        if (!$this->isAllowedType()) {
            $this->errors[] = 'Type de fichier non autorisé';
            return false;
        }

        // Check if temporary file is valid
        if (!is_uploaded_file($this->file['tmp_name'])) {
            $this->errors[] = 'Tentative d\'upload invalide';
            Logger::warning('Invalid upload attempt detected');
            return false;
        }

        return true;
    }

    /**
     * Check if file type is allowed
     */
    private function isAllowedType(): bool
    {
        $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));

        if (!isset($this->allowedTypes[$ext])) {
            return false;
        }

        // Verify MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $this->file['tmp_name']);
        finfo_close($finfo);

        // Check MIME matches expected
        $expectedMime = $this->allowedTypes[$ext];
        return $mimeType === $expectedMime || in_array($mimeType, explode('|', $expectedMime));
    }

    /**
     * Save the uploaded file
     */
    public function save(): string|false
    {
        if (!$this->isValid()) {
            return false;
        }

        // Create upload directory if it doesn't exist
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // Generate safe filename
        $this->filename = $this->generateSafeFilename();
        $filepath = $this->uploadDir . '/' . $this->filename;

        // Move uploaded file
        if (move_uploaded_file($this->file['tmp_name'], $filepath)) {
            chmod($filepath, 0644);

            Logger::info('File uploaded: {filename}', [
                'filename' => $this->filename,
                'size' => $this->file['size'],
                'path' => $filepath
            ]);

            return $filepath;
        }

        $this->errors[] = 'Erreur lors de la sauvegarde du fichier';
        Logger::error('File upload save failed: {filename}', ['filename' => $this->file['name']]);
        return false;
    }

    /**
     * Generate safe filename
     */
    private function generateSafeFilename(): string
    {
        $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        $basename = pathinfo($this->file['name'], PATHINFO_FILENAME);

        // Remove special characters
        $basename = preg_replace('/[^a-zA-Z0-9_-]/', '', $basename);
        $basename = preg_replace('/-+/', '-', $basename);
        $basename = trim($basename, '-');

        // If basename is empty, use timestamp
        if (empty($basename)) {
            $basename = 'file_' . time();
        }

        // Add uniqueness
        $filename = $basename . '_' . time() . '.' . $ext;

        return $filename;
    }

    /**
     * Set maximum file size
     */
    public function setMaxSize(int $bytes): self
    {
        $this->maxSize = $bytes;
        return $this;
    }

    /**
     * Set allowed file types
     */
    public function setAllowedTypes(array $types): self
    {
        $this->allowedTypes = $types;
        return $this;
    }

    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get uploaded filename
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Get file size
     */
    public function getSize(): int
    {
        return $this->file['size'] ?? 0;
    }

    /**
     * Get original filename
     */
    public function getOriginalName(): string
    {
        return $this->file['name'] ?? '';
    }

    /**
     * Format bytes to human readable size
     */
    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Get upload error message
     */
    private function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'Fichier dépasse la limite du serveur',
            UPLOAD_ERR_FORM_SIZE => 'Fichier dépasse la taille maximale du formulaire',
            UPLOAD_ERR_PARTIAL => 'Fichier partiellement téléchargé',
            UPLOAD_ERR_NO_FILE => 'Aucun fichier téléchargé',
            UPLOAD_ERR_NO_TMP_DIR => 'Répertoire temporaire manquant',
            UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire le fichier',
            UPLOAD_ERR_EXTENSION => 'Extension de fichier bloquée',
            default => 'Erreur d\'upload inconnue',
        };
    }

    /**
     * Delete uploaded file
     */
    public function delete(string $filepath): bool
    {
        if (file_exists($filepath) && is_file($filepath)) {
            if (unlink($filepath)) {
                Logger::info('File deleted: {filepath}', ['filepath' => $filepath]);
                return true;
            }
        }
        return false;
    }

    /**
     * Get file info from path
     */
    public static function getInfo(string $filepath): array|false
    {
        if (!file_exists($filepath)) {
            return false;
        }

        return [
            'name' => basename($filepath),
            'size' => filesize($filepath),
            'type' => mime_content_type($filepath),
            'uploaded' => filemtime($filepath),
            'path' => $filepath,
        ];
    }
}
