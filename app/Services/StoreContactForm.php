<?php

namespace App\Services;

use App\DataTransferObjects\StoreContactFormData;
use App\Models\Submission;

final class StoreContactForm
{
    public function __invoke(StoreContactFormData $data): Submission
    {
        return tap(new Submission, static function (Submission $s) use ($data): void {
            $s->fill($data->toArray(except: ['files', 'images']));
            $s->save();

            $formDir = sprintf('%04d', $s->getKey());

            foreach ($data->files as $f) {
                $f->store($formDir, options: ['disk' => 'files']);
            }

            foreach ($data->images as $f) {
                $f->store($formDir, options: ['disk' => 'files']);
            }
        });
    }
}
