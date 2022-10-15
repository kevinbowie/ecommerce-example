<script setup>
import { ref, watch } from "vue";
import Card from "@/admin/Components/Card.vue";
import Input from "@admin/Components/Input.vue";
import Label from "@admin/Components/Label.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        default: {},
    },
});

const emits = defineEmits("update:modelValue");

const filters = ref({ ...props.modelValue });

watch(
    filters,
    () => {
        emits("update:modelValue", filters.value);
    },
    { deep: true }
);
</script>

<template>
    <Card class="mt-4">
        <template #header>Filters</template>
        <form class="grid grid-cols-4 gap-8">
            <div>
                <Label value="Name" />
                <Input
                    type="text"
                    class="mt-1 block w-full"
                    v-model="filters.name"
                />
            </div>
        </form>
    </Card>
</template>
