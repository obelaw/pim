<?php

namespace Obelaw\Pim\Data;

class MediaDTO
{
    public function __construct(
        public string $filePath,
        public string $fileType,
        public ?string $mimeType = null,
        public ?int $fileSize = null,
        public bool $isPrimary = false,
    ) {}

    public function toArray(): array
    {
        return [
            'file_path' => $this->filePath,
            'file_type' => $this->fileType,
            'mime_type' => $this->mimeType,
            'file_size' => $this->fileSize,
            'is_primary' => $this->isPrimary,
        ];
    }
}
