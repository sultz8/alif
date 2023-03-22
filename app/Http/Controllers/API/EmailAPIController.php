<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\EmailRequest;
use App\Models\Contact;
use App\Models\Email;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class EmailAPIController extends APIController
{
    /**
     * Получить список email адресов указанного контакта.
     *
     * @param Contact $contact
     *
     * @return JsonResponse
     */
    public function index(Contact $contact): JsonResponse
    {
        return $this->sendSuccess(__('rest.index_success'), $contact->emails());
    }

    /**
     * Массовое создание email адресов указанному контакту.
     *
     * @param EmailRequest $request
     * @param Contact $contact
     *
     * @return JsonResponse
     */
    public function store(EmailRequest $request, Contact $contact): JsonResponse
    {
        $validatedData = $request->validated();
        $createdEmails = [];

        foreach ($validatedData['emails'] as $record) {
            $email = new Email($record);
            $email->contact()->associate($contact);
            $email->save();

            $createdEmails[] = $email;
        }

        return $this->sendSuccess(__('rest.store_success'), $createdEmails);
    }

    /**
     * Массовое изменение email адресов у указанного контакта
     *
     * @param EmailRequest $request
     * @param Contact $contact
     *
     * @return JsonResponse
     */
    public function update(EmailRequest $request, Contact $contact): JsonResponse
    {
        $validateData = $request->validated();
        $updatedEmails = [];

        foreach ($validateData['emails'] as $record) {
            $email = Email::find($record['id']);
            $email->update(Arr::except($record, 'id'));

            $updatedEmails[] = $email;
        }

        return $this->sendSuccess(__('rest.update_success'), $updatedEmails);
    }

    /**
     * Удалить указанный email.
     *
     * @param Email $email
     *
     * @return JsonResponse
     */
    public function destroy(Email $email): JsonResponse
    {
        $email->delete();

        return $this->sendSuccess(__('rest.delete_success'));
    }
}
