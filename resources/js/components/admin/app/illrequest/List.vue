<script setup>
import { onMounted, ref } from "vue";
import { RouterLink } from "vue-router";
import DataTable from "../../common/DataTable.vue";
const illRequests = ref(null);
onMounted(() => {
    axios
        .get("api/ill-requests")
        .then((response) => {
            console.log(response);
            illRequests.value = response.data;
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
});
</script>
<template>
    <div v-if="illRequests" class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <data-table
                            :columns="{
                                id: 'Request ID',
                                'book_item.book.title': {
                                    name: 'Title',
                                    route: true,
                                    to: 'cataloging/book/{book_item.book.id}',
                                },
                                'patron.name': {
                                    name: 'Patron',
                                    route: true,
                                    to: 'patron/{patron.id}',
                                },
                                'book_item.barcode': 'Item barcode',
                                'lending_lib.name': 'Lending library',
                                'borrowing_lib.name': 'Borrowing library',
                                created_at: 'Placed on',
                                updated_at: 'Updated on',
                            }"
                            :data="illRequests"
                        >
                            <template v-slot:actions="{ item }">
                                <RouterLink :to="`ill-requests/${item.id}`">
                                    <button
                                        type="button"
                                        class="btn btn-outline-light btn-fw action"
                                    >
                                        manage request
                                    </button>
                                </RouterLink>
                            </template>
                        </data-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
