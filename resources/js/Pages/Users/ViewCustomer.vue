<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

</script>

<template>
    <Head title="Bekijk klant" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bekijk klant</h2>
        </template>
        <form>
        <div class="py-8">
            <div class="mx-auto sm:px-6 lg:px-8">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md font-semibold uppercase">Account gegevens</h2>
                    <hr class="mb-4">

                    <div class="space-y-6">

                        <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                            <div class="w-full lg:w-1/2 inline-block">
                                <InputLabel for="name" value="Voornaam" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    model-value="Voornaam"/>
                            </div>
                            <div class="w-full lg:w-1/2 inline-block">
                                <InputLabel for="lastname" value="Achternaam" />

                                <TextInput
                                    id="lastname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    model-value="Achternaam"/>
                            </div>
                        </div>
                        <div>
                            <InputLabel for="email" value="Email" />

                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                model-value="e-mail@test.nl"
                                required
                                autocomplete="username"
                            />
                        </div>
                    </div>

                </div>
            </div>
        </div>

            <div class="pb-8">
                <div class="mx-auto sm:px-6 lg:px-8">

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h2 class="mb-2 text-md font-semibold uppercase">Factuur gegevens</h2>
                        <hr class="mb-4">

                        <div class="space-y-6">

                                <div>
                                    <InputLabel for="adress" value="Adres" />

                                    <TextInput
                                        id="adress"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        autofocus
                                        model-value="Straatnaam 4"/>
                                </div>
                                <div>
                                    <InputLabel for="zipcode" value="Postcode" />

                                    <TextInput
                                        id="zipcode"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        autofocus
                                        model-value="1234 AA"/>
                                </div>
                            <div>
                                <InputLabel for="city" value="Plaats" />

                                <TextInput
                                    id="city"
                                    type="text"
                                    class="mt-1 block w-full"
                                    model-value="Plaats"
                                    required
                                    autocomplete="city"
                                />
                            </div>

                            <div>
                                <InputLabel for="iban" value="IBAN" />

                                <TextInput
                                    id="iban"
                                    type="text"
                                    class="mt-1 block w-full"
                                    model-value="NL 00 RABO 01234567"
                                    required
                                />
                            </div>

                            <div>
                                <InputLabel for="tariefgroep" value="Tariefgroep" />

                                <TextInput
                                    id="tariefgroep"
                                    type="text"
                                    class="mt-1 block w-full"
                                    model-value="Naam tariefgroep"
                                    required
                                />
                            </div>

                            <div>
                                <PrimaryButton>Opslaan</PrimaryButton>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>


        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="bg-white overflow-scroll shadow-sm rounded-md sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h2 class="mb-2 text-md uppercase font-semibold">Gekoppelde aanvragen</h2>
                        <hr class="mb-4">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right  text-gray-500 ">
                                <thead class=" text-gray-700 uppercase text-sm bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Aanvraag
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Datum
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Plaats
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tijd
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Plekken
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Bekijken
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel één
                                    </th>
                                    <td class="px-6 py-4">
                                        02-03-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Zwolle
                                    </td>
                                    <td class="px-6 py-4">
                                        14:00 - 18:00
                                    </td>
                                    <td class="px-6 py-4">
                                        4 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out"><i class="fa-solid fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-gray-50  border-b hover:bg-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        Aanvraag titel twee
                                    </th>
                                    <td class="px-6 py-4">
                                        12-05-2024
                                    </td>
                                    <td class="px-6 py-4">
                                        Staphorst
                                    </td>
                                    <td class="px-6 py-4">
                                        18:00 - 22:00
                                    </td>
                                    <td class="px-6 py-4">
                                        1 / <strong>6</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a :href="route('lotus-requests.viewlotusrequest')" class="inline-flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 ease-in-out"><i class="fa-solid fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>




    </AuthenticatedLayout>
</template>

