<script setup>
import Label from "@admin/Components/Label.vue";
import Select from "@admin/Components/Select.vue";
import InputError from "@admin/Components/InputError.vue";

defineProps({
    modelValue: {},
    items: Array,
    itemText: {
        type: String,
        default: "name",
    },
    itemValue: {
        type: String,
        default: "id",
    },
    withoutSelect: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: "",
    },
    errorMessage: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <div>
        <Label v-if="label" :value="label" />
        <Select
            class="mt-1"
            v-bind="$attrs"
            :model-value="modelValue"
            :items="items"
            :item-text="itemText"
            :item-value="itemValue"
            :without-select="withoutSelect"
            @update:model-value="$emit('update:modelValue', $event)"
        />
        <InputError v-if="errorMessage" class="mt-1" :message="errorMessage" />
    </div>
</template>
