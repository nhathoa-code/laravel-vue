<script setup>
import { onMounted, ref } from "vue";
import { VendorService } from "@root/services/acquisition/VendorService";
import { useMessage } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
import Toast from "primevue/toast";
const message = useMessage();
const vendors = ref(null);
onMounted(() => {
    VendorService.getList((res) => {
        console.log(res);
        vendors.value = res.data;
    });
});
function deleteVendor(id) {
    const yes = confirm("do you realy want to delete this vendor ?");
    if (!yes) return;
    VendorService.delete(id, (res) => {
        vendors.value = vendors.value.filter((item) => item.id !== id);
        message(res.data.message);
    });
}
function close(id) {
    VendorService.update(id, (res) => {}, {
        action: "close",
    });
}
</script>
<template>
    <Loading
        :visible="VendorService.loading.value || VendorService.submitting.value"
    />
    <Toast />
    <div class="row">
        <div id="acqui_order_supplierlist">
            <div v-for="item in vendors" class="supplier page-section">
                <h2 class="suppliername">
                    <RouterLink
                        :to="{ name: 'vendor', params: { id: item.id } }"
                    >
                        {{ item.name }}</RouterLink
                    >
                </h2>
                <span class="basketcounts">
                    <a href="/cgi-bin/koha/acqui/booksellers.pl?booksellerid=24"
                        >{{ item.baskets.length }} baskets</a
                    >
                </span>
                <div id="toolbar" class="btn-toolbar">
                    <div class="btn-group">
                        <RouterLink
                            :to="`/admin/acquisition/vendor/${item.id}/basket`"
                            class="btn btn-default"
                        >
                            <i class="fa fa-plus"></i> New basket
                            <span class="caret"></span>
                        </RouterLink>
                    </div>
                    <div class="btn-group">
                        <RouterLink
                            :to="`/admin/acquisition/vendor/${item.id}/edit`"
                            class="btn btn-default"
                            ><i
                                class="fa fa-solid fa-pencil"
                                aria-hidden="true"
                            ></i>
                            Edit vendor</RouterLink
                        >
                    </div>
                    <div v-if="item.baskets.length === 0" class="btn-group">
                        <button
                            @click="deleteVendor(item.id)"
                            type="button"
                            data-toggle="modal"
                            data-target="#deleteVendorModal"
                            data-booksellerid="24"
                            class="btn btn-default m-0"
                        >
                            <i class="fa fa-solid fa-trash-o"></i> Delete vendor
                        </button>
                    </div>
                    <div class="btn-group">
                        <RouterLink
                            :to="`/admin/acquisition/parcels?booksellerid=${item.id}`"
                            class="btn btn-default"
                        >
                            <i class="fa fa-inbox"></i> Receive shipments
                        </RouterLink>
                    </div>
                </div>
                <div class="baskets">
                    <div
                        v-if="item.baskets.length > 0"
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
                                    v-for="basket in item.baskets"
                                    :key="basket.id"
                                >
                                    <td>{{ basket.id }}</td>
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'basket',
                                                params: {
                                                    id: item.id,
                                                    basketId: basket.id,
                                                },
                                            }"
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
                                                :to="{
                                                    name: 'neworder',
                                                    params: {
                                                        id: item.id,
                                                        basketId: basket.id,
                                                    },
                                                }"
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
                                                @click="close(basket.id)"
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
    </div>
</template>
<style scoped>
a.btn {
    margin: 0;
}
.basketcounts {
    font-size: 13px;
}
.page-section:not(.bg-danger):not(.bg-warning):not(.bg-info):not(
        .bg-success
    ):not(.bg-primary):not(.action) {
    background-color: #fff;
}
.page-section {
    margin-bottom: 1rem;
    padding: 1rem;
}
#acqui_order_supplierlist .suppliername {
    display: inline-block;
    margin: 0.5em 1em 0.5em 0;
}
</style>
