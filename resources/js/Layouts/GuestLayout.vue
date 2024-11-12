<script setup>
import { computed } from 'vue';
import "https://kit.fontawesome.com/bf86a3d082.js";
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import NoAccountYet from "@/Pages/Auth/NoAccountYet.vue";


defineProps({
    noAccountYet: {
        type: Boolean,
    }
});

</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 to-red-100">

        <div>
            <div class="shrink-0 flex items-center">
                <Link href="/">
                    <ApplicationLogo
                        class="block h-9 w-auto fill-current text-gray-800"
                    />
                </Link>
            </div>
        </div>


        <div
            class="w-full sm:max-w-md mt-6 px-2 py-1 sm:px-4 sm:py-2 lg:px-6 lg:py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>


        <NoAccountYet  v-if="noAccountYet"></NoAccountYet>


    </div>
</template>
