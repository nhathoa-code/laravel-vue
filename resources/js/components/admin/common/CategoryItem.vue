<script>
import { ref } from "vue";
export default {
    name: "category-item",
    props: {
        item: {
            type: Object,
            required: true,
        },
        index: {
            type: Number,
            required: true,
        },
        check: {
            type: Boolean,
            default: false,
        },
        categories: {
            type: Array,
            default: [],
        },
    },
    setup(props) {
        const isChecked = ref(
            props.categories.includes(props.item.id) ? true : props.check
        );
        const categories = ref(props.categories);
        return {
            isChecked,
            categories,
        };
    },
    methods: {
        toggle() {
            this.isChecked = !this.isChecked;
        },
    },
};
</script>
<template>
    <li>
        <div class="form-check form-check-primary">
            <label class="form-check-label">
                <input
                    type="checkbox"
                    :checked="isChecked"
                    class="form-check-input"
                    name="cats"
                    :value="item.id"
                    @change="toggle"
                />
                {{ item.name }} <i class="input-helper"></i>
            </label>
        </div>
        <ul v-if="isChecked && item.children">
            <category-item
                v-for="(child, index) in item.children"
                :key="child.id"
                :item="child"
                :index="index"
                :categories="categories"
            ></category-item>
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
