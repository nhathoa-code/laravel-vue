<script setup>
import { RouterLink, useRoute } from "vue-router";
import { onMounted, ref } from "vue";
import Spinner from "@admin/common/Spinner.vue";
const route = useRoute();
const isProcessing = ref(false);
const id = route.params.id;
const illRequest = ref(null);
onMounted(() => {
    axios
        .get(`api/ill-requests/${id}`)
        .then((response) => {
            console.log(response);
            illRequest.value = response.data;
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
});
function confirmRequest() {
    const data = {
        book: illRequest.value.book_item.book.id,
        book_item: illRequest.value.book_item.id,
        pickup_library: illRequest.value.borrowing_lib.id,
    };
    isProcessing.value = true;
    axios
        .post(
            `api/circulations/patron/${illRequest.value.patron.id}/place-hold`,
            data
        )
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {
            isProcessing.value = false;
        });
}
function deleteRequest() {
    isProcessing.value = true;
    axios
        .delete(`api/ill-requests/${illRequest.id}`)
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {
            isProcessing.value = false;
        });
}
</script>
<template>
    <template v-if="illRequest">
        <h3>Request Detail</h3>
        <div class="row">
            <div
                id="toolbar"
                class="btn-toolbar fh-fixedHeader"
                style="position: static; left: auto"
            >
                <div class="btn-group">
                    <button class="btn btn-default">
                        <i class="fa fa-pencil"></i> Edit request
                    </button>
                </div>
                <div class="btn-group">
                    <button @click="confirmRequest" class="btn btn-default">
                        <i class="fa fa-check"></i> Confirm request
                    </button>
                </div>
                <div class="btn-group">
                    <button class="btn btn-default">
                        <i class="fa fa-paper-plane-o"></i> Place request with
                        partner
                    </button>
                </div>
                <div class="btn-group">
                    <button @click="deleteRequest" class="btn btn-default">
                        <i class="fa fa-times-circle"></i> Delete request
                    </button>
                </div>
                <div class="btn-group">
                    <button class="btn btn-default">
                        <i class="fa fa-envelope"></i> Send notice to patron
                    </button>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body pb-0">
                        <h4>Details from library</h4>
                        <div class="rows">
                            <ol>
                                <li>
                                    <span class="label">Request number: </span>
                                    <span>{{ illRequest.id }}</span>
                                </li>
                                <li class="email">
                                    <span class="label">Patron:</span>
                                    <RouterLink>
                                        {{ illRequest.patron.name }}
                                    </RouterLink>
                                </li>

                                <li>
                                    <span class="label">Pickup library:</span>
                                    <span>{{
                                        illRequest.borrowing_lib.name
                                    }}</span>
                                </li>
                                <li>
                                    <span class="label">Status:</span>
                                    <span>{{ illRequest.status }}</span>
                                </li>
                                <li>
                                    <span class="label">Last updated:</span>
                                    <span>{{ illRequest.updated_at }}</span>
                                </li>
                            </ol>
                        </div>
                        <h4>Details from supplier</h4>
                        <div class="rows">
                            <ol>
                                <li>
                                    <span class="label">Title :</span>
                                    <span>
                                        <RouterLink>
                                            {{
                                                illRequest.book_item.book.title
                                            }}
                                        </RouterLink>
                                    </span>
                                </li>
                                <li>
                                    <span class="label">Author :</span>
                                    <span>
                                        {{ illRequest.book_item.book.author }}
                                    </span>
                                </li>
                                <li>
                                    <span class="label">Item barcode:</span>
                                    <span>{{
                                        illRequest.book_item.barcode
                                    }}</span>
                                </li>
                                <li>
                                    <span class="label">Target:</span>
                                    <span>{{
                                        illRequest.lending_lib.name
                                    }}</span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <overlay :visible="isProcessing">
        <spinner :visible="true"></spinner>
    </overlay>
</template>
