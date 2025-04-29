<script setup>
import { ref } from 'vue'
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

const currentMonth = ref(props.selectedMonth)
const currentYear = ref(props.selectedYear)
const lastCopied = ref(null)

const updateTimeline = () => {
    router.get(route('lotus-requests.viewtimeline'), {
        month: currentMonth.value,
        year: currentYear.value
    })
}


const copyRequestInfo = async (request) => {
    console.log(request)
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
            request.request_number
        ].join('\t');

        await navigator.clipboard.writeText(info);
        lastCopied.value = 'request-' + request.id;
        setTimeout(() => lastCopied.value = null, 2000);
    } catch (err) {
        console.error('Copy failed:', err);
    }
}

const copyUserInfo = async (request) => {
    try {
        const date = new Date(request.date).toLocaleDateString('nl-NL');
        const info = request.users.map(user => {
            const p = user.pivot ?? {};
            return `${date}\t${request.arrival_time.slice(0, 5)} - ${request.end_time.slice(0, 5)}\t${[
                user.name,
                p.registration_number ?? '',
                p.request_number ?? '',
                request.customer_name ?? '',
                request.payment_mark ?? '',
                request.name,
                (p.user_played_time / 60) ?? '',
                p.user_amount_km ?? '',
                p.user_expenses ?? '',
                p.user_feedback ?? ''
            ].join('\t')}`;
        }).join('\n');

        await navigator.clipboard.writeText(info);
        lastCopied.value = 'users-' + request.id;
        setTimeout(() => lastCopied.value = null, 2000);
    } catch (err) {
        console.error('Copy failed:', err);
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
            </div>

            <div v-if="lotusRequests.length === 0" class="text-gray-600">Geen Lotus-aanvragen gevonden voor deze maand.</div>

            <div v-else class="space-y-6">
                <div v-for="request in lotusRequests" :key="request.id" class="bg-white p-4 rounded shadow border">

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2">
                        <Link :href="route('lotus-requests.viewlotusrequest', { id: request.id })" class="text-lg font-semibold text-red-700 hover:underline">
                            {{ new Date(request.date).toLocaleDateString('nl-NL') }} - {{ request.name }}
                        </Link>
                        <div class="flex gap-2 mt-2 sm:mt-0 flex-wrap">
                            <button @click="copyRequestInfo(request)" class="text-sm bg-blue-100 hover:bg-blue-200 px-2 py-1 rounded">
                                📋 Kopieer aanvraag
                            </button>
                            <button @click="copyUserInfo(request)" class="text-sm bg-green-100 hover:bg-green-200 px-2 py-1 rounded">
                                👥 Kopieer leden
                            </button>
                            <span v-if="lastCopied === 'request-' + request.id || lastCopied === 'users-' + request.id" class="text-green-600 text-xs self-center">
                                Gekopieerd!
                            </span>
                        </div>
                    </div>

                    <p class="">{{ request.customer.name }}</p>
                    <p class="text-sm text-gray-600">{{ request.city }} – {{ request.arrival_time }} tot {{ request.end_time }}</p>
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
