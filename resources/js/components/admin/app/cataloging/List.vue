<script setup>
import { onMounted, ref } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";
import { BookService } from "@root/services/book/BookService";
import DataTable from "primevue/datatable";
import Paginator from "primevue/paginator";
import Column from "primevue/column";
import Loading from "@admin/common/Loading.vue";
const data = ref(null);
const router = useRouter();
const route = useRoute();
onMounted(() => {
    BookService.getList(
        function (res) {
            data.value = res.data;
        },
        { getLocations: true, page: route.query.page ?? 1 }
    );
});
function onPageChange(event) {
    BookService.getList(
        function (res) {
            data.value = res.data;
        },
        { page: event.page + 1, getLocations: true }
    );
    router.push({ name: "books", query: { page: event.page + 1 } });
}
</script>
<template>
    <Loading :visible="BookService.loading.value" />
    <template v-if="data">
        <div class="card">
            <div class="card-body">
                <DataTable :value="data.data" showGridlines stripedRows>
                    <Column style="align-content: start">
                        <template #body="{ data }">
                            <img
                                style="width: 75px; height: auto"
                                :src="data.cover_image"
                            />
                        </template>
                    </Column>
                    <Column style="align-content: start" header="Results">
                        <template #body="{ data }">
                            <RouterLink
                                :to="`/admin/cataloging/book/${data.id}`"
                            >
                                {{ data.title }}
                            </RouterLink>
                            <p class="author">
                                <span class="byAuthor">By </span>
                                <a href="">{{ data.authors.join(",") }}</a>
                            </p>
                            <p class="results_summary isbn">
                                <span class="label">ISBN: </span
                                ><span property="isbn">{{ data.isbn }}</span>
                            </p>
                            <p class="results_summary rda264">
                                <span class="label">Publisher: </span>
                                <span
                                    property="rda264_name"
                                    typeof="Organization"
                                    ><span property="name" class="rda264_name"
                                        >{{ data.publisher }},
                                    </span></span
                                >
                                <span property="date" class="rda264_date">{{
                                    data.publish_date
                                }}</span>
                            </p>
                            <p class="results_summary description">
                                <span class="label">Description: </span
                                >{{ data.page_count }} pages.
                            </p>
                            <p class="hold">
                                <a
                                    id="reserve_30084"
                                    href="/cgi-bin/koha/reserve/request.pl?biblionumber=30084"
                                    >Holds (0)</a
                                >

                                |
                                <a class="addtocart" id="cart30084" href="#"
                                    >Add to cart</a
                                >
                                <a
                                    style="display: none"
                                    class="cartRemove"
                                    id="cartR30084"
                                    href="#"
                                    >(remove)</a
                                >

                                |
                                <a
                                    id="requst_article_30084"
                                    href="/cgi-bin/koha/circ/request-article.pl?biblionumber=30084"
                                    >Request article</a
                                >

                                |
                                <RouterLink :to="`cataloging/edit/${data.id}`">
                                    Edit record
                                </RouterLink>
                                |
                                <a
                                    href="/cgi-bin/koha/cataloguing/additem.pl?biblionumber=30084"
                                    >Edit items</a
                                >

                                <span class="view-in-opac">
                                    |
                                    <a
                                        href="https://train2.bywatersolutions.com/cgi-bin/koha/opac-detail.pl?biblionumber=30084"
                                        target="_blank"
                                        >OPAC view</a
                                    >
                                </span>
                            </p>
                        </template>
                    </Column>
                    <Column header="Locations">
                        <template #body="{ data }">
                            <ul class="loop_items">
                                <template v-for="location in data.locations">
                                    <li>
                                        <div class="result_item_details">
                                            <img
                                                :src="'/admin_asset/images/book.png'"
                                                style="
                                                    width: 25px;
                                                    height: auto;
                                                    margin-right: 5px;
                                                "
                                            />
                                            <span style="font-weight: bold">{{
                                                location.name
                                            }}</span>
                                            <span class="item_count"
                                                >({{
                                                    location.available
                                                }})</span
                                            >
                                            <span>Book</span>
                                            <span class="ms-1 available"
                                                >(Available)</span
                                            >
                                        </div>
                                    </li>
                                    <li>
                                        <div class="result_item_details">
                                            <img
                                                :src="'/admin_asset/images/book.png'"
                                                style="
                                                    width: 25px;
                                                    height: auto;
                                                    margin-right: 5px;
                                                "
                                            />
                                            <span style="font-weight: bold">{{
                                                location.name
                                            }}</span>
                                            <span class="item_count"
                                                >({{
                                                    location.checked_out
                                                }})</span
                                            >
                                            <span>Book</span>
                                            <span class="ms-1 problem"
                                                >(Checked out)</span
                                            >
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </template>
                    </Column>
                </DataTable>
                <Paginator
                    :rows="data.per_page"
                    :totalRecords="data.total"
                    :first="(data.current_page - 1) * data.per_page"
                    @page="onPageChange"
                ></Paginator>
            </div>
        </div>
    </template>
</template>
<style scoped>
.title {
    font-weight: bold;
}
.author {
    margin-top: 0.65rem;
}
.edit-items-link {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
}
td ul li {
    clear: left;
    font-size: 90%;
    padding: 0.2em 0;
    top: 100%;
}
.item_count {
    font-weight: bold;
    padding: 2px;
}
</style>
