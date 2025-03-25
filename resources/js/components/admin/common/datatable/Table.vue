<script setup>
import { RouterLink } from "vue-router";
defineProps({
    data: {
        type: Array,
        required: true,
    },
    columns: {
        type: Object,
        required: true,
    },
});
function getColumnValue(obj, path) {
    return path.split(".").reduce((acc, part) => acc[part] ?? null, obj);
}
function evaluateTo(str, obj) {
    const parts = [...str.matchAll(/\{(.*?)\}/g)].map((m) => m[1]);
    parts.forEach((item) => {
        str = str.replace(`{${item}}`, this.getColumnValue(obj, item));
    });
    return str;
}
</script>
<template>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th v-for="(value, key) in columns">
                    {{ typeof value === "object" ? value.name : value }}
                </th>
                <th style="width: 20%">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in data" :key="item.id">
                <td v-for="(value, key) in columns">
                    <template
                        v-if="
                            typeof value === 'object' &&
                            value.hasOwnProperty('route') &&
                            value.hasOwnProperty('to')
                        "
                    >
                        <RouterLink :to="evaluateTo(value.to, item)">
                            {{ getColumnValue(item, key) }}
                        </RouterLink>
                    </template>
                    <template v-else>
                        {{ getColumnValue(item, key) }}
                    </template>
                </td>
                <td>
                    <slot name="actions" :item="item"></slot>
                </td>
            </tr>
        </tbody>
    </table>
</template>
