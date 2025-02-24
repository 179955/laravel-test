<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class ContactFormTest extends TestCase
{
    #[DataProvider('valid_contact_form_data_provider')]
    public function test_can_submit_contact_form_with_valid_data(array $data): void
    {
        Storage::fake('files');

        $response = $this->postJson('/api/v1/contact-form', $data)
            // ->dump()
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'submission_id',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);

        $id = $response->json('data.submission_id');
        $dir = sprintf('%04d', $id);

        foreach ($data['images'] ?? [] as $f) {
            Storage::disk('files')->assertExists("{$dir}/{$f->hashName()}");
        }

        foreach ($data['files'] ?? [] as $f) {
            Storage::disk('files')->assertExists("{$dir}/{$f->hashName()}");
        }
    }

    public static function valid_contact_form_data_provider(): \Generator
    {
        yield 'without files' => [
            [
                'name' => fake()->firstName(),
                'email' => fake()->safeEmail(),
                'phone' => fake()->e164PhoneNumber(),
                'message' => fake()->text(),
                'street' => fake()->streetName(),
                'state' => fake()->streetName(),
                'zip' => fake()->postCode(),
                'country' => fake()->country(),
                'images' => null,
                'files' => null,
            ],
        ];

        yield 'with files' => [
            [
                'name' => fake()->firstName(),
                'email' => fake()->safeEmail(),
                'phone' => fake()->e164PhoneNumber(),
                'message' => fake()->text(),
                'street' => fake()->streetName(),
                'state' => fake()->streetName(),
                'zip' => fake()->postCode(),
                'country' => fake()->country(),
                'images' => [
                    UploadedFile::fake()->image('image1.jpg'),
                    UploadedFile::fake()->image('image2.jpg'),
                ],
                'files' => [
                    UploadedFile::fake()->create('document1.pdf', mimeType: 'application/pdf'),
                    UploadedFile::fake()->create('document2.pdf', mimeType: 'application/pdf'),
                ],
            ],
        ];
    }
}
