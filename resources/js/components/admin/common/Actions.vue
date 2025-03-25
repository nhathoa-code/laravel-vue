<script>
import { ref } from "vue";
export default {
    name: "actions",
    setup() {
        const isOpen = ref(false);
        return {
            isOpen,
        };
    },
    methods: {
        toggle() {
            this.isOpen = !this.isOpen;
        },
    },
};
</script>
<template>
    <div class="btn-group dropup" :class="{ open: isOpen }">
        <a
            class="btn btn-default btn-xs dropdown-toggle"
            id="invoiceactions82"
            role="button"
            data-toggle="dropdown"
            href="#"
            @click.prevent="toggle"
            aria-expanded="false"
        >
            Actions <b class="caret"></b>
        </a>
        <ul
            class="dropdown-menu pull-right"
            role="menu"
            aria-labelledby="invoiceactions82"
        >
            <slot></slot>
        </ul>
    </div>
</template>
