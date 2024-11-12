
<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, computed } from 'vue';


// Initialize the form using useForm from Inertia.js
const form = useForm({
    name: '',
    description: '',
    date: '',
    arrival_time: '',
    end_time: '',
    amount_lotus: 1,
    payment_mark: '',
    rate_group: '',
    details: '',
    city: '',
    street_name: '',
    house_number: '',
    zipcode: '',
    contact_person: '',
    contact_phone: '',
    contact_email: '',
});

// Method to handle form submission
const submit = () => {
    form.post(route('lotus-requests.store'), {
        onFinish: () => {
            // Reset het formulier en haal de successmelding op
            form.reset();
            form.success = ''; // Reset de successmelding
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

// Compute de huidige datum
const today = computed(() => {
    const now = new Date();
    return now.toISOString().split('T')[0]; // zet datum om naar YYYY-MM-DD formaat
});

</script>

<template>
    <Head title="Aanvraag aanmaken" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aanvraag aanmaken</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8 space-y-6">
                    <form @submit.prevent="submit" class=" space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

                            <h2 class="mb-1 text-sm font-semibold uppercase">Algemene gegevens aanvraag</h2>
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
                                <textarea
                                    id="description"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.description"
                                    rows="4"
                                ></textarea>
                            </div>

                            <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                                <div class="w-full lg:w-1/3 inline-block">
                                    <InputLabel for="date" value="Datum" />
                                    <TextInput
                                        id="date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.date"
                                        :min="today"
                                        required
                                    />
                                </div>
                                <div class="w-full lg:w-1/3 inline-block">
                                <InputLabel for="arrival_time" value="Starttijd" />
                                <TextInput
                                    id="arrival_time"
                                    type="time"
                                    class="mt-1 block w-full"
                                    v-model="form.arrival_time"
                                />
                                </div>

                                <div class="w-full lg:w-1/3 inline-block">
                                    <InputLabel for="end_time" value="Eindtijd" />
                                    <TextInput
                                        id="end_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="form.end_time"
                                    />
                                </div>
                            </div>

                            <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                                <div class="w-full lg:w-1/2 inline-block">
                                <InputLabel for="amount_lotus" value="Aantal lotusslachtoffers nodig" />
                                <TextInput
                                    id="amount_lotus"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.amount_lotus"
                                    required
                                />
                                </div>
                                <div class="w-full lg:w-1/2 inline-block">
                                    <InputLabel for="payment_mark" value="Betalingskenmerk" />
                                    <TextInput
                                        id="payment_mark"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.payment_mark"
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
                                <InputLabel for="details" value="Bijzonderheden" />
                                <textarea
                                    id="details"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.details"
                                    rows="4"
                                ></textarea>
                            </div>
                        </div>

                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">


                            <h2 class="text-sm font-semibold uppercase">Locatiegegevens aanvraag</h2>
                            <hr class="mb-2">

                                <div>
                                    <InputLabel for="city" value="Plaats" />
                                    <TextInput
                                        id="city"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.city"
                                        required
                                    />
                                </div>

                            <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                                 <div class="w-full lg:w-1/3 inline-block">
                                    <InputLabel for="street_name" value="Straatnaam" />
                                    <TextInput
                                        id="street_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.street_name"
                                        required
                                    />
                                </div>

                                <div class="w-full lg:w-1/3 inline-block">
                                    <InputLabel for="house_number" value="Huisnummer" />
                                    <TextInput
                                        id="house_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.house_number"
                                        required
                                    />
                                </div>

                                <div class="w-full lg:w-1/3 inline-block">
                                    <InputLabel for="zipcode" value="Postcode" />
                                    <TextInput
                                        id="zipcode"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.zipcode"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

                            <h2 class="text-sm font-semibold uppercase">Contactgegevens aanvraag</h2>
                            <hr class="mb-2">

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
                                <InputLabel for="contact_phone" value="Telefoonnummer" />
                                <TextInput
                                    id="contact_phone"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.contact_phone"
                                    required
                                />
                            </div>

                            <div>
                                <InputLabel for="contact_email" value="E-mail" />
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

                            <!-- Display form errors -->
                            <div v-if="Object.keys(form.errors).length">
                                <ul>
                                    <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                                </ul>
                            </div>

                        </div>
                    </form>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

