<script setup>
import { useRoute } from "vue-router";
import { onMounted, ref } from "vue";
import { UserService } from "@root/services/user/UserService";
import { Toolbar, Button } from "@admin/common/toolbar";
import ChangePassword from "./ChangePassword.vue";
import Loading from "@admin/common/Loading.vue";
const route = useRoute();
const changePassword = ref(false);
const patron = ref(null);
onMounted(() => {
    UserService.getById(route.params.id, (res) => {
        console.log(res);
        patron.value = res.data;
    });
});
</script>
<template>
    <Loading :visible="UserService.loading.value" />
    <template v-if="patron">
        <div id="toolbar" class="btn-toolbar">
            <div class="btn-group">
                <Toolbar>
                    <Button
                        :route="{
                            name: 'patron-edit',
                            params: { id: patron.id },
                        }"
                        name="Edit"
                        icon="fa-pencil"
                    ></Button>
                    <Button
                        name="Change password"
                        icon="fa-lock"
                        @click="changePassword = true"
                    ></Button>
                    <Button name="Search to hold" icon="fa-search"></Button>
                    <Button name="Print" icon="fa-print"></Button>
                    <Button name="Renew patron" icon="fa-refresh"></Button>
                    <Button name="Delete" icon="fa-trash-o"></Button>
                </Toolbar>
            </div>
        </div>
        <template v-if="!changePassword">
            <div class="page-section">
                <div class="circmessage attention">
                    <h3 class="page-section-title">Attention</h3>
                    <ul class="vnh-list">
                        <li class="expired">
                            <span class="circ-hlt">Expiration:</span>
                            <span>Patron's card has expired. </span>
                            <a href="#">Renew</a>
                            or
                            <a href="#">Edit details</a>
                        </li>
                        <li>
                            <span class="circ-hlt">Charges:</span>
                            <span>Patron has outstanding charges of 5.00.</span>
                            <a class="btn btn-default btn-xs">Make payment</a>
                            <a class="btn btn-default btn-xs"
                                >Pay all charges</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <h3>{{ patron.name }} ({{ patron.user_meta.card_number }})</h3>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="patroninfo-heading">
                                <h4>Contact information</h4>
                            </div>
                            <div class="address">
                                {{ patron.address }}
                            </div>
                            <div class="rows">
                                <ol>
                                    <li>
                                        <span class="label"
                                            >Primary phone:
                                        </span>
                                        <a href="tel:563.555.8851">{{
                                            patron.user_meta.phone
                                        }}</a>
                                    </li>

                                    <li class="email">
                                        <span class="label"
                                            >Primary email:</span
                                        >
                                        <a
                                            title="Bilbo@example.com"
                                            href="mailto:Bilbo@example.com"
                                            >{{ patron.email }}</a
                                        >
                                    </li>

                                    <li>
                                        <span class="label"
                                            >Date of birth:</span
                                        >
                                        {{ patron.user_meta.birthdate }}
                                        <span class="age_years"
                                            >(11 years)</span
                                        >
                                    </li>

                                    <li id="patron-privacyguarantor">
                                        <span class="label"
                                            >Show checkouts to guarantor:</span
                                        >

                                        No
                                    </li>

                                    <li id="patron-privacy_guarantor_fines">
                                        <span class="label"
                                            >Show charges to guarantor:</span
                                        >

                                        No
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="patroninfo-heading">
                                <h4>Library use</h4>
                            </div>
                            <div class="rows">
                                <ol>
                                    <li>
                                        <span class="label">Card number: </span>
                                        <span>{{
                                            patron.user_meta.card_number
                                        }}</span>
                                    </li>
                                    <li class="email">
                                        <span class="label">Type:</span>
                                        <span>{{ patron.role }}</span>
                                    </li>
                                    <li>
                                        <span class="label"
                                            >Registration date:</span
                                        >
                                        <span>{{
                                            patron.user_meta.registration_date
                                        }}</span>
                                    </li>
                                    <li>
                                        <span class="label"
                                            >Expiration date:</span
                                        >
                                        <span>{{
                                            patron.user_meta.expiration_date
                                        }}</span>
                                    </li>
                                    <li>
                                        <span class="label">Library:</span>
                                        <span>{{
                                            patron.user_meta?.library?.name ??
                                            "No limitation"
                                        }}</span>
                                    </li>
                                    <li>
                                        <span class="label">Username:</span>
                                        <span>{{ patron.username }}</span>
                                    </li>
                                    <li>
                                        <span class="label">Password:</span>
                                        <span>{{ "*******" }}</span>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="home-tab mt-3">
                <div
                    class="d-sm-flex align-items-center justify-content-between"
                >
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation">
                            <a
                                class="nav-link active"
                                id="home-tab"
                                data-bs-toggle="tab"
                                href="#checkouts"
                                role="tab"
                                aria-controls="checkouts"
                                aria-selected="true"
                                >Checkouts ({{ patron.loans.length }})</a
                            >
                        </li>
                        <li role="presentation">
                            <a
                                class="nav-link"
                                id="profile-tab"
                                data-bs-toggle="tab"
                                href="#audiences"
                                role="tab"
                                aria-selected="false"
                                tabindex="-1"
                                >Holds ({{ patron.holds.length }})</a
                            >
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic mt-0">
                    <div
                        class="tab-pane fade show active"
                        id="checkouts"
                        role="tabpanel"
                        aria-labelledby="checkouts"
                    >
                        <div
                            v-if="patron.loans.length > 0"
                            class="table-responsive"
                        >
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Due date</th>
                                        <th>Title</th>
                                        <th>Checked out on</th>
                                        <th>Checked out from</th>
                                        <th>Barcode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class="text-center"
                                        v-for="(item, index) in patron.loans"
                                        :key="item.id"
                                    >
                                        <td>{{ item.due_date }}</td>
                                        <td>{{ item.book_item.book.title }}</td>
                                        <td>{{ item.checkout_date }}</td>
                                        <td>
                                            {{
                                                item.book_item.home_library.name
                                            }}
                                        </td>
                                        <td>
                                            {{ item.book_item.barcode }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p v-else>
                            The patron currently not checked out any books
                        </p>
                    </div>
                    <div
                        class="tab-pane fade show"
                        id="audiences"
                        role="tabpanel"
                        aria-labelledby="audiences"
                    >
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Placed on</th>
                                        <th>Expires on</th>
                                        <th>Pickup location</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(item, index) in patron.holds"
                                        :key="item.id"
                                    >
                                        <td>
                                            <RouterLink
                                                >{{ item.book.title }} /
                                            </RouterLink>
                                            {{ item.book.authors.join(",") }}
                                        </td>
                                        <td>{{ item.placed_at }}</td>
                                        <td>Never expires</td>
                                        <td>
                                            {{ item.pickup_location.name }}
                                        </td>
                                        <td>{{ item.status }}</td>
                                        <td>
                                            <button
                                                class="btn btn-default action"
                                            >
                                                action
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <ChangePassword
            v-if="changePassword"
            :patron="patron"
            @change-password="changePassword = false"
        />
    </template>
</template>
<style scoped>
.toptabs {
    margin: 1rem 0;
}
.nav-tabs > li > a {
    background-color: #408540;
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
.nav-tabs > li a.active {
    background-color: #fff !important;
    border: 1px solid #fff !important;
    color: #111;
    cursor: default;
    padding: 0.5em 1em;
}
</style>
