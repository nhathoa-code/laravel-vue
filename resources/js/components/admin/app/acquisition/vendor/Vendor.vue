<script setup>
import { onMounted, ref } from "vue";
import { RouterLink, useRoute } from "vue-router";
import { VendorService } from "@root/services/acquisition/VendorService";
import { Toolbar, Button } from "@admin/common/toolbar";
import Loading from "@admin/common/Loading.vue";
const vendor = ref(null);
const showAll = ref(false);
const route = useRoute();
function getVendor(params = {}) {
    VendorService.getById(
        route.params.id,
        (res) => {
            console.log(res.data);
            vendor.value = res.data;
        },
        params
    );
}
onMounted(() => {
    getVendor({ baskets: true });
});
function showBaskets(status) {
    VendorService.getBaskets(
        vendor.value.id,
        (res) => {
            vendor.value.baskets = res.data;
            showAll.value = !showAll.value;
        },
        { status: status }
    );
}
</script>
<template>
    <Loading :visible="VendorService.loading.value" />
    <template v-if="vendor">
        <Toolbar>
            <Button
                :route="{ name: 'new-basket', params: { id: vendor.id } }"
                name="New basket"
                icon="fa-plus"
            ></Button>
            <Button
                :route="{ name: 'edit-vendor', params: { id: vendor.id } }"
                name="Edit vendor"
                icon="fa-pencil"
            ></Button>
            <Button
                :route="{
                    name: 'vendor-parcel',
                    query: { booksellerid: vendor.id },
                }"
                name="Receive shipments"
                icon="fa-inbox"
            ></Button>
        </Toolbar>
        <div class="page-section col-6 rows">
            <h2>Vendor details</h2>
            <li>
                <span class="label">Type: </span>
            </li>
            <li><span class="label">Company name: </span>{{ vendor.name }}</li>
            <li><span class="label">Postal address: </span></li>
            <li><span class="label">Physical address: </span></li>
            <li><span class="label">Phone: </span></li>
            <li><span class="label">Fax: </span></li>
        </div>
        <div class="supplier page-section">
            <h2 class="suppliername">
                <a href="javascript:void(0)">
                    {{ vendor.name }}
                </a>
                <span class="basketcounts">
                    {{ vendor.number_of_baskets }} baskets
                </span>
                <div>
                    <a
                        @click="showBaskets('all')"
                        v-if="!showAll"
                        class="action-link"
                        >Show all baskets</a
                    >
                    <a @click="showBaskets('active')" v-else class="action-link"
                        >Show active baskets only</a
                    >
                </div>
            </h2>
            <div class="baskets">
                <div class="baskets">
                    <div
                        v-if="vendor.baskets.length > 0"
                        class="table-responsive"
                    >
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Item count</th>
                                    <th>Item expected</th>
                                    <th>Created by</th>
                                    <th>Date</th>
                                    <th>Closed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr
                                    v-for="basket in vendor.baskets"
                                    :key="basket.id"
                                >
                                    <td>{{ basket.id }}</td>
                                    <td>
                                        <RouterLink
                                            :to="`/admin/acquisition/vendor/${vendor.id}/basket/${basket.id}`"
                                        >
                                            {{ basket.name }}
                                        </RouterLink>
                                    </td>
                                    <td>{{ basket.items.length }}</td>
                                    <td>{{ basket.items.length }}</td>
                                    <td>{{ basket.created_by.name }}</td>
                                    <td>{{ basket.opened_on }}</td>
                                    <td>
                                        {{
                                            basket.status == "closed"
                                                ? basket.closed_on
                                                : ""
                                        }}
                                    </td>
                                    <td>
                                        <template
                                            v-if="basket.status == 'spending'"
                                        >
                                            <RouterLink
                                                v-if="
                                                    basket.status === 'spending'
                                                "
                                                :to="`/admin/acquisition/vendor/${vendor.id}/basket/${basket.id}/neworder`"
                                            >
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-light btn-fw action"
                                                >
                                                    <i class="fa fa-plus"></i>
                                                    Add to basket
                                                </button>
                                            </RouterLink>
                                            <button
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i
                                                    class="fa fa-times-circle"
                                                ></i>
                                                Close this basket
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>No pending baskets.</p>
                </div>
            </div>
        </div>
    </template>
</template>
