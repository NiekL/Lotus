
<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';


// Initialize the form using useForm from Inertia.js
const form = useForm({
    name: '',
    customer_id: '',
    description: '',
    date: '',
    arrival_time: '',
    end_time: '',
    amount_lotus: 1,
    payment_mark: '',
    payment_mark_customer: '',
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



// Compute de huidige datum
const today = computed(() => {
    const now = new Date();
    return now.toISOString().split('T')[0]; // zet datum om naar YYYY-MM-DD formaat
});

//TODO
form.rate_group = '1';

//User Roles
const page = usePage();
const userRoles = computed(() => page.props.auth.user?.roles || []);

const isAdmin = computed(() => userRoles.value.includes("admin"));
const isCoordinator = computed(() => userRoles.value.includes("coordinator"));
const isKlant = computed(() => userRoles.value.includes("klant"));

// Customer selection logic
const customers = ref([]);

onMounted(async () => {
    if (isAdmin.value || isCoordinator.value) {
        try {
            const response = await axios.get(route('users.customersOnly')); // Use the new route
            customers.value = response.data.customers;
        } catch (error) {
            console.error("Error fetching customers:", error);
        }
    }

    if (isKlant.value) {
        form.customer_id = page.props.auth.user?.id; // Set customer_id for klanten automatically
    }
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

//Tijd in kwartieren
const correctTime = (field) => {
    if (!form[field]) return;

    const [hours, minutes] = form[field].split(":").map(Number);
    const roundedMinutes = Math.round(minutes / 15) * 15; // Ronden naar dichtstbijzijnde 15 minuten

    // Zorgt ervoor dat het correct blijft in de 24-uurs tijdsindeling
    const correctedHours = roundedMinutes === 60 ? (hours + 1) % 24 : hours;
    const correctedMinutes = roundedMinutes === 60 ? 0 : roundedMinutes;

    form[field] = `${String(correctedHours).padStart(2, "0")}:${String(correctedMinutes).padStart(2, "0")}`;
};
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
                                <InputLabel for="name" value="Naam bedrijf/organisatie" />
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

                            <!-- CREATE AN (invisible) OPTION to set the customer_id to the current user id if the role is klant. Create an select option to select a klant when the role is 'admin'. -->
                            <!-- Customer selection (Hidden for 'klant', Dropdown for 'admin') -->
                            <div v-if="isAdmin || isCoordinator">
                                <InputLabel for="customer_id" value="Selecteer Klant" />
                                <select id="customer_id" v-model="form.customer_id" class="block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="" disabled>Selecteer een klant</option>
                                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                        {{ customer.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.customer_id" />
                            </div>

                            <!-- Invisible field for 'klant' -->
                            <input v-if="isKlant" type="hidden" v-model="form.customer_id" />

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
                                <InputLabel for="arrival_time" value="Starttijd (wordt afgerond naar kwartieren)" />
                                <TextInput
                                    id="arrival_time"
                                    type="time"
                                    class="mt-1 block w-full"
                                    v-model="form.arrival_time"
                                    @blur="correctTime('arrival_time')"
                                />
                                </div>

                                <div class="w-full lg:w-1/3 inline-block">
                                    <InputLabel for="end_time" value="Eindtijd (wordt afgerond naar kwartieren)" />
                                    <TextInput
                                        id="end_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="form.end_time"
                                        @blur="correctTime('end_time')"
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
                                    min="1"
                                    required
                                />
                                </div>
                                <div class="w-full lg:w-1/2 inline-block">
                                    <InputLabel for="payment_mark_customer" value="Eigen referentie (Optioneel, voor als u een eigen referentie wil toevoegen)" />
                                    <TextInput
                                        id="payment_mark_customer"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.payment_mark_customer"
                                    />
                                </div>

                            </div>

<!--                            <div>-->
<!--                                <InputLabel for="rate_group" value="Tariefgroep (Selecteerveld en voor iedereen?)" />-->
<!--                                <TextInput-->
<!--                                    id="rate_group"-->
<!--                                    type="text"-->
<!--                                    class="mt-1 block w-full"-->
<!--                                    v-model="form.rate_group"-->
<!--                                    required-->
<!--                                />-->
<!--                            </div>-->
                            <input type="hidden" v-model="form.rate_group" />


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
                                    <InputLabel for="city" value="Plaatsnaam speellocatie" />
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
                                    <InputLabel for="street_name" value="Straatnaam speellocatie" />
                                    <TextInput
                                        id="street_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.street_name"
                                        required
                                    />
                                </div>

                                <div class="w-full lg:w-1/3 inline-block">
                                    <InputLabel for="house_number" value="Huisnummer speellocatie" />
                                    <TextInput
                                        id="house_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.house_number"
                                        required
                                    />
                                </div>

                                <div class="w-full lg:w-1/3 inline-block">
                                    <InputLabel for="zipcode" value="Postcode speellocatie" />
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

