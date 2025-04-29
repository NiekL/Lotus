<script setup>
import {ref} from "vue";

const props = defineProps({
    lotusRequest: Object,
    billingInfo: Array,
    signedUpUsers: Array,
})

console.log(props.lotusRequest)

const lastCopied = ref(null);
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
            props.billingInfo.billing_email,
            props.lotusRequest.request_number
        ].join('\t');

        await navigator.clipboard.writeText(allBillingData);
        lastCopied.value = 'lotusRequest';
        setTimeout(() => {
            lastCopied.value = null;
        }, 2000);

    } catch (err) {
        console.error('Failed to copy all billing info:', err);
    }
};

const copyAllSignedUpUsers = async () => {

    const dateObj = new Date(props.lotusRequest.date);
    const formattedDate = dateObj.toLocaleDateString('nl-NL');


    console.log(props.signedUpUsers);
    try {
        const formattedUsers = props.signedUpUsers.map(user => {
            const pivot = user.pivot || {};

            return formattedDate + '\t'
            + props.lotusRequest.arrival_time.slice(0, 5) + ' - ' + props.lotusRequest.end_time.slice(0, 5) + '\t'
            +
            [
                user.name ?? '',
                pivot.registration_number ?? '',
                pivot.request_number ?? '',
                props.lotusRequest.customer_name ?? '',
                props.lotusRequest.payment_mark_customer ?? '',
                props.lotusRequest.name ?? '',
                (pivot.user_played_time / 60) ?? '',
                pivot.user_amount_km ?? '',
                pivot.user_expenses ?? '',
                pivot.user_feedback ?? ''
            ].join('\t');
        }).join('\n');

        await navigator.clipboard.writeText(formattedUsers);
        lastCopied.value = 'signedUpUsers';

        setTimeout(() => {
            lastCopied.value = null;
        }, 2000);
    } catch (err) {
        console.error('Failed to copy signed-up users info:', err);
    }
};

</script>


<template>
    <div class="mx-auto px-2 sm:px-6 lg:px-8">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h2 class="mb-2 text-md uppercase font-semibold">Gegevens kopiëren Penningmeester</h2>
            <hr class="mb-4">
            <p class="mb-2">Klik op de onderstaande knop op alle <strong>gegevens van de aanvraag</strong> te kopieëren</p>
            <button
                @click="copyAllBillingInfo"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                Kopieër alle aanvraag gegevens&nbsp;
                <i :class="lastCopied === 'lotusRequest' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"></i>
            </button>
            <hr class="my-4">

            <p class="mb-2 mt-4">Klik op de onderstaande knop op alle <strong>gegevens van de leden</strong> te kopieëren</p>
            <button
                @click="copyAllSignedUpUsers"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                Kopieër alle leden gegevens&nbsp;
                <i :class="lastCopied === 'signedUpUsers' ? 'fa-solid fa-check text-green-700' : 'fa-regular fa-copy'"></i>
            </button>
        </div>
    </div>
</template>
