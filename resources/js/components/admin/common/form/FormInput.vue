<script setup>
import { Field, ErrorMessage } from "vee-validate";
defineProps({
    name: { type: String, default: "" },
    label: String,
    labelInline: { type: Boolean, default: true },
    type: { type: String, default: "text" },
    class: { type: String, default: "form-control" },
    red: { type: Boolean, default: false },
    options: { type: Array, default: [] },
    group: { type: Boolean, default: true },
});
</script>
<template>
    <!-- class="col-sm-3 col-form-label" -->
    <div class="form-group" :class="{ row: labelInline }">
        <label
            v-if="label"
            :for="name"
            :class="{ 'col-sm-3 col-form-label': labelInline, required: red }"
            >{{ label }}</label
        >
        <div :class="{ 'col-sm-9': label && labelInline }">
            <Field
                v-if="['text', 'number', 'password'].includes(type)"
                :name="name"
                :type="type"
                class="form-control"
            />
            <Field
                v-else-if="type == 'select'"
                :name="name"
                :as="type"
                class="form-control"
            >
                <slot></slot>
                <!-- <option selected value="">none</option> -->
                <!-- <option :value="item.id" v-for="item in options">
                    {{ item.name }}
                </option> -->
            </Field>
            <Field v-else :name="name" :as="type" />
            <ErrorMessage :name="name" as="p" class="text-danger" />
        </div>
    </div>
</template>
