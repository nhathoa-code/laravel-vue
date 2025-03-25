<script>
import { computed } from "vue";
import { useRouter } from "vue-router";
export default {
    name: "tab-items",
    props: {
        items: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const router = useRouter();
        const currentRoute = computed(() => router.currentRoute.value.path);
        return {
            items: props.items,
            currentRoute,
        };
    },
};
</script>
<template>
    <div id="finesholdsissues" class="toptabs">
        <ul class="nav nav-tabs" role="tablist">
            <li
                :class="{ active: currentRoute == item.route }"
                v-for="(item, index) in items"
                :key="index"
            >
                <RouterLink
                    :to="item.route"
                    role="tab"
                    aria-controls="overview"
                    aria-selected="true"
                    >{{ item.name }}
                </RouterLink>
            </li>
        </ul>
        <div class="tab-content">
            <slot></slot>
        </div>
    </div>
</template>
<style scoped>
.toptabs {
    margin: 1rem 0;
}
.nav-tabs > li > a {
    background-color: #408540;
    border: 2px solid #408540;
    border-bottom: 0;
    border-radius: 0;
    color: #fff;
    font-weight: bold;
    line-height: 1.3;
    margin-right: 0.4em;
    padding: 0.5em 1em;
}
.nav-tabs {
    border-bottom: 0;
    padding: 0.2em 1.4em 0 0;
}
.tab-content {
    background-color: #fff;
    border: 0;
    border-radius: 0;
    padding: 1em;
    margin-top: 3px;
}
.nav-tabs > li.active a {
    background-color: #fff;
    border: 2px solid #fff;
    color: #111;
    cursor: default;
    padding: 0.5em 1em;
}
</style>
