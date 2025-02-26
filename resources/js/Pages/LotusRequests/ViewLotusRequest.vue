
<script setup>
import {onMounted, ref, computed} from 'vue';
import axios from 'axios';
import { defineProps } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    lotusRequest: Object, // Define the lotusRequest prop
    signedUpUsers: Array,
    billingInfo: Array,
    userRequestData: Object,
});

const notification = ref({ message: '', type: '' });

const formatTime = (timeString) => {
    const date = new Date(`1970-01-01T${timeString}Z`);
    return date.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' });
};

const acceptRequest = async () => {
    if (!window.confirm("Weet u zeker dat u dit verzoek wilt goedkeuren?")) return;

    try {
        await axios.post(`/lotus-requests/accept/${props.lotusRequest.id}`);
        notification.value = { message: 'Aanvraag is succesvol goedgekeurd.', type: 'success' };
        props.lotusRequest.status = 2; // Update lokaal de status
    } catch (error) {
        notification.value = { message: 'Er is een fout opgetreden bij het goedkeuren.', type: 'error' };
        console.error(error);
    }
};

const declineRequest = async () => {
    if (!window.confirm("Weet u zeker dat u dit verzoek wilt afwijzen?")) return;

    try {
        await axios.post(`/lotus-requests/decline/${props.lotusRequest.id}`);
        notification.value = { message: 'Aanvraag is succesvol afgewezen.', type: 'success' };
        props.lotusRequest.status = 3; // Update lokaal de status
    } catch (error) {
        notification.value = { message: 'Er is een fout opgetreden bij het afwijzen.', type: 'error' };
        console.error(error);
    }
};

const isUserSignedUp = ref(false);
const filledSpots = ref(props.lotusRequest.filled_spots);
const canSignup = computed(() => filledSpots.value < props.lotusRequest.amount_lotus);

// const canSignup = computed(() => {
//     return props.lotusRequest.filled_spots < props.lotusRequest.amount_lotus;
// });

// Controleer de aanmeldstatus van de gebruiker
onMounted(async () => {
    try {
        const response = await axios.get(`/lotus-requests/${props.lotusRequest.id}/check-signup`);
        isUserSignedUp.value = response.data.isUserSignedUp;
    } catch (error) {
        console.error('Fout bij het controleren van de aanmeldstatus:', error);
    }
});

// Functie voor aanmelden
const signup = async () => {
    try {
        await axios.post(`/lotus-requests/${props.lotusRequest.id}/signup`);
        notification.value = { message: 'Je hebt je succesvol aangemeld voor de aanvraag.', type: 'success' };
        isUserSignedUp.value = true;
    } catch (error) {
        notification.value = { message: error.response.data.message || 'Er is een fout opgetreden bij het aanmelden.', type: 'error' };
    }
};

// Functie voor afmelden
const cancelSignup = async () => {
    try {
        await axios.post(`/lotus-requests/${props.lotusRequest.id}/cancel-signup`);
        notification.value = { message: 'Je hebt je succesvol afgemeld voor de aanvraag.', type: 'success' };
        isUserSignedUp.value = false;
        filledSpots.value--;
    } catch (error) {
        notification.value = { message: error.response.data.message || 'Er is een fout opgetreden bij het afmelden.', type: 'error' };
        console.error(error);
    }
};

const lastCopied = ref(null);
const copyToClipboard = async (text, fieldId) => {
    try {
        await navigator.clipboard.writeText(text);
        lastCopied.value = fieldId;
        setTimeout(() => {
            if (lastCopied.value === fieldId) {
                lastCopied.value = null;
            }
        }, 2000); // Optional: Reset after 2 seconds
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};


//Voor user feedback form
const form = ref({
    user_played_time: props.userRequestData.user_played_time ?? '',
    user_amount_km: props.userRequestData.user_amount_km ?? '',
    user_feedback: props.userRequestData.user_feedback ?? '',
    errors: {}
});

console.log(props.userRequestData);

const handleSubmit = async () => {
    try {
        await axios.post(`/lotus-requests/${props.lotusRequest.id}/submit-details`, form.value);
        notification.value = { message: 'Gegevens succesvol opgeslagen.', type: 'success' };
    } catch (error) {
        notification.value = { message: 'Er is een fout opgetreden bij het opslaan.', type: 'error' };
        form.value.errors = error.response.data.errors || {};
    }

    // Scroll to the notification element for error messages as well
    setTimeout(() => {
        document.getElementById("top-of-app").scrollIntoView({ behavior: "smooth" });
    }, 100);
};

//User Roles
const page = usePage();
const userRoles = computed(() => page.props.auth.user?.roles || []);

const isAdmin = computed(() => userRoles.value.includes("admin"));
const isCoordinator = computed(() => userRoles.value.includes("coordinator"));
const isKlant = computed(() => userRoles.value.includes("klant"));
const isLid = computed(() => userRoles.value.includes("lid"));
const isPenningmeester = computed(() => userRoles.value.includes("penningmeester"));
const isSecretaris = computed(() => userRoles.value.includes("secretaris"));


</script>

<template>
    <Head title="Aanvraag aanmaken" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aanvraag {{ lotusRequest.name }}</h2>
        </template>

        <!-- Notification Banner -->
        <div class="pt-8 -pb-8" v-if="notification.message" id="notifications-wrapper">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div :class="{'bg-green-50 border-green-500': notification.type === 'success', 'bg-red-300 border-red-500': notification.type === 'error'}" class="shadow-sm rounded-md sm:rounded-lg">
                    <div class="p-4 rounded">
                        {{ notification.message }}
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isAdmin || isCoordinator || isLid || isPenningmeester || isSecretaris" class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aanmeldstatus</h2>
                    <hr class="mb-4">

                    <!-- Huidige status van aanmelding -->
                    <p v-if="isUserSignedUp" class="mb-4">Je bent momenteel <strong>aangemeld</strong> voor deze aanvraag.</p>
                    <p v-else class="mb-4">Je bent momenteel <strong>niet aangemeld</strong> voor deze aanvraag.</p>

                    <p v-if="!canSignup" class="mb-4">Deze aanvraag zit momenteel vol. Het is dus niet meer mogelijk om je hiervoor aan te melden. Neem bij vragen hierover even contact op met een coördinator.</p>

                    <!-- Knoppen voor aanmelden/afmelden -->
                    <button v-if="!isUserSignedUp && canSignup" @click="signup" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 mr-4">
                        Aanmelden
                    </button>
                    <button v-if="isUserSignedUp" @click="cancelSignup" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        Afmelden
                    </button>
                </div>
            </div>
        </div>

        <div v-if="isAdmin || isCoordinator || isSecretaris" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aanvraag goedkeuren/afwijzen</h2>
                    <hr class="mb-4">
                    <p v-if="lotusRequest.status === 1" class="mb-4">Deze aanvraag is <strong>nog niet behandeld</strong>.</p>
                    <p v-if="lotusRequest.status === 2" class="mb-4">Deze aanvraag is <strong class="text-green-600">goegekeurd</strong>. Toch afwijzen? Klik op de onderstaande knop.</p>
                    <p v-if="lotusRequest.status === 3" class="mb-4">Deze aanvraag is <strong class="text-red-600">afgewezen</strong>. Toch goedkeuren? Klik op de onderstaande knop.</p>


                    <!-- Goedkeuren Knop, zichtbaar als status 1 of 3 -->
                    <button v-if="lotusRequest.status === 1 || lotusRequest.status === 3"
                            @click="acceptRequest"
                            class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 mr-4">
                        Goedkeuren
                    </button>

                    <!-- Afwijzen Knop, zichtbaar als status 1 of 2 -->
                    <button v-if="lotusRequest.status === 1 || lotusRequest.status === 2"
                            @click="declineRequest"
                            class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        Afwijzen
                    </button>
                </div>
            </div>
        </div>


        <div class="pb-8" :class="{ 'pt-8': isKlant }">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Gegevens aanvraag</h2>
                    <hr class="mb-4">
                    <!-- Naam aanvraag -->
                    <div class="mb-4">
                        <InputLabel for="name" value="Naam bedrijf/organisatie" />
                        <p class="border p-2 rounded">
                            {{ lotusRequest.name }}
                        </p>
                    </div>

                    <!-- Beschrijving -->
                    <div class="mb-4">
                        <InputLabel for="description" value="Beschrijving" />
                        <p class="border p-2 rounded" v-if="!lotusRequest.description">Geen beschrijving toegevoegd.</p>
                        <p class="border p-2 rounded" v-else>{{ lotusRequest.description }}</p>
                    </div>

                    <div class="mb-4">
                        <InputLabel for="number_of_people" value="Aantal benodigde lotusslachtoffers " />
                        <p class="border p-2 rounded">
                            {{ lotusRequest.amount_lotus }}
                        </p>
                    </div>

                    <!-- Bijzonderheden -->
                    <div class="mb-4">
                        <InputLabel for="special_requests" value="Bijzonderheden" />
                        <p class="border p-2 rounded">
                            {{ lotusRequest.details }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Datum, tijd en locatie</h2>
                    <hr class="mb-4">
                    <!-- Begindatum en tijd / Einddatum en tijd -->
                    <div class="flex gap-6 flex-wrap lg:flex-nowrap mb-4">
                        <div class="w-full lg:w-1/3">
                            <InputLabel for="date" value="Datum" />
                            <p class="border p-2 rounded">
                                {{ new Date(lotusRequest.date).toLocaleDateString('nl-NL') }}
                            </p>
                        </div>
                        <div class="w-full lg:w-1/3">
                            <InputLabel for="arrival_time" value="Aanvangs tijd" />
                            <p class="border p-2 rounded">
                                {{ formatTime(lotusRequest.arrival_time) }}
                            </p>
                        </div>

                        <div class="w-full lg:w-1/3">
                            <InputLabel for="date_time" value="Eindtijd" />
                            <p class="border p-2 rounded">
                                {{ formatTime(lotusRequest.end_time) }}

                            </p>
                        </div>
                    </div>

                    <div class="flex gap-6 flex-wrap lg:flex-nowrap mb-4">
                        <div class="w-full lg:w-1/3">
                            <InputLabel for="street_name" value="Straatnaam en huisnummer" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ lotusRequest.street_name }}  {{ lotusRequest.house_number }}</span>
                                <i :class="lastCopied === 'street_name' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(lotusRequest.street_name + ' ' + lotusRequest.house_number, 'street_name')">
                                </i>
                            </p>
                        </div>


                        <div class="w-full lg:w-1/3">
                            <InputLabel for="postal_code" value="Postcode" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ lotusRequest.zipcode }}</span>
                                <i :class="lastCopied === 'zipcode' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(lotusRequest.zipcode, 'zipcode')"></i>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/3">
                            <InputLabel for="location" value="Plaats" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ lotusRequest.city }}</span>
                                <i :class="lastCopied === 'city' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(lotusRequest.city, 'city')"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Contactgegevens aanvraag</h2>
                    <hr class="mb-4">

                    <!-- Naam aanvrager / Plaats / Straatnaam / Huisnummer / Postcode -->
                    <div class="mb-4">
                        <InputLabel for="contact_person" value="Contactpersoon" />
                        <p class="border p-2 rounded flex justify-between items-center">
                            <span>{{ lotusRequest.contact_person }}</span>
                            <i :class="lastCopied === 'contact_person' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                               @click="copyToClipboard(lotusRequest.contact_person, 'contact_person')"></i>

                        </p>
                    </div>

                    <div class="mb-4">
                        <InputLabel for="contact_phone" value="Telefoonnummer" />
                        <p class="border p-2 rounded flex justify-between items-center">
                            <span>{{ lotusRequest.contact_phone }}</span>
                            <i :class="lastCopied === 'contact_phone' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                               @click="copyToClipboard(lotusRequest.contact_phone, 'contact_phone')"></i>

                        </p>
                    </div>
                    <div class="mb-4">
                        <InputLabel for="contact_email" value="Contact email" />
                        <p class="border p-2 rounded flex justify-between items-center">
                            <span>{{ lotusRequest.contact_email }}</span>
                            <i :class="lastCopied === 'contact_email' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                               @click="copyToClipboard(lotusRequest.contact_email, 'contact_email')"></i>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aangemelde lotusslachtoffers</h2>
                    <hr class="mb-4">
                    <p v-if="signedUpUsers.length === 0" class="text-gray-500">Er zijn nog geen aanmeldingen voor deze aanvraag.</p>
                    <ul v-else class="space-y-4">
                        <li v-for="user in signedUpUsers" :key="user.id" class="flex items-center space-x-4 border-b border-gray-200 pb-4">
                            <i class="fa-regular fa-user text-2xl self-baseline"></i>
                            <div class="w-full">
                                <p class="text-lg font-medium">{{ user.name }}</p>

                                <!-- Display Pivot Data if available -->
                                <div v-if="user.pivot" class="text-gray-600 text-sm mt-2 space-y-1">
                                    <p v-if="!isKlant"><strong>Gespeelde tijd:</strong> {{ user.pivot.user_played_time ?? 'Geen gegevens' }} minuten</p>
                                    <p v-if="!isKlant"><strong>Gereden kilometers:</strong> {{ user.pivot.user_amount_km ?? 'Geen gegevens' }} km</p>
                                    <p v-if="!isKlant"><strong>Feedback:</strong> {{ user.pivot.user_feedback ?? 'Geen feedback' }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div v-if="isUserSignedUp" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Speel gegevens(Goede naam?)</h2>
                    <hr class="mb-4">

                    <form @submit.prevent="handleSubmit" class="mt-6 space-y-6">
                        <div>
                            <InputLabel for="user_played_time" value="Gespeelde tijd (in minuten)" />
                            <TextInput
                                id="user_played_time"
                                type="text"
                                class="block w-full"
                                v-model="form.user_played_time"
                                placeholder="Vul aantal minuten in.."
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.user_played_time" />
                        </div>

                        <div>
                            <InputLabel for="user_amount_km" value="Aantal gereden kilometers" />
                            <TextInput
                                id="user_amount_km"
                                type="text"
                                class="block w-full"
                                v-model="form.user_amount_km"
                                placeholder="Vul aantal KM's in.."
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.user_amount_km" />
                        </div>

                        <div>
                            <InputLabel for="user_feedback" value="Feedback" />

                            <TextAreaInput
                                id="user_feedback"
                                class="mt-1 block w-full"
                                v-model="form.user_feedback"
                                placeholder="Voer eventuele bijzonderheden/feedback toe.."
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


        <div v-if="isAdmin || isCoordinator || isKlant || isPenningmeester || isSecretaris" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Factuurgegevens</h2>
                    <hr class="mb-4">
                    <div class="space-y-6">

                        <!-- Factuurnummer -->
                        <div class="flex gap-6 flex-wrap lg:flex-nowrap mb-4">
                            <div class="w-full lg:w-1/2">
                                <InputLabel for="payment_mark" value="Factuurnummer" />
                                <p class="border p-2 rounded flex justify-between items-center">
                                    <span>{{ lotusRequest.payment_mark }}</span>
                                    <i :class="lastCopied === 'payment_mark' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                       @click="copyToClipboard(lotusRequest.payment_mark, 'payment_mark')"></i>
                                </p>
                            </div>

                            <!-- Tariefgroep -->
                            <div class="w-full lg:w-1/2">
                                <InputLabel for="rate_group" value="Tariefgroep" />
                                <p class="border p-2 rounded flex justify-between items-center">
                                    <span>{{ lotusRequest.rate_group }}</span>
                                    <i :class="lastCopied === 'rate_group' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                       @click="copyToClipboard(lotusRequest.rate_group, 'rate_group')"></i>
                                </p>
                            </div>
                        </div>

                        <!-- (Bedrijfs)naam -->
                        <div>
                            <InputLabel for="billing_name" value="(Bedrijfs)naam" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ billingInfo.billing_name }}</span>
                                <i :class="lastCopied === 'billing_name' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(billingInfo.billing_name, 'billing_name')"></i>
                            </p>
                        </div>

                        <!-- Factuuradres -->
                        <div>
                            <InputLabel for="billing_address" value="Factuur adres" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ billingInfo.billing_address }}</span>
                                <i :class="lastCopied === 'billing_address' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(billingInfo.billing_address, 'billing_address')"></i>
                            </p>
                        </div>

                        <!-- Factuur postcode -->
                        <div>
                            <InputLabel for="billing_zipcode" value="Factuur postcode" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ billingInfo.billing_zipcode }}</span>
                                <i :class="lastCopied === 'billing_zipcode' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(billingInfo.billing_zipcode, 'billing_zipcode')"></i>
                            </p>
                        </div>

                        <!-- Factuur plaatsnaam -->
                        <div>
                            <InputLabel for="billing_city" value="Factuur plaatsnaam" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ billingInfo.billing_city }}</span>
                                <i :class="lastCopied === 'billing_city' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(billingInfo.billing_city, 'billing_city')"></i>
                            </p>
                        </div>

                        <!-- Factuur contactpersoon -->
                        <div>
                            <InputLabel for="billing_contactperson" value="Factuur contactpersoon" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ billingInfo.billing_contactperson }}</span>
                                <i :class="lastCopied === 'billing_contactperson' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(billingInfo.billing_contactperson, 'billing_contactperson')"></i>
                            </p>
                        </div>

                        <!-- Factuur telefoonnummer -->
                        <div>
                            <InputLabel for="billing_phone" value="Factuur telefoonnummer" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ billingInfo.billing_phone }}</span>
                                <i :class="lastCopied === 'billing_phone' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(billingInfo.billing_phone, 'billing_phone')"></i>
                            </p>
                        </div>

                        <!-- Factuur email -->
                        <div>
                            <InputLabel for="billing_email" value="Factuur email" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ billingInfo.billing_email }}</span>
                                <i :class="lastCopied === 'billing_email' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(billingInfo.billing_email, 'billing_email')"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </AuthenticatedLayout>
</template>

