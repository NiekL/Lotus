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
                <Link href="/"
                      class="flex flex-row items-center gap-2">
                    <ApplicationLogo
                        class="block h-9 w-auto fill-current text-gray-800"
                    />
                </Link>
            </div>
        </div>


        <div
            class="w-full sm:max-w-md mt-6 px-2 py-1 sm:py-2 lg:py-4  overflow-hidden sm:rounded-lg"
        >
            <div class="mx-auto px-2 sm:px-6 lg:px-8 p-4 sm:p-8 bg-white shadow rounded-lg">
                <slot />
            </div>
        </div>



        <NoAccountYet  v-if="noAccountYet"></NoAccountYet>


    </div>
</template>
