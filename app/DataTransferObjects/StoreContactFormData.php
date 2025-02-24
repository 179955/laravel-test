<?php

namespace App\DataTransferObjects;

use Illuminate\Http\UploadedFile;

/**
 * @property UploadedFile[] $files
 * @property UploadedFile[] $images
 */
final readonly class StoreContactFormData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $message,
        public ?string $street,
        public ?string $state,
        public ?string $zip,
        public ?string $country,
        public array $files,
        public array $images,
    ) {}

    public function toArray(array $except = []): array
    {
        $reflection = new \ReflectionClass($this);
        $array = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            if (in_array($property->getName(), $except, true)) {
                continue;
            }

            $array[$property->getName()] = $property->getValue($this);
        }

        return $array;
    }
}
