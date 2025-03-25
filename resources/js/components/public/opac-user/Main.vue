<script setup>
import { computed } from "vue";
import { useRouter, RouterLink } from "vue-router";
const router = useRouter();
const currentRoute = computed(() => {
    return router.currentRoute.value.path;
});
</script>
<template>
    <div class="row">
        <div class="col col-lg-2 order-2 order-lg-1 pe-0">
            <div id="navigation">
                <div id="usermenu">
                    <div id="menu">
                        <ul>
                            <li
                                :class="{
                                    active:
                                        currentRoute == '/opac-user/summary',
                                }"
                            >
                                <RouterLink :to="{ name: 'opac-user-summary' }"
                                    >Summary</RouterLink
                                >
                            </li>

                            <li
                                :class="{
                                    active:
                                        currentRoute == '/opac-user/charges',
                                }"
                            >
                                <RouterLink :to="{ name: 'opac-user-charges' }"
                                    >Charges</RouterLink
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-memberentry.pl"
                                    >Personal details</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-passwd.pl"
                                    >Change password</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-readingrecord.pl"
                                    >Checkout history</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-privacy.pl"
                                    >Privacy</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-holdshistory.pl"
                                    >Holds history</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-suggestions.pl"
                                    >Purchase suggestions</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-messaging.pl"
                                    >Messaging</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-shelves.pl?op=list"
                                    >Lists</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-illrequests.pl"
                                    >Interlibrary loan requests</a
                                >
                            </li>

                            <li>
                                <a href="/cgi-bin/koha/opac-curbside-pickups.pl"
                                    >Curbside pickups</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-10 order-1 order-lg-2 ps-4">
            <div id="userdetails" class="maincontent">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<style>
@media only screen and (min-width: 992px) {
    #menu {
        border-right: 1px solid #d8d8d8;
    }
}
#menu li a {
    font-size: 110% !important;
}
#menu li {
    list-style-type: none;
}
@media only screen and (min-width: 992px) {
    #menu li.active a {
        border-right: 0;
        border-top: 1px solid #d8d8d8;
    }
}
#menu li.active a {
    background-color: #fff;
    border-top: 1px solid #999;
    color: #0074ad;
}
@media only screen and (min-width: 992px) {
    #menu li a {
        border-bottom: 1px solid #999 !important;
        margin: 0.4em 0 !important;
        margin-right: -1px !important;
    }
}
#menu li a {
    background-color: #f0f3f3;
    border: 1px solid #d8d8d8;
    border-bottom-width: 0;
    color: #005c89;
    display: block;
    margin: 0;
    padding: 0.4em 0.6em;
    text-decoration: none;
}
</style>
