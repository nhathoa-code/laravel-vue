<script>
import { inject } from "vue";
export default {
    name: "option-item",
    props: {
        item: {
            type: Object,
            required: true,
        },
        level: {
            type: Number,
            default: 1,
        },
        isSelected: {
            type: Boolean,
            default: false,
        },
        isEdit: {
            type: Boolean,
            default: false,
        },
    },
    setup(props) {
        const editedCategory = inject("editedCategory");
        const selectedParent = inject("selectedParent");
        if (props.isEdit) {
            if (editedCategory.value.parent_id == props.item.id) {
                selectedParent.value = props.item;
            }
        }
        return {
            editedCategory,
        };
    },
};
</script>
<template>
    <option :disabled="isEdit && item.id == editedCategory.id" :value="item">
        {{ level > 1 ? "-".repeat(level) + " " : "" }}
        {{ item.name }}
    </option>
    <option-item
        v-if="item.children"
        v-for="child in item.children"
        :key="child.id"
        :item="child"
        :level="level + 1"
        :isEdit="isEdit"
    ></option-item>
</template>
