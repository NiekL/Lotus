<script setup>
import { ref, computed } from 'vue'
import { Head, router, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    lotusRequests: Array,
    selectedMonth: String,
    selectedYear: String,
})

const months = [
    { label: 'Januari', value: '01' }, { label: 'Februari', value: '02' }, { label: 'Maart', value: '03' },
    { label: 'April', value: '04' }, { label: 'Mei', value: '05' }, { label: 'Juni', value: '06' },
    { label: 'Juli', value: '07' }, { label: 'Augustus', value: '08' }, { label: 'September', value: '09' },
    { label: 'Oktober', value: '10' }, { label: 'November', value: '11' }, { label: 'December', value: '12' },
]

const currentMonthLabel = computed(() => {
    const month = months.find(m => m.value === currentMonth.value)
    return month ? month.label : 'Onbekend'
})

const currentMonth = ref(props.selectedMonth)
const currentYear = ref(props.selectedYear)
const lastCopied = ref(null)

const hasOpenRequests = computed(() => {
    return props.lotusRequests.some(request => request.is_closed === false)
});
const notifyIfOpenRequests = () => {
    if (hasOpenRequests.value) {
        alert('Let op: Niet alle aanvragen zijn gesloten voor de geselecteerde maand. Dit kan betekenen dat niet alle informatie compleet is of dat er voor sommige aanvragen nog geen registratienummer is gegenereerd.');
    }
}


const updateTimeline = () => {
    router.get(route('lotus-requests.viewtimeline'), {
        month: currentMonth.value,
        year: currentYear.value
    })
}
const copySingleRequestInfo = async (request) => {
    try {
        const info = [
            request.payment_mark,
            request.rate_group,
            request.customer.name,
            request.customer.billing_info?.billing_address,
            request.customer.billing_info?.billing_zipcode,
            request.customer.billing_info?.billing_city,
            request.customer.billing_info?.billing_contact_person,
            request.customer.billing_info?.billing_contact_phone,
            request.customer.billing_info?.billing_contact_email,
            request.request_number.toString().padStart(5, '0'),
        ].join('\t');

        await navigator.clipboard.writeText(info);
        lastCopied.value = 'request-' + request.id;
        setTimeout(() => lastCopied.value = null, 2000);
    } catch (err) {
        console.error('Copy failed:', err);
    }
}

const copySingleUserInfo = async (request) => {
    try {
        const date = new Date(request.date).toLocaleDateString('nl-NL');
        const info = request.users.map(user => {
            const p = user.pivot ?? {};
            return [
                date,
                `${request.arrival_time.slice(0, 5)} - ${request.end_time.slice(0, 5)}`,
                p.registration_number ?? '',
                user.name,
                (p.request_number ?? '').toString().padStart(5, '0'),
                request.customer.name ?? '',
                request.name ?? '',
                // request.payment_mark ?? '',
                (p.user_played_time / 60).toLocaleString('nl-NL', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) ?? '0',
                p.user_amount_km ?? '0',
                (p.user_expenses ?? 0).toLocaleString('nl-NL', {minimumFractionDigits: 2, maximumFractionDigits: 2}) ?? '0,00',
                p.user_feedback ?? '',
                request.payment_mark ?? '',
                request.payment_mark_customer ?? 'nvt',
            ].join('\t');
        }).join('\n');

        await navigator.clipboard.writeText(info);
        lastCopied.value = 'users-' + request.id;
        setTimeout(() => lastCopied.value = null, 2000);
    } catch (err) {
        console.error('Copy failed:', err);
    }
}

const copyAllRequestInfo = async () => {
    notifyIfOpenRequests();
    try {
        const info = props.lotusRequests.map(request => {
            return [
                request.payment_mark,
                request.rate_group,
                request.customer.name,
                request.customer.billing_info?.billing_address,
                request.customer.billing_info?.billing_zipcode,
                request.customer.billing_info?.billing_city,
                request.customer.billing_info?.billing_contactperson,
                request.customer.billing_info?.billing_phone,
                request.customer.billing_info?.billing_email,
                request.request_number.toString().padStart(5, '0'),
            ].join('\t');
        }).join('\n');

        await navigator.clipboard.writeText(info);
        lastCopied.value = 'all-requests';
        setTimeout(() => lastCopied.value = null, 2000);
    } catch (err) {
        console.error('Copy all request info failed:', err);
    }
}

const copyAllUserInfo = async () => {
    console.log(props.lotusRequests);
    notifyIfOpenRequests();
    try {
        const info = props.lotusRequests.flatMap(request => {
            const date = new Date(request.date).toLocaleDateString('nl-NL');
            return request.users.map(user => {
                const p = user.pivot ?? {};
                return [
                    date,
                    `${request.arrival_time.slice(0, 5)} - ${request.end_time.slice(0, 5)}`,
                    p.registration_number ?? '',
                    user.name,
                    (p.request_number ?? '').toString().padStart(5, '0'),
                    request.customer.name ?? '',
                    request.name ?? '',
                    // request.payment_mark ?? '',
                    (p.user_played_time / 60).toLocaleString('nl-NL', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) ?? '0',
                    p.user_amount_km ?? '0',
                    (p.user_expenses ?? 0).toLocaleString('nl-NL', {minimumFractionDigits: 2, maximumFractionDigits: 2}) ?? '0,00',
                    p.user_feedback ?? '',
                    request.payment_mark ?? '',
                    request.payment_mark_customer ?? 'nvt',
                ].join('\t');
            });
        }).join('\n');

        await navigator.clipboard.writeText(info);
        lastCopied.value = 'all-users';
        setTimeout(() => lastCopied.value = null, 2000);
    } catch (err) {
        console.error('Copy all user info failed:', err);
    }
}

</script>

<template>
    <Head title="Tijdlijn" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bekijk tijdlijn</h2>
        </template>




        <div class="py-6 px-4">
            <div class="bg-white p-4 rounded shadow mb-4">
                <div class="flex flex-wrap gap-2 items-center">
                    <select v-model="currentMonth" @change="updateTimeline" class="border rounded p-2">
                        <option v-for="month in months" :key="month.value" :value="month.value">{{ month.label }}</option>
                    </select>
                    <input type="number" v-model="currentYear" @change="updateTimeline" class="border rounded p-2 w-24" min="2000" max="2100" />
                </div>

                <div class="flex flex-wrap gap-2 my-4">
                    <button @click="copyAllRequestInfo" class="text-sm bg-blue-100 hover:bg-blue-200 px-2 py-1 rounded">
                        📋 Kopieer alle aanvraag info van {{ currentMonthLabel }}
                    </button>
                    <button @click="copyAllUserInfo" class="text-sm bg-green-100 hover:bg-green-200 px-2 py-1 rounded">
                        👥 Kopieer alle leden info van {{ currentMonthLabel }}
                    </button>
                    <span v-if="lastCopied === 'all-requests' || lastCopied === 'all-users'" class="text-green-600 text-xs self-center">
        Alles gekopieerd!
            </span>
                </div>
            </div>

            <div v-if="hasOpenRequests" class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded mb-4 border border-yellow-300">
                <p>Let op: Nog niet alle aanvragen zijn gesloten voor de maand {{ currentMonthLabel }}. Dit kan betekenen dat niet alle informatie compleet is of dat er voor sommige aanvragen nog geen registratienummer is gegenereerd. De aanvragen die nog niet gesloten zijn hebben een rode achtergrond. </p>
            </div>

            <div v-if="lotusRequests.length === 0" class="text-gray-600">Geen Lotus-aanvragen gevonden voor deze maand.</div>

            <div v-else class="space-y-6">
                <div
                    v-for="request in lotusRequests"
                    :key="request.id"
                    :class="[
                            'p-4 rounded shadow mb-2',
                            request.is_closed === false ? 'bg-red-50' : 'bg-white'
                          ]"
                >

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2">
                        <Link :href="route('lotus-requests.viewlotusrequest', { id: request.id })" class="text-lg font-semibold text-red-700 hover:underline">
                            {{ new Date(request.date).toLocaleDateString('nl-NL') }} - {{ request.name }}
                        </Link>
                        <div class="flex gap-2 mt-2 sm:mt-0 flex-wrap">
                            <button @click="copySingleRequestInfo(request)" class="text-sm bg-blue-100 hover:bg-blue-200 px-2 py-1 rounded">
                                📋 Kopieer aanvraag
                            </button>
                            <button @click="copySingleUserInfo(request)" class="text-sm bg-green-100 hover:bg-green-200 px-2 py-1 rounded">
                                👥 Kopieer leden
                            </button>
                            <span v-if="lastCopied === 'request-' + request.id || lastCopied === 'users-' + request.id" class="text-green-600 text-xs self-center">
                                Gekopieerd!
                            </span>
                        </div>
                    </div>

                    <p class="">Klant: {{ request.customer.name }}</p>
                    <p class="text-sm text-gray-600">Status: {{ request.is_closed ? 'Gesloten' : 'Open' }}</p>
                    <p class="text-sm text-gray-600">Locatie: {{ request.city }} – {{ request.arrival_time }} tot {{ request.end_time }}</p>
<!--                    <p class="text-sm italic mb-2">{{ request.description }}</p>-->

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-300">
                            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr class="text-left">
                                <th class="px-2 py-1">Naam</th>
                                <th class="px-2 py-1">Speeltijd</th>
                                <th class="px-2 py-1">Km</th>
                                <th class="px-2 py-1">Onkosten</th>
                                <th class="px-2 py-1">Feedback</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in request.users" :key="user.id" class="border-t">
                                <td class="px-2 py-1 whitespace-nowrap">{{ user.name }}</td>
                                <td class="px-2 py-1">{{ user.pivot.user_played_time ? `${user.pivot.user_played_time} min (${(user.pivot.user_played_time / 60).toFixed(1)} uur)` : 'nvt' }}</td>
                                <td class="px-2 py-1">{{ user.pivot.user_amount_km ?? 'nvt' }}</td>
                                <td class="px-2 py-1">{{ user.pivot.user_expenses !== null ? `€${user.pivot.user_expenses}` : 'nvt' }}</td>
                                <td class="px-2 py-1">{{ user.pivot.user_feedback ?? 'nvt' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
