<script setup>
import { RouterLink, useRouter } from "vue-router";
import { UserService } from "@root/services/user/UserService";
import { useUserStore } from "@root/stores/user";
import Loading from "@admin/common/Loading.vue";
const router = useRouter();
const userStore = useUserStore();
function logout() {
    UserService.logout(() => {
        userStore.setUser(null);
        UserService.logging_out.value = false;
        router.push({ name: "opac-user-login" });
    });
}
</script>
<template>
    <Loading :visible="UserService.logging_out.value" />
    <div id="header-region" class="noprint">
        <nav class="navbar navbar-expand">
            <RouterLink :to="{ name: 'main' }">
                <div id="logo">
                    <a class="navbar-brand" href="/cgi-bin/koha/opac-main.pl">
                    </a>
                </div>
            </RouterLink>

            <!-- <div id="cartDetails" class="cart-message">
                    Your cart is empty.
                </div> -->

            <ul id="cart-list-nav" class="navbar-nav">
                <li class="nav-item js-show" style="display: list-item">
                    <a
                        href="#"
                        class="nav-link"
                        title="Collect items you are interested in"
                        id="cartmenulink"
                        role="button"
                        aria-label="Cart"
                    >
                        <i
                            id="carticon"
                            class="fa fa-shopping-cart fa-icon-black me-1"
                            aria-hidden="true"
                        ></i>
                        <span class="cartlabel">Cart</span>
                        <span id="basketcount"></span>
                    </a>
                </li>

                <li class="divider-vertical"></li>

                <li class="nav-item dropdown">
                    <a
                        href="/cgi-bin/koha/opac-shelves.pl"
                        title="Show lists"
                        class="nav-link dropdown-toggle"
                        id="listsmenu"
                        data-toggle="dropdown"
                        role="button"
                        aria-label="Lists"
                        aria-haspopup="true"
                        aria-expanded="false"
                        ><i
                            class="fa fa-list fa-icon-black me-1"
                            aria-hidden="true"
                        ></i>
                        <span class="listslabel">Lists</span>
                    </a>
                    <div
                        aria-labelledby="listsmenu"
                        role="menu"
                        class="dropdown-menu"
                    >
                        <a
                            class="dropdown-item"
                            href="/cgi-bin/koha/opac-shelves.pl?op=list&amp;public=1"
                            tabindex="-1"
                            role="menuitem"
                            ><strong>Public lists</strong></a
                        >
                        <a
                            class="dropdown-item"
                            href="/cgi-bin/koha/opac-shelves.pl?op=view&amp;shelfnumber=4&amp;sortfield=title"
                            tabindex="-1"
                            role="menuitem"
                            >Staff Picks</a
                        >
                    </div>
                    <!-- / .dropdown-menu -->
                </li>
                <!-- / .nav-item.dropdown -->
            </ul>
            <!-- / .navbar-nav -->

            <ul id="members" class="navbar-nav">
                <template v-if="!userStore.user">
                    <li class="nav-item dropdown">
                        <RouterLink
                            :to="{ name: 'opac-user-login' }"
                            class="nav-link login-link loginModal-trigger"
                            ><i class="fa fa-user fa-icon-black fa-fw"></i>
                            <span class="userlabel"
                                >Log in to your account</span
                            ></RouterLink
                        >
                    </li>
                    <li class="divider-vertical"></li>
                    <li class="nav-item search_history">
                        <a
                            class="nav-link login-link"
                            href="/cgi-bin/koha/opac-search-history.pl"
                            title="View your search history"
                            >Search history</a
                        >
                    </li>
                </template>
                <template v-if="userStore.user">
                    <li class="nav-item dropdown dropdown-menu-end">
                        <a
                            class="nav-link dropdown-toggle show"
                            data-bs-toggle="dropdown"
                            role="button"
                        >
                            <i
                                class="fa fa-user fa-icon-black fa-fw"
                                aria-hidden="true"
                            ></i>
                            <span class="userlabel"
                                >Welcome, {{ userStore.user.name }}</span
                            >
                        </a>
                        <div
                            aria-labelledby="user-menu"
                            role="menu"
                            class="dropdown-menu dropdown-menu-end"
                            data-bs-popper="static"
                        >
                            <div id="loggedinuser-menu">
                                <p>
                                    <RouterLink
                                        class="login-link"
                                        :to="{ name: 'opac-user' }"
                                        ><span>Your account</span></RouterLink
                                    >
                                </p>
                                <p>
                                    <a
                                        tabindex="-1"
                                        role="menuitem"
                                        class="login-link"
                                        href="/cgi-bin/koha/opac-search-history.pl"
                                        title="View your search history"
                                        >Search history</a
                                    >
                                    <span class="divider-vertical"></span>
                                    <a
                                        class="clearsh"
                                        href="/cgi-bin/koha/opac-search-history.pl?action=delete"
                                        title="Delete your search history"
                                        ><i
                                            class="fa fa-trash-o"
                                            aria-hidden="true"
                                        ></i>
                                        Clear</a
                                    >
                                </p>

                                <a
                                    style="cursor: pointer"
                                    @click.prevent="logout"
                                    tabindex="-1"
                                    role="menuitem"
                                    class="logout"
                                    >Log out</a
                                >
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </nav>
    </div>
</template>
<style scoped>
.navbar {
    background: #fcf9fc !important;
    padding-right: 10px !important;
    padding-left: 10px !important;
}
#logo {
    background: rgba(0, 0, 0, 0)
        url(https://catalog.bywatersolutions.com/opac-tmpl/bootstrap/images/koha-green-logo.svg)
        no-repeat scroll 0%;
    border: 0;
    float: left !important;
    margin: 0;
    padding: 0;
    width: 100px;
}
#cart-list-nav {
    flex-grow: 2;
    font-size: 1rem;
    font-weight: bold;
}
#members {
    font-size: 1rem;
    font-weight: bold;
}
.divider-vertical {
    border: 1px solid #eee;
    border-right-color: #fcf9fc;
    margin: 5px 0;
}
.dropdown .dropdown-menu {
    border-radius: 0;
    background-color: #fcf9fc;
}
#members a.clearsh {
    color: #921925;
    font-size: 100%;
    font-weight: bold;
    padding-bottom: 0.6rem;
    padding-top: 0.6rem;
}
#loggedinuser-menu {
    min-width: 300px;
    padding: 0.5em 1em;
}
a {
    color: rgb(0 116 173);
}

#members .dropdown-menu a.logout {
    color: #921925 !important;
    font-weight: bold;
}

#loggedinuser-menu .divider-vertical {
    margin: 5px 10px;
}
p {
    margin-top: 0;
    margin-bottom: 1rem;
}
</style>
