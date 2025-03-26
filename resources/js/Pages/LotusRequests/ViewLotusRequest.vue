
<script setup>
import {onMounted, ref, computed, toRaw, watch} from 'vue';
import axios from 'axios';
import { defineProps } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    lotusRequest: Object, // Define the lotusRequest prop
    signedUpUsers: Array,
    billingInfo: Array,
    userRequestData: Object,
    nonCustomers: Object,
});

const notification = ref({ message: '', type: '' });
const formatTime = (timeString) => {
    const date = new Date(`1970-01-01T${timeString}`);
    return date.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' });
};

//Coordinator / admin functie om leden toe te voegen en te verwijderen
const usersList = computed(() => props.nonCustomers?.original?.nonCustomers || []);

const selectedUser = ref(null);
const loading = ref(false);
const errorMessage = ref(null);
const successMessage = ref(null);

var usersSignedUp = ref(props.lotusRequest.filled_spots).value;


// Functie voor aanmelden specifiek lid als coordinator of admin
const addUserToRequest = async () => {
    if (!selectedUser.value) return;
    loading.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
        const response = await axios.post(`/lotus-requests/${selectedUser.value}/singupSepecificUser`, {
            lotus_request_id: props.lotusRequest.id, // Extra parameter correct meegeven
        }, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (response.data.status === 'success') {
            successMessage.value = response.data.message;
            //Filled spots ophogen
            usersSignedUp++;
            if (response.data.user) {
                props.signedUpUsers.push(response.data.user);
            }
        } else {
            errorMessage.value = response.data.message;
        }

        selectedUser.value = null; // Reset de selectie
    } catch (error) {
        errorMessage.value = error.response?.data?.message || "Er is iets misgegaan.";
    } finally {
        loading.value = false;
    }
}

const confirmRemoveUser = (userId, userName) => {
    const confirmation = window.confirm(`Weet je zeker dat je ${userName} wilt verwijderen?`);
    usersSignedUp--;
    if (confirmation) {
        removeUserFromRequest(userId);
    }
};
const removeUserFromRequest = async (userId) => {
    loading.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
        const response = await axios.delete(`/lotus-requests/${userId}/removeUser`, {
            data: { lotus_request_id: props.lotusRequest.id } // Body van DELETE request
        });

        if (response.data.status === 'success') {
            successMessage.value = response.data.message;

            const index = props.signedUpUsers.findIndex(user => user.id === userId);
            if (index !== -1) {
                props.signedUpUsers.splice(index, 1);
            }
        } else {
            errorMessage.value = response.data.message;
        }
    } catch (error) {
        errorMessage.value = error.response?.data?.message || "Er is iets misgegaan.";
    } finally {
        loading.value = false;
    }
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
// **Controle of afmelden nog mogelijk is (minimaal 2 dagen van tevoren)**
const canUnregister = computed(() => {
    const today = new Date(); // Correct gedefinieerd binnen de computed property
    const requestDate = new Date(props.lotusRequest.date);
    const twoDaysLater = new Date();
    twoDaysLater.setDate(today.getDate() + 2);
    return requestDate > twoDaysLater;
});


// Controleer de aanmeldstatus van de gebruiker
onMounted(async () => {
    try {
        const response = await axios.get(`/lotus-requests/${props.lotusRequest.id}/check-signup`);
        isUserSignedUp.value = response.data.isUserSignedUp;
    } catch (error) {
        console.error('Fout bij het controleren van de aanmeldstatus:', error);
    }
});

//Als coordinator de aanvraag dichtzetten
const toggleClosedStatus = async () => {
    // Vragen of deze check erbij moet?
    if (usersSignedUp > props.lotusRequest.amount_lotus) {
        notification.value = { message: 'Deze aanvraag kan niet gesloten worden omdat er meer lotussen aan gekoppeld zijn dan dat er wordt gevraagd door de klant.', type: 'error' };
        window.scrollTo(0,0);
        return;
    }

    if (!window.confirm(`Wilt u deze aanvraag ${props.lotusRequest.is_closed ? 'heropenen' : 'sluiten'}?`)) return;

    try {
        const response = await axios.post(`/lotus-requests/toggle-closed/${props.lotusRequest.id}`);
        notification.value = { message: 'Aanvraagstatus succesvol gewijzigd.', type: 'success' };
        props.lotusRequest.is_closed = response.data.is_closed; // Update de status lokaal
    } catch (error) {
        notification.value = { message: 'Er is een fout opgetreden bij het wijzigen van de status.', type: 'error' };
        console.error(error);
    }
};

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
    if (!canUnregister.value) {
        notification.value = { message: 'Afmelden niet meer mogelijk, neem contact op met de coördinator.', type: 'error' };
        return;
    }

    try {
        await axios.post(`/lotus-requests/${props.lotusRequest.id}/cancel-signup`);
        notification.value = { message: 'Je hebt je succesvol afgemeld voor de aanvraag.', type: 'success' };
        isUserSignedUp.value = false;
        filledSpots.value--;
    } catch (error) {
        notification.value = {
            message: error.response?.data?.message || 'Er is een fout opgetreden bij het afmelden.',
            type: 'error'
        };
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


// Kopieer alle factuurgegevens als één string, gescheiden door een komma
const copyAllBillingInfo = async () => {
    try {
        const allBillingData = [
            props.lotusRequest.payment_mark,
            props.lotusRequest.rate_group,
            props.billingInfo.billing_name,
            props.billingInfo.billing_address,
            props.billingInfo.billing_zipcode,
            props.billingInfo.billing_city,
            props.billingInfo.billing_contactperson,
            props.billingInfo.billing_phone,
            props.billingInfo.billing_email
        ].join(', '); // Maak een string met komma's

        await navigator.clipboard.writeText(allBillingData);
        lastCopied.value = 'all';
        setTimeout(() => {
            lastCopied.value = null;
        }, 2000);
    } catch (err) {
        console.error('Failed to copy all billing info:', err);
    }
};


//Voor user feedback form
const form = ref({
    user_played_time: props.userRequestData.user_played_time ?? 0,
    user_amount_km: props.userRequestData.user_amount_km ?? '',
    user_expenses: props.userRequestData.user_expenses ?? '',
    user_feedback: props.userRequestData.user_feedback ?? '',
    errors: {}
});

const editMode = ref(false);
const lotusRequestCopy = ref({ ...props.lotusRequest });
let editMessage;


const updateLotusRequest = async () => {
    try {
        if (lotusRequestCopy.value.arrival_time) {
            lotusRequestCopy.value.arrival_time = lotusRequestCopy.value.arrival_time.substring(0, 5);
        }

        if (lotusRequestCopy.value.end_time) {
            lotusRequestCopy.value.end_time = lotusRequestCopy.value.end_time.substring(0, 5);
        }

        const response = await axios.put(`/lotus-requests/${props.lotusRequest.id}`, lotusRequestCopy.value);
        notification.value = { message: 'Aanvraag succesvol bijgewerkt.', type: 'success' };
        editMessage = response.data.editMessage;

        // Update de originele data na succesvolle bewerking
        Object.assign(props.lotusRequest, lotusRequestCopy.value);

        editMode.value = false;
    } catch (error) {
        notification.value = { message: 'Fout bij opslaan. Controleer de invoer.', type: 'error' };
        console.error(error);
    }
};

const resetEditForm = () => {
    lotusRequestCopy.value = { ...props.lotusRequest }; // Reset de kopie naar originele waarden
    editMode.value = false; // Sluit de bewerkingsmodus
};

const handleSubmit = async () => {
    console.log("Opgeslagen tijd:", form.value.user_played_time);
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

const googleMapsLink = computed(() => {
    const { streetname, housenumber, zipcode, city } = props.lotusRequest;
    const query = encodeURIComponent(`${streetname} ${housenumber}, ${zipcode} ${city}`);
    return `https://www.google.com/maps/search/?api=1&query=${query}`;
});


//User Roles
const page = usePage();
const userRoles = computed(() => page.props.auth.user?.roles || []);

const isAdmin = computed(() => userRoles.value.includes("admin"));
const isCoordinator = computed(() => userRoles.value.includes("coordinator"));
const isKlant = computed(() => userRoles.value.includes("klant"));
const isLid = computed(() => userRoles.value.includes("lid"));
const isPenningmeester = computed(() => userRoles.value.includes("penningmeester"));
const isSecretaris = computed(() => userRoles.value.includes("secretaris"));


const canEdit = computed(() => {
    const today = new Date(); // Correct gedefinieerd binnen de computed property
    const requestDate = new Date(props.lotusRequest.date);
    const offsetDaysLater = new Date();
    offsetDaysLater.setDate(today.getDate() + 2);
    return requestDate > offsetDaysLater;
});

const editButtonFunction = computed(() => {
    editMode = !editMode;
});

//Voor gespeelde uren
const selectedHours = ref(0);
const selectedMinutes = ref(0);

// **Bij het laden van de pagina: Zet de opgeslagen minuten om in uren en minuten**
onMounted(() => {
    if (form.value.user_played_time > 0) {
        selectedHours.value = Math.floor(form.value.user_played_time / 60);
        selectedMinutes.value = form.value.user_played_time % 60;
    }
});

//Voor de aangemelde gebruikers
function displayMinToNice(minutes){
    var pivotHours = Math.floor(minutes / 60);
    var pivotMinutes = minutes % 60;

    return pivotHours + " uur en " + pivotMinutes + " minuten";
}

watch([selectedHours, selectedMinutes], () => {
    // form.value.user_played_time = `${selectedHours.value}.${selectedMinutes.value}`;
    form.value.user_played_time = selectedHours.value * 60 + selectedMinutes.value;
});

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

        <div v-if="isAdmin || isLid" class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aanmeldstatus</h2>
                    <hr class="mb-4">

                    <!-- Huidige status van aanmelding -->
                    <p v-if="isUserSignedUp" class="mb-4">Je bent momenteel <strong class="text-green-600">aangemeld</strong> voor deze aanvraag.</p>
                    <p v-else class="mb-4">Je bent momenteel <strong class="text-red-600">niet aangemeld</strong> voor deze aanvraag.</p>

                    <p v-if="!canSignup" class="mb-4"><strong>Let op: </strong> Voor deze aanvraag zijn er <strong>{{props.lotusRequest.amount_lotus}}</strong> lotussen nodig. Momenteel zijn er <strong>{{props.lotusRequest.filled_spots}}</strong> aanmeldingen. De coordinator bepaalt wie er uiteindelijk mogen spelen. </p>

                    <p v-if="lotusRequest.is_closed" class="mb-4">Deze aanvraag is momenteel <strong>gesloten</strong>. Aan- of afmelden is daarom niet meer mogelijk, voor vragen neem contact op met de coordinator.</p>

                    <!-- Knoppen voor aanmelden/afmelden -->




                    <button v-if="!isUserSignedUp && !lotusRequest.is_closed " @click="signup" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 mr-4">
                        Aanmelden
                    </button>


                    <p v-if="!canUnregister && isUserSignedUp && !lotusRequest.is_closed">Afmelden niet meer mogelijk, dit moet minimaal 2 dagen van te voren. Toch afmelden, neem contact op met de coördinator.</p>
                    <button
                        v-if="isUserSignedUp && !lotusRequest.is_closed"
                        :disabled="!canUnregister"
                        @click="cancelSignup"
                        class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Afmelden
                    </button>
                </div>
            </div>
        </div>

        <div v-if="isAdmin || isCoordinator" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aanvraag {{ lotusRequest.is_closed ? 'heropenen' : 'sluiten' }}</h2>
                    <hr class="mb-4">
                    <!-- Sluiten/Heropenen Knop -->
                    <p v-if="lotusRequest.is_closed" class="mb-4">Klik hieronder om de aanvraag weer te openen.</p>
                    <p v-if="!lotusRequest.is_closed" class="mb-4">Klik hieronder om de aanvraag te sluiten. <br>Wanneer je de aanvraag sluit kunnen er geen leden meer aanmelden voor de aanvraag. Ook kan de klant geen aanpassingen meer doorvoeren. </p>

                    <button @click="toggleClosedStatus"
                            class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                        {{ lotusRequest.is_closed ? 'Heropenen' : 'Sluiten' }}
                    </button>
                </div>
            </div>
        </div>


        <div v-if="(isAdmin || isCoordinator) && !lotusRequest.is_closed" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aanvraag goedkeuren/afwijzen</h2>
                    <hr class="mb-4">
                    <p v-if="lotusRequest.status === 1" class="mb-4">Deze aanvraag is <strong>nog niet behandeld</strong>.</p>
                    <p v-if="lotusRequest.status === 2" class="mb-4">Deze aanvraag is <strong class="text-green-600">goedgekeurd</strong>. Toch afwijzen? Klik op de onderstaande knop.</p>
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



        <form @submit.prevent="updateLotusRequest">
        <div v-if="(isAdmin || isCoordinator || isKlant) && !lotusRequest.is_closed" class="pb-8" :class="{ 'pt-8': isKlant}" >
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8  shadow sm:rounded-lg" :class="{'bg-amber-50': editMode, 'bg-white': !editMode }">
                    <h2 class="mb-2 text-md uppercase font-semibold">Aanvraag bewerken</h2>
                    <hr class="mb-4">
                    <div v-if="!canEdit && isKlant" class="">
                        <p class="text-red-600">Bewerken van de aanvraag niet meer mogelijk (maximaal 2 dagen van te voren). <br> Toch wijzigingen doorvoeren? Neem contact op met de coördinator.</p>
                    </div>
                    <div v-else>
                        <p class="mb-4">Klik op de knop om de aanvraag te bewerken. De gegevens in de gele blokken zijn aan te passen. Wanneer je klaar bent klikt vervolgens op opslaan.</p>
                        <button @click="editMode = !editMode" v-if="!editMode && (isCoordinator || isAdmin || isKlant)" class=" inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" type="button">
                            {{ editMode ? 'Annuleer bewerking' : 'Bewerk aanvraag' }}
                        </button>

                        <div v-if="editMode" class="flex items-center gap-4">
                            <PrimaryButton type="submit">Opslaan</PrimaryButton>
                            <SecondaryButton @click="resetEditForm" type="button">Annuleren</SecondaryButton>
                        </div>

                        <p v-if="editMessage" class="mt-2 text-green-600">{{ editMessage }}</p>

                    </div>
                </div>
            </div>
        </div>

        <div class="pb-8" >
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div :class="{'bg-amber-50': editMode, 'bg-white': !editMode }" class="p-4 sm:p-8  shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Gegevens aanvraag</h2>
                    <hr class="mb-4">
                    <!-- Naam aanvraag -->
                    <div class="mb-4">
                        <InputLabel for="name" value="Naam bedrijf/organisatie" />
                        <TextInput id="name" type="text" v-model="lotusRequestCopy.name" class="w-full" :disabled="!editMode" required />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="description" value="Beschrijving" />
                        <TextAreaInput id="description" v-model="lotusRequestCopy.description" class="w-full" :disabled="!editMode" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="amount_lotus" value="Aantal lotusslachtoffers nodig" />
                        <TextInput id="amount_lotus" type="number" v-model="lotusRequestCopy.amount_lotus" class="w-full" min="1" :disabled="!editMode" required />
                    </div>

                    <!-- Bijzonderheden -->
                    <div class="mb-4">
                        <InputLabel for="details" value="Bijzonderheden" />
                        <TextAreaInput id="details" v-model="lotusRequestCopy.details" class="w-full" :disabled="!editMode" />
                    </div>


                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8  shadow sm:rounded-lg" :class="{'bg-amber-50': editMode, 'bg-white': !editMode }">
                    <h2 class="mb-2 text-md uppercase font-semibold">Datum, tijd en locatie</h2>
                    <hr class="mb-4">
                    <!-- Begindatum en tijd / Einddatum en tijd -->
                    <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                        <div class="w-full lg:w-1/3">
                            <InputLabel for="date" value="Datum" />
                            <TextInput id="date" type="date" v-model="lotusRequestCopy.date" class="w-full" :disabled="!editMode" required />
                        </div>

                        <div class="w-full lg:w-1/3">
                            <InputLabel for="arrival_time" value="Starttijd" />
                            <TextInput id="arrival_time" type="time" v-model.lazy="lotusRequestCopy.arrival_time" class="w-full" :disabled="!editMode" required />

                        </div>

                        <div class="w-full lg:w-1/3">
                            <InputLabel for="end_time" value="Eindtijd" />
                            <TextInput id="end_time" type="time" v-model.lazy="lotusRequestCopy.end_time" class="w-full" :disabled="!editMode" required />

                        </div>
                    </div>

                    <div class="flex gap-6 flex-wrap lg:flex-nowrap">
                        <div class="w-full lg:w-1/3">
                            <InputLabel for="street_name" value="Straatnaam" />
                            <TextInput id="street_name" type="text" v-model="lotusRequestCopy.street_name" class="w-full" :disabled="!editMode" required />
                        </div>

                        <div class="w-full lg:w-1/3">
                            <InputLabel for="house_number" value="Huisnummer" />
                            <TextInput id="house_number" type="text" v-model="lotusRequestCopy.house_number" class="w-full" :disabled="!editMode" required />
                        </div>

                        <div class="w-full lg:w-1/3">
                            <InputLabel for="zipcode" value="Postcode" />
                            <TextInput id="zipcode" type="text" v-model="lotusRequestCopy.zipcode" class="w-full" :disabled="!editMode" required />
                        </div>
                    </div>

                    <a class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :href="googleMapsLink">Open in maps</a>
                </div>
            </div>
        </div>

        <div class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8  shadow sm:rounded-lg" :class="{'bg-amber-50': editMode, 'bg-white': !editMode }">
                    <h2 class="mb-2 text-md uppercase font-semibold">Contactgegevens aanvraag</h2>
                    <hr class="mb-4">

                    <!-- Naam aanvrager / Plaats / Straatnaam / Huisnummer / Postcode -->
                    <div class="mb-4">
                        <InputLabel for="contact_person" value="Contactpersoon" />
                        <TextInput id="contact_person" type="text" v-model="lotusRequestCopy.contact_person" class="w-full" :disabled="!editMode" required />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="contact_phone" value="Telefoonnummer" />
                        <TextInput id="contact_phone" type="text" v-model="lotusRequestCopy.contact_phone" class="w-full" :disabled="!editMode" required />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="contact_email" value="E-mail" />
                        <TextInput id="contact_email" type="email" v-model="lotusRequestCopy.contact_email" class="w-full" :disabled="!editMode" required />
                    </div>

                    <input type="hidden" v-model="lotusRequestCopy.rate_group"/>


                </div>
            </div>
        </div>
        </form>

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
                                    <p v-if="!isKlant"><strong>Gespeelde tijd:</strong>{{displayMinToNice(user.pivot.user_played_time)}}</p>
                                    <p v-if="!isKlant"><strong>Gereden kilometers:</strong> {{ user.pivot.user_amount_km ?? 'Geen gegevens' }} km</p>
                                    <p v-if="isAdmin || isSecretaris"><strong>Onkosten:</strong>€ {{ user.pivot.user_expenses ?? ' Geen onkosten ingevuld' }}</p>
                                    <p v-if="!isKlant"><strong>Feedback:</strong> {{ user.pivot.user_feedback ?? 'Geen feedback' }}</p>
                                </div>
                            </div>
                            <button v-if="(isAdmin || isCoordinator) && !lotusRequest.is_closed" @click="confirmRemoveUser(user.id, user.name)"
                                    class="ml-auto rounded-md self-baseline">
                                <i class="fa-regular fa-trash-can cursor-pointer mt-1 text-red-600 hover:text-red-700 "></i>
                            </button>
                        </li>
                    </ul>

                    <div class="my-4" v-if="(isAdmin || isCoordinator) && !lotusRequest.is_closed">
                        <h2 class="mb-2 text-md uppercase font-semibold">Leden toevoegen</h2>
                        <label for="user-select" class="block text-sm font-medium text-gray-700">Selecteer een gebruiker:</label>
                        <select v-model="selectedUser" id="user-select"
                                class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="" disabled>Selecteer een gebruiker</option>
                            <option v-for="user in usersList" :key="user.id" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                        <p v-if="errorMessage" class="mt-2 text-red-600">{{ errorMessage }}</p>
                        <p v-if="successMessage" class="mt-2 text-green-600">{{ successMessage }}</p>
                        <button @click="addUserToRequest"
                                :disabled="!selectedUser || loading"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:bg-gray-400">
                            {{ loading ? 'Bezig met aanmelden...' : 'Toevoegen' }}
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <div v-if="isUserSignedUp" class="pb-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md uppercase font-semibold">Speel gegevens</h2>
                    <hr class="mb-4">

                    <form @submit.prevent="handleSubmit" class="mt-6 space-y-6">
                        <div>
                            <InputLabel for="user_played_time" value="Gespeelde tijd" />

                            <div class="flex gap-2">
                                <select v-model="selectedHours" class="block w-1/2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option v-for="hour in 24" :key="hour" :value="hour - 1">
                                        {{ hour - 1 }} uur
                                    </option>
                                </select>

                                <select v-model="selectedMinutes" class="block w-1/2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option v-for="minute in [0, 15, 30, 45]" :key="minute" :value="minute">
                                        {{ minute }} min
                                    </option>
                                </select>
                            </div>

                            <!-- Verborgen input die correct wordt bijgewerkt -->
                            <TextInput
                                id="user_played_time"
                                type="hidden"
                                v-model="form.user_played_time"
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
                            <InputLabel for="user_expenses" value="Gemaakte onkosten (in €)" />
                            <TextInput
                                id="user_expenses"
                                type="number"
                                class="block w-full"
                                v-model="form.user_expenses"
                                step="0.01"
                                min="0"
                                placeholder="Vul bedrag in.."
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.user_expenses" />
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
                                <InputLabel for="payment_mark" value="Factuurnummer van Lotus" />
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

                        <!-- Extra factuurnummer voor klant -->
                        <div v-if="lotusRequest.payment_mark_customer">
                            <InputLabel for="payment_mark_customer" value="Extra factuurnummer van klant" />
                            <p class="border p-2 rounded flex justify-between items-center">
                                <span>{{ lotusRequest.payment_mark_customer }}</span>
                                <i :class="lastCopied === 'payment_mark' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"
                                   @click="copyToClipboard(lotusRequest.payment_mark_customer, 'payment_mark')"></i>
                            </p>
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

                        <!-- Alle gegevens -->
                        <button
                            @click="copyAllBillingInfo"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                            Kopieer alle gegevens&nbsp;
                            <i :class="lastCopied === 'all' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"></i>
                        </button>

                    </div>
                </div>
            </div>
        </div>




    </AuthenticatedLayout>
</template>

