<script>
import { computed } from "vue";
import { useRouter } from "vue-router";
export default {
    name: "menu-items",
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
    <ul class="nav nav-tabs" role="tablist">
        <li
            :class="{ active: currentRoute == item.route }"
            v-for="(item, index) in items"
            :key="index"
            class="nav-item"
        >
            <RouterLink
                :class="{
                    'ps-0': index === 0,
                    'border-0': index === items.length - 1,
                    'me-3': index !== items.length - 1,
                    active: currentRoute === item.route,
                }"
                class="nav-link"
                id="home-tab"
                :to="item.route"
                role="tab"
                aria-controls="overview"
                aria-selected="true"
            >
                <span v-html="item.icon"></span> {{ item.name }}
            </RouterLink>
        </li>
    </ul>
</template>
<style scoped>
.home-tab .nav-tabs .nav-item .nav-link {
    border-right: 0px;
}
i.fa {
    margin-right: 2px;
}
.nav-tabs > li > a {
    background-color: #e0e0e0 !important;
    border-radius: 6px !important;
    color: #101010 !important;
    display: block !important;
    font-size: 110% !important;
    font-weight: bold !important;
    padding: 10px 20px !important;
}

.nav-tabs > li > a:hover {
    background-color: #408540 !important;
    color: #fff !important;
}

.nav-tabs > li > a.active {
    background-color: #408540 !important;
    color: #fff !important;
}
</style>
