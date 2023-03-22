<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PhoneRequest;
use App\Models\Contact;
use App\Models\Phone;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class PhoneAPIController extends APIController
{
    /**
     * Получить список телефонных номеров указанного контакта.
     *
     * @param Contact $contact
     *
     * @return JsonResponse
     */
    public function index(Contact $contact): JsonResponse
    {
        return $this->sendSuccess(__('rest.index_success'), $contact->phones);
    }

    /**
     * Массовое создание телефонных номеров указанному контакту.
     *
     * @param PhoneRequest $request
     * @param Contact $contact
     *
     * @return JsonResponse
     */
    public function store(PhoneRequest $request, Contact $contact): JsonResponse
    {
        $validatedData = $request->validated();
        $createdPhones = [];

        foreach ($validatedData['phones'] as $record) {
            $phone = new Phone($record);
            $phone->contact()->associate($contact);
            $phone->save();

            $createdPhones[] = $phone;
        }

        return $this->sendSuccess(__('rest.store_success'), $createdPhones);
    }

    /**
     * Массовое изменение телефонных номеров у указанного контакта
     *
     * @param PhoneRequest $request
     * @param Contact $contact
     *
     * @return JsonResponse
     */
    public function update(PhoneRequest $request, Contact $contact): JsonResponse
    {
        $validateData = $request->validated();
        $updatedPhones = [];

        foreach ($validateData['phones'] as $record) {
            $phones = Phone::find($record['id']);
            $phones->update(Arr::except($record, 'id'));

            $updatedPhones[] = $phones;
        }

        return $this->sendSuccess(__('rest.update_success'), $updatedPhones);
    }

    /**
     * Удалить указанный номер телефона.
     *
     * @param Phone $phone
     *
     * @return JsonResponse
     */
    public function destroy(Phone $phone): JsonResponse
    {
        $phone->delete();

        return $this->sendSuccess(__('rest.delete_success'));
    }
}
