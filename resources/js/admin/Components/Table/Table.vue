<script setup>
import Th from "./Th.vue";
import Td from "./Td.vue";

defineProps({
    headers: {
        type: Array,
        default: () => [],
    },

    items: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-b">
                        <tr>
                            <Th
                                v-for="header in headers"
                                :keys="header.label"
                                >{{ header.label }}</Th
                            >
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="border-b"
                            v-for="item in items.data"
                            :key="item.id"
                        >
                            <slot :item="item" />
                        </tr>
                        <tr v-if="items.data.length === 0">
                            <Td :colspan="headers.length"
                                >No data available.</Td
                            >
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
