<script setup>
import Container from "@admin/Components/Container.vue";
import BreezeAuthenticatedLayout from "@admin/Layouts/Authenticated.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { onMounted } from "vue";
import Card from "@/admin/Components/Card.vue";
import Button from "@admin/Components/Button.vue";
import BreezeInput from "@admin/Components/Input.vue";
import BreezeLabel from "@admin/Components/Label.vue";
import InputError from "@admin/Components/InputError.vue";

const props = defineProps({
    edit: {
        type: Boolean,
        default: false,
    },
    item: {
        type: Object,
        default: {},
    },
    title: {
        type: String,
    },
    routeResourceName: {
        type: String,
        required: true,
    },
});

const form = useForm({
    name: "",
});

const submit = () => {
    props.edit
        ? form.put(
              route(`admin.${props.routeResourceName}.update`, {
                  id: props.item.id,
              })
          )
        : form.post(route(`admin.${props.routeResourceName}.store`));
};

onMounted(() => {
    if (props.edit) {
        form.name = props.item.name;
    }
});
</script>

<template>
    <Head :title="title" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ title }}
            </h2>
        </template>

        <Container>
            <Card>
                <form @submit.prevent="submit">
                    <div>
                        <BreezeLabel value="Name" />
                        <BreezeInput
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                        />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <Button :disabled="form.processing">
                            {{ form.processing ? "Saving..." : "Save" }}</Button
                        >
                    </div>
                </form>
            </Card>
        </Container>
    </BreezeAuthenticatedLayout>
</template>
