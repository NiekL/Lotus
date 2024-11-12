<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>

<template>
    <div
        class="w-full sm:max-w-md mt-6 px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
    >
        <p><strong>Nog geen account?</strong></p>
        <hr class="my-2">
        <p class="mb-2">Maak een account aan om een lotusaanvraag in te dienen.</p>
        <PrimaryButton>
            <a
                :href="route('register')"
            >Registreer hier</a>

        </PrimaryButton>
    </div>

</template>

<style scoped>

</style>
