<script>
import { useTemplateRef, ref, onMounted, onUnmounted } from "vue";
import { RouterLink } from "vue-router";
export default {
    name: "category-item",
    props: {
        route: {
            type: [Object, String],
            default: null,
        },
        name: {
            type: String,
            required: true,
        },
        icon: {
            type: String,
            default: null,
        },
        dropdown: {
            type: Array,
            default: null,
        },
    },
    setup() {
        const open = ref(false);
        const button = useTemplateRef("btn");

        const handleClickOutside = (event) => {
            if (button.value && !button.value.contains(event.target)) {
                open.value = false;
            }
        };

        onMounted(() => {
            document.addEventListener("click", handleClickOutside);
        });

        onUnmounted(() => {
            document.removeEventListener("click", handleClickOutside);
        });

        return {
            open,
        };
    },
    methods: {
        openDropdown() {
            this.open = !this.open;
        },
    },
    inheritAttrs: false,
};
</script>
<template>
    <div
        ref="btn"
        @click="open = !open"
        :class="{ open: open }"
        class="btn-group"
    >
        <RouterLink v-if="route" :to="route">
            <button
                :class="{ 'dropdown-toggle': dropdown }"
                class="btn btn-default"
            >
                <i v-if="icon" :class="['fa', icon]"></i> {{ name
                }}<span class="caret"></span>
            </button>
        </RouterLink>
        <button
            v-else
            v-bind="$attrs"
            :class="{ 'dropdown-toggle': dropdown }"
            class="btn btn-default"
        >
            <i v-if="icon" :class="['fa', icon]"></i> {{ name
            }}<span class="caret"></span>
        </button>
        <ul v-if="dropdown" class="dropdown-menu">
            <template v-for="item in dropdown">
                <li
                    v-if="
                        item?.route &&
                        (typeof item.route == 'object' ||
                            typeof item.route == 'string')
                    "
                >
                    <RouterLink :to="item.route">{{ item.name }} </RouterLink>
                </li>
                <li v-else-if="typeof item == 'string'" class="dropdown-header">
                    {{ item }}
                </li>
                <li v-else-if="item">
                    <a @click.prevent="item.command()" href="#">{{
                        item.name
                    }}</a>
                </li>
                <li v-else role="separator" class="divider"></li>
            </template>
        </ul>
    </div>
</template>
<style scoped>
.dropdown-menu {
    padding-top: 5px;
}
.dropdown-menu .divider {
    height: 1px;
    margin: 7.5px 0;
    overflow: hidden;
    background-color: #e5e5e5;
}
.dropdown-header {
    color: #000;
    font-weight: bold;
    margin-top: 5px;
    padding-left: 10px;
    display: block;
    padding: 3px 15px;
    font-size: 12px;
    line-height: 1.42857143;
    white-space: nowrap;
}
.dropdown-menu > li:not(.dropdown-header) > a {
    /* display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap; */
    font-size: 12px;
    padding-left: 25px;
}
</style>
