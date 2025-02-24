<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormSubmitRequest;
use App\Services\StoreContactForm;

final class ContactFormController
{
    public function __invoke(ContactFormSubmitRequest $request, StoreContactForm $storeContactForm)
    {
        $submission = $storeContactForm($request->asData());

        return response()->json([
            'success' => true,
            'data' => [
                'submission_id' => $submission->getKey(),
            ],
        ]);
    }
}
