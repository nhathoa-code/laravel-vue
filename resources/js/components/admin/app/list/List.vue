<script setup>
import { onMounted, ref } from "vue";
import { ListService } from "@root/services/book/ListService";
import Loading from "@admin/common/Loading.vue";
const privateList = ref(null);
const publicList = ref(null);
onMounted(() => {
    ListService.getList((res) => {
        privateList.value = res.data.filter((item) => item.type === "private");
        publicList.value = res.data.filter((item) => item.type === "public");
    });
});
</script>
<template>
    <Loading :visible="ListService.loading.value" />
    <div v-if="privateList && publicList" class="row">
        <h3>Lists</h3>
        <div class="home-tab tabs">
            <div class="d-sm-flex align-items-center justify-content-between">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation">
                        <a
                            class="nav-link v-link active"
                            id="home-tab"
                            data-bs-toggle="tab"
                            href="#privatelists"
                            role="tab"
                            aria-selected="true"
                            >Your lists</a
                        >
                    </li>
                    <li role="presentation">
                        <a
                            class="nav-link v-link"
                            id="profile-tab"
                            data-bs-toggle="tab"
                            href="#publiclists"
                            role="tab"
                            aria-selected="false"
                            tabindex="-1"
                            >Public lists</a
                        >
                    </li>
                </ul>
            </div>
            <div class="tab-content tab-content-basic mt-0">
                <div
                    class="tab-pane fade show active"
                    id="privatelists"
                    role="tabpanel"
                    aria-labelledby="privatelists"
                >
                    <div class="table-responsive col-6">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>List Name</th>
                                    <th>Contents</th>
                                    <th>Sort by</th>
                                    <th>Type</th>
                                    <th style="width: 20%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in privateList" :key="item.id">
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'list-content',
                                                params: { id: item.id },
                                            }"
                                        >
                                            {{ item.name }}
                                        </RouterLink>
                                    </td>
                                    <td>{{ item.items.length }} item(s)</td>
                                    <td>{{ item.sort_by }}</td>
                                    <td>{{ item.type }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i
                                                title="Edit library"
                                                class="fa fa-pencil"
                                            ></i>
                                            Edit
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i
                                                title="Delete library"
                                                class="fa fa-trash-o"
                                            ></i>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="tab-pane fade show"
                    id="publiclists"
                    role="tabpanel"
                    aria-labelledby="publiclists"
                >
                    <table
                        v-if="publicList.length > 0"
                        class="table table-bordered table-striped"
                    >
                        <thead>
                            <tr>
                                <th>List Name</th>
                                <th>Contents</th>
                                <th>Sort by</th>
                                <th>Type</th>
                                <th style="width: 20%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in publicList" :key="item.id">
                                <td>
                                    <RouterLink :to="`/admin/list/${item.id}`">
                                        {{ item.name }}
                                    </RouterLink>
                                </td>
                                <td>{{ item.count }} item(s)</td>
                                <td>{{ item.sort_by }}</td>
                                <td>{{ item.list_type }}</td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-outline-light btn-fw"
                                    >
                                        <i
                                            title="Edit library"
                                            class="fa fa-pencil"
                                        ></i>
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-light btn-fw"
                                    >
                                        <i
                                            title="Delete library"
                                            class="fa fa-trash-o"
                                        ></i>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="mb-0" v-else>you do not have any public lists</p>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.toptabs {
    margin: 1rem 0;
}
</style>
