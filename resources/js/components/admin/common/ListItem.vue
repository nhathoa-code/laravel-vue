<script>
import { ref } from "vue";
import { inject } from "vue";
export default {
    name: "list-item",
    props: {
        item: {
            type: Object,
            required: true,
        },
        index: {
            type: Number,
            required: true,
        },
        parent: {
            type: String,
            required: false,
            default: null,
        },
    },
    setup(props) {
        const isExpanded = ref(false);
        const isEdit = inject("isEdit");
        const editedCategory = inject("editedCategory");
        return {
            isExpanded,
            isEdit,
            editedCategory,
        };
    },
    methods: {
        toggle() {
            this.isExpanded = !this.isExpanded;
        },
        editCategory(item, index, parent) {
            this.isEdit = true;
            this.editedCategory = item;
            this.editedCategory.index = index;
            this.editedCategory.parent = parent;
        },
    },
};
</script>
<template>
    <li>
        <span class="d-flex align-items-center">
            <div class="flex-grow-1" style="cursor: pointer" @click="toggle">
                <span
                    v-if="item.children && item.children.length > 0"
                    class="me-2"
                >
                    <i v-if="isExpanded" class="fa fa-minus"></i>
                    <i v-else class="fa fa-plus"></i>
                </span>
                <span>{{ item.name }}</span>
            </div>
            <i
                @click="editCategory(item, index, parent)"
                class="fa fa-pencil d-none"
            ></i>
        </span>
        <ul v-if="isExpanded && item.children">
            <list-item
                v-for="(child, index) in item.children"
                :key="child.id"
                :item="child"
                :index="index"
                :parent="parent ? parent + '-' + item.id : `${item.id}`"
            ></list-item>
        </ul>
    </li>
</template>
<style scoped>
ul {
    padding-left: 2rem;
}
ul li {
    list-style-type: none;
    font-weight: 500;
    font-size: 14px;
}
ul li > span:hover > i.fa {
    display: block !important;
}
.fa {
    cursor: pointer;
}
</style>
