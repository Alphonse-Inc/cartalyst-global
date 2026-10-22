<?php
declare(strict_types=1);

namespace App;

final class Upload
{
    /** @return array{stored:string, original:string, mime:string}|null */
    public static function storeProcurement(?array $file): ?array
    {
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('The file could not be uploaded. Please try again.');
        }
        $max = (int) config('uploads.max_bytes');
        if (($file['size'] ?? 0) > $max) {
            throw new \RuntimeException('The file must be 5MB or smaller.');
        }
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new \RuntimeException('Invalid upload.');
        }

        $original = (string) ($file['name'] ?? 'file');
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowedExt = config('uploads.allowed_ext', []);
        if (!in_array($ext, $allowedExt, true)) {
            throw new \RuntimeException('File type is not allowed. Use PDF, DOC, XLS, JPG, or PNG.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        $allowedMime = config('uploads.allowed_mime', []);
        if (!in_array($mime, $allowedMime, true)) {
            throw new \RuntimeException('File type could not be verified.');
        }

        $dir = UPLOAD_PATH . '/procurement/' . date('Y');
        if (!is_dir($dir) && !mkdir($dir, 0750, true) && !is_dir($dir)) {
            throw new \RuntimeException('Upload directory is not writable.');
        }

        $storedName = bin2hex(random_bytes(16)) . '.' . $ext;
        $destination = $dir . '/' . $storedName;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \RuntimeException('The file could not be saved.');
        }
        @chmod($destination, 0640);

        $relative = 'procurement/' . date('Y') . '/' . $storedName;
        return [
            'stored' => $relative,
            'original' => mb_substr($original, 0, 190),
            'mime' => $mime,
        ];
    }
}
