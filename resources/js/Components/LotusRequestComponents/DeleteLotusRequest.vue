<template>
    <button
        @click="confirmDelete"
        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
    >
        Verwijder aanvraag
    </button>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    requestId: {
        type: Number,
        required: true,
    },
})

const confirmDelete = () => {
    if (confirm('Weet je zeker dat je deze aanvraag wilt verwijderen?')) {
        router.delete(route('lotus-requests.destroy', props.requestId), {
            onSuccess: () => {
                alert('Aanvraag succesvol verwijderd.')
            },
            onError: (errors) => {
                console.error(errors)
                alert('Er is een fout opgetreden bij het verwijderen van de aanvraag.')
            },
        })
    }
}
</script>
