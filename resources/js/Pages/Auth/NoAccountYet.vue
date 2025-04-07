<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>

<template>
    <div
        class="w-full sm:max-w-md px-2 py-1 sm:py-2 lg:py-4  overflow-hidden"
    >
        <div class="mx-auto px-2 sm:px-6 lg:px-8 p-4 sm:p-8 bg-white shadow rounded-lg">
        <p><strong>Nog geen account? Registreer als klant.</strong></p>
        <hr class="my-2">
        <p class="mb-2">Maak een account aan om een lotusaanvraag in te kunnen dienen.</p>
        <PrimaryButton>
            <a
                :href="route('register')"
            >Registreer hier als klant</a>

        </PrimaryButton>
        </div>
    </div>

</template>

<style scoped>

</style>
