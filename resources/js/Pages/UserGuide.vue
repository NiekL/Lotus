<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

</script>

<template>
    <Head title="Declaratie info" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Handleidingen</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="mb-2 text-md font-semibold uppercase">Handleidingen</h2>
                    <hr class="mb-4">
                    <p>De handleidingen voor de klanten en leden zijn te vinden door op de onderstaande buttons te klikken:</p>
                    <br>

                    <div class="space-y-2">
                        <a
                            href="https://lotuszwolle.nl/wp-content/uploads/2025/04/Handleiding-Lotus-Planner-Klant-V1.1.0.pdf"
                            target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Handleiding voor Klanten
                        </a><br><br>
                        <a
                            href="https://lotuszwolle.nl/wp-content/uploads/2025/04/Handleiding-Lotus-Planner-Lid-V1.1.0.pdf"
                            target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Handleiding voor Leden
                        </a>
                    </div>
                </div>
            </div>
        </div>




    </AuthenticatedLayout>
</template>
