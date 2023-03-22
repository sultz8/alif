<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactAPIController extends APIController
{
    /**
     * Получить список контактов.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->sendSuccess(
            __('rest.index_success'),
            Contact::with(['emails', 'phones'])
                ->whereHas(
                    'emails',
                    function ($query) use ($request) {
                        return $query->where('email', 'like', '%' . $request->input('search') . '%');
                    })
                ->orWhereHas(
                    'phones',
                    function ($query) use ($request) {
                        return $query->where('phone_number', 'like', '%' . $request->input('search') . '%');
                    })
            ->orWhere('full_name', 'like', '%' . $request->input('search') . '%')
            ->where('user_id', '=', $request->user()->getKey())
            ->get()
        );
    }

    /**
     * Создать контакт текущему пользователю.
     *
     * @param ContactRequest $request
     * @return JsonResponse
     */
    public function store(ContactRequest $request): JsonResponse
    {
        $contact = new Contact($request->validated());
        $contact->user()->associate($request->user());

        $contact->save();

        return $this->sendSuccess(__('rest.store_success'), $contact);
    }

    /**
     * Получить указанный контакт.
     *
     * @param Contact $contact
     * @return JsonResponse
     */
    public function show(Contact $contact): JsonResponse
    {
        return $this->sendSuccess(__('rest.show_success'), $contact);
    }

    /**
     * Обновить указанный контакт.
     *
     * @param ContactRequest $request
     * @param Contact $contact
     * @return JsonResponse
     */
    public function update(ContactRequest $request, Contact $contact): JsonResponse
    {
        $contact->update($request->validated());

        return $this->sendSuccess(__('rest.update_success'), $contact);
    }

    /**
     * Удалить указанный контакт.
     *
     * @param Contact $contact
     * @return JsonResponse
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return $this->sendSuccess(__('rest.delete_success'));
    }
}
