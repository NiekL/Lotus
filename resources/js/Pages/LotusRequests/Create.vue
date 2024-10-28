
<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

// Initialize the form using useForm from Inertia.js
const form = useForm({
    name: '',
    description: '',
    start_date_time: '',
    end_date_time: '',
    applicant: '',
    location: '',
    street_name: '',
    house_number: '',
    postal_code: '',
    number_of_people: 1,
    rate_group: '',
    special_requests: '',
    contact_person: '',
    contact_phone: '',
    contact_email: '',
    payment_reference: '',
});

// Method to handle form submission
const submit = () => {
    form.post(route('lotus-requests.store'), {
        onFinish: () => form.reset(),
        onError: (errors) => {
            // Handle errors, if any, after submission
            console.error(errors);
        }
    });
};
</script>

<template>
    <Head title="Aanvraag aanmaken" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aanvraag aanmaken</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="submit" class=" space-y-6">

                        <h2 class="mb-1 text-sm font-semibold uppercase">Gegevens aanvraag</h2>
                        <hr class="mb-2">
                        <div>
                            <InputLabel for="name" value="Naam aanvraag" />
                            <TextInput
                                id="name"
                                type="text"
                                class="block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Beschrijving" />
                            <TextInput
                                id="description"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.description"
                            />
                        </div>

                        <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                            <div class="w-full lg:w-1/2 inline-block">
                            <InputLabel for="start_date_time" value="Begindatum en tijd" />
                            <TextInput
                                id="start_date_time"
                                type="datetime-local"
                                class="mt-1 block w-full"
                                v-model="form.start_date_time"
                                required
                            />
                            </div>
                            <div class="w-full lg:w-1/2 inline-block">
                            <InputLabel for="end_date_time" value="Einddatum en tijd" />
                            <TextInput
                                id="end_date_time"
                                type="datetime-local"
                                class="mt-1 block w-full"
                                v-model="form.end_date_time"
                            />
                            </div>
                        </div>

                        <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                            <div class="w-full lg:w-1/2 inline-block">
                            <InputLabel for="number_of_people" value="Aantal lotusslachtoffers" />
                            <TextInput
                                id="number_of_people"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.number_of_people"
                                required
                            />
                            </div>
                            <div class="w-full lg:w-1/2 inline-block">
                                <InputLabel for="payment_reference" value="Betalingskenmerk" />
                                <TextInput
                                    id="payment_reference"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.payment_reference"
                                />
                            </div>

                        </div>

                        <div>
                            <InputLabel for="rate_group" value="Tariefgroep" />
                            <TextInput
                                id="rate_group"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.rate_group"
                                required
                            />
                        </div>

                        <div>
                            <InputLabel for="special_requests" value="Bijzonderheden" />
                            <TextInput
                                id="special_requests"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.special_requests"
                            />
                        </div>

                        <h2 class="mb-1 text-sm font-semibold uppercase">Gegevens klant</h2>
                        <hr class="mb-2">
                        <div>
                            <InputLabel for="applicant" value="Naam aanvrager" />
                            <TextInput
                                id="applicant"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.applicant"
                                required
                            />
                        </div>

                        <div>
                            <InputLabel for="location" value="Plaats" />
                            <TextInput
                                id="location"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.location"
                                required
                            />
                        </div>

                        <div>
                            <InputLabel for="street_name" value="Straatnaam" />
                            <TextInput
                                id="street_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.street_name"
                                required
                            />
                        </div>

                        <div>
                            <InputLabel for="house_number" value="Huisnummer" />
                            <TextInput
                                id="house_number"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.house_number"
                                required
                            />
                        </div>

                        <div>
                            <InputLabel for="postal_code" value="Postcode" />
                            <TextInput
                                id="postal_code"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.postal_code"
                                required
                            />
                        </div>




                        <div>
                            <InputLabel for="contact_person" value="Contactpersoon" />
                            <TextInput
                                id="contact_person"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.contact_person"
                                required
                            />
                        </div>

                        <div>
                            <InputLabel for="contact_phone" value="Contact telefoonnummer" />
                            <TextInput
                                id="contact_phone"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.contact_phone"
                                required
                            />
                        </div>

                        <div>
                            <InputLabel for="contact_email" value="Contact email" />
                            <TextInput
                                id="contact_email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.contact_email"
                            />
                        </div>



                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Aanvraag aanmaken</PrimaryButton>
                        </div>
                    </form>


                    <!-- Display form errors -->
                    <div v-if="Object.keys(form.errors).length">
                        <ul>
                            <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

