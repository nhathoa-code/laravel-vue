<script setup>
import { onMounted, ref } from "vue";
import { LibraryService } from "@root/services/administration/LibraryService";
import { useRouter } from "vue-router";
const router = useRouter();
const libraries = ref([]);
const searchType = ref("title");
const query = ref("");
onMounted(() => {
    LibraryService.getList((res) => {
        libraries.value = res.data;
    });
});
function search() {
    router.push({
        name: "opac-search",
        query: { search_type: searchType.value, q: query.value },
    });
}
</script>
<template>
    <div class="row">
        <div class="col">
            <div id="opac-main-search" class="mastheadsearch">
                <form @submit.prevent="search">
                    <div class="form-row align-items-center">
                        <div class="col-sm-2 order-2 order-sm-2">
                            <select v-model="searchType" class="form-control">
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                                <option value="publisher">Publisher</option>
                                <option value="isbn">ISBN</option>
                            </select>
                        </div>
                        <div class="col order-4 order-sm-3">
                            <input
                                type="text"
                                class="form-control"
                                v-model="query"
                            />
                        </div>
                        <div
                            class="col-sm col-md-3 col-lg-2 order-3 order-sm-4"
                        >
                            <select
                                name="limit"
                                id="select_library"
                                class="form-control"
                            >
                                <option value="">All libraries</option>
                                <option
                                    v-for="item in libraries"
                                    :value="item.id"
                                >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>
                        <div class="order-5 col-sm-auto">
                            <button
                                type="submit"
                                id="searchsubmit"
                                class="btn btn-primary btn-custom"
                                title="Search"
                                aria-label="Search"
                            >
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="weight_search" value="1" />
                </form>
            </div>
        </div>
    </div>
</template>
<style scoped>
#opac-main-search {
    background: #f0f3f3;
    margin: 7px 0;
    padding: 15px;
}
.form-row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -5px;
    margin-left: -5px;
    gap: 10px;
}
#searchsubmit {
    height: calc(1.5em + 0.75rem + 2px);
    display: flex;
    align-items: center;
}
</style>
