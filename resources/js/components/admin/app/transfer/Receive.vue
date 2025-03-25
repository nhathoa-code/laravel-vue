<script setup>
import { onMounted, ref } from "vue";
import "vue-toast-notification/dist/theme-sugar.css";
import Spinner from "@admin/common/Spinner.vue";
const transfers = ref(null);
onMounted(() => {
    axios
        .get("api/transfers")
        .then((response) => {
            console.log(response);
            transfers.value = response.data;
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
});
function receive(item) {
    axios
        .post("api/transfers/receive", { transfer: item.id })
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
}
function cancel(item) {
    axios
        .post("api/transfers/cancel", { transfer: item.id })
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
}
</script>
<template>
    <template v-if="transfers">
        <h3>Transfers made to your library as of 01/31/2025</h3>
        <p>Your library is the destination for the following transfer(s)</p>
        <div v-for="(value, key) in transfers">
            <div class="row">
                <div class="grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-3">Coming from {{ key }}</h3>
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped"
                                >
                                    <thead>
                                        <tr>
                                            <th>Date of transfer</th>
                                            <th>Title</th>
                                            <th>On hold for</th>
                                            <th>Home library</th>
                                            <th style="width: 20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            class="problem"
                                            v-for="item in value"
                                            :key="item.id"
                                        >
                                            <td class="problem">
                                                {{ item.date_of_transfer }}
                                            </td>
                                            <td>
                                                <RouterLink to="" class="title">
                                                    {{
                                                        item.book_item.book
                                                            .title
                                                    }}
                                                </RouterLink>
                                                <br />
                                                <span class="problem"
                                                    >Barcode:
                                                    <span>{{
                                                        item.book_item.barcode
                                                    }}</span></span
                                                >
                                            </td>
                                            <td class="problem">
                                                {{ item.on_hold_for ?? "None" }}
                                            </td>
                                            <td class="problem">
                                                {{
                                                    item.book_item.home_library
                                                        .name
                                                }}
                                            </td>
                                            <td>
                                                <button
                                                    @click="receive(item)"
                                                    type="button"
                                                    class="btn btn-outline-light btn-fw action"
                                                >
                                                    Receive
                                                </button>
                                                <button
                                                    @click="cancel(item)"
                                                    type="button"
                                                    class="btn btn-outline-light btn-fw action"
                                                >
                                                    Cancel transfer
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
<style scoped>
.title {
    font-weight: bold;
}
</style>
