<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'sidebar-item inline-flex items-center text-sm font-medium leading-5 text-red-600 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'sidebar-item inline-flex items-center text-sm font-medium leading-5 text-gray-900 hover:text-red-600 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
