
<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";


</script>

<template>
    <Head title="Aanvraag aanmaken" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aanvraag aanmaken</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aanvraag goedkeuren/afwijzen</h2>
                    <hr class="mb-4">
                    <p class="mb-1">(Dit is niet voor iedereen zichtbaar)</p>
                    <!-- Goedkeuren Knop -->
                    <button class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 mr-4">
                        Goedkeuren
                    </button>

                    <!-- Afwijzen Knop -->
                    <button class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        Afwijzen
                    </button>
                </div>
            </div>
        </div>


        <div class="pb-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Gegevens aanvraag</h2>
                    <hr class="mb-4">
                    <!-- Naam aanvraag -->
                    <div class="mb-4">
                        <InputLabel for="name" value="Naam aanvraag" />
                        <p class="border p-2 rounded">
                            Naam aanvraag
                        </p>
                    </div>

                    <!-- Beschrijving -->
                    <div class="mb-4">
                        <InputLabel for="description" value="Beschrijving" />
                        <p class="border p-2 rounded">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                    </div>

                    <div class="mb-4">
                        <InputLabel for="number_of_people" value="Aantal benodigde lotusslachtoffers " />
                        <p class="border p-2 rounded">
                            6
                        </p>
                    </div>

                    <!-- Bijzonderheden -->
                    <div class="mb-4">
                        <InputLabel for="special_requests" value="Bijzonderheden" />
                        <p class="border p-2 rounded">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Datum, tijd en locatie</h2>
                    <hr class="mb-4">
                    <!-- Begindatum en tijd / Einddatum en tijd -->
                    <div class="flex gap-6 flex-wrap lg:flex-nowrap mb-4">
                        <div class="w-full lg:w-1/2">
                            <InputLabel for="start_date_time" value="Begindatum en tijd" />
                            <p class="border p-2 rounded">
                                03-09-2024 14:00
                            </p>
                        </div>
                        <div class="w-full lg:w-1/2">
                            <InputLabel for="end_date_time" value="Einddatum en tijd" />
                            <p class="border p-2 rounded">
                                03-09-2024 18:00
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <InputLabel for="location" value="Plaats" />
                        <p class="border p-2 rounded">
                            Locatie van het evenement
                        </p>
                    </div>
                    <div class="mb-4">
                        <InputLabel for="street_name" value="Straatnaam" />
                        <p class="border p-2 rounded">
                            Straatnaam 4
                        </p>
                    </div>

                    <div class="mb-4">
                        <InputLabel for="postal_code" value="Postcode" />
                        <p class="border p-2 rounded">
                            1234 AA
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Contactgegevens</h2>
                    <hr class="mb-4">

                    <!-- Naam aanvrager / Plaats / Straatnaam / Huisnummer / Postcode -->
                    <div class="mb-4">
                        <InputLabel for="applicant" value="Naam aanvrager" />
                        <p class="border p-2 rounded">
                            Naam van de Klant (indoen nodig)
                        </p>
                    </div>

                    <!-- Contactpersoon / Contact telefoonnummer / Contact email -->
                    <div class="mb-4">
                        <InputLabel for="contact_person" value="Contactpersoon" />
                        <p class="border p-2 rounded">
                            Naam Contactpersoon
                        </p>
                    </div>
                    <div class="mb-4">
                        <InputLabel for="contact_phone" value="Contact telefoonnummer" />
                        <p class="border p-2 rounded">
                            06123456789
                        </p>
                    </div>
                    <div class="mb-4">
                        <InputLabel for="contact_email" value="Contact email" />
                        <p class="border p-2 rounded">
                            contactpersoon@email.nl
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aangemelde lotusslachtoffers</h2>
                    <hr class="mb-4">
                    <ul class="space-y-4">
                        <!-- Persoon 1 -->
                        <li class="flex items-center space-x-4 border-b border-gray-200 pb-4">
                            <img src="https://via.placeholder.com/50" alt="Profielfoto van Jan" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="text-lg font-medium">Jan Jansen</p>
                                <p class="text-gray-500">Aanwezig</p>
                            </div>
                        </li>

                        <!-- Persoon 2 -->
                        <li class="flex items-center space-x-4 border-b border-gray-200 pb-4">
                            <img src="https://via.placeholder.com/50" alt="Profielfoto van Petra" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="text-lg font-medium">Petra de Vries</p>
                                <p class="text-gray-500">Afwezig</p>
                            </div>
                        </li>

                        <!-- Persoon 3 -->
                        <li class="flex items-center space-x-4 border-b border-gray-200 pb-4">
                            <img src="https://via.placeholder.com/50" alt="Profielfoto van Klaas" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="text-lg font-medium">Klaas van Dijk</p>
                                <p class="text-gray-500">Aanwezig</p>
                            </div>
                        </li>

                        <!-- Persoon 4 -->
                        <li class="flex items-center space-x-4">
                            <img src="https://via.placeholder.com/50" alt="Profielfoto van Eva" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="text-lg font-medium">Eva Jansen</p>
                                <p class="text-gray-500">Aanwezig</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Geef feedback</h2>
                    <hr class="mb-4">

                    <form action="#" class="mt-6 space-y-6">
                        <div>
                            <InputLabel for="feedback" value="Feedback" />

                            <TextAreaInput
                                id="feedback"
                                class="mt-1 block w-full"
                                required
                                autofocus
                            />

                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton>Opslaan</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="pb-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Factuuregegevens</h2>
                    <hr class="mb-4">
                    <p>Dit is niet voor alle gebruikers te zien</p>
                    <div class="space-y-6">

                        <div>
                            <InputLabel for="billing_number" value="Factuurnummer" />

                            <TextInput
                                id="billing_number"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                model-value="#12345"
                                disabled/>
                        </div>

                        <div>
                            <InputLabel for="adress" value="Adres" />

                            <TextInput
                                id="adress"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                model-value="Straatnaam 4"
                                disabled/>
                        </div>
                        <div>
                            <InputLabel for="zipcode" value="Postcode" />

                            <TextInput
                                id="zipcode"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                model-value="1234 AA"
                                disabled/>
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
                                disabled
                            />
                        </div>

                        <div>
                            <InputLabel for="iban" value="IBAN" />

                            <TextInput
                                id="iban"
                                type="text"
                                class="mt-1 block w-full"
                                model-value="NL 00 RABO 01234567"
                                disabled
                            />
                        </div>

                        <div>
                            <InputLabel for="tariefgroep" value="Tariefgroep" />

                            <TextInput
                                id="tariefgroep"
                                type="text"
                                class="mt-1 block w-full"
                                model-value="Naam tariefgroep"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>

