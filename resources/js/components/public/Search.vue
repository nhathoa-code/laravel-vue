<script setup>
import { onMounted, ref, watch } from "vue";
import { BookService } from "@root/services/book/BookService";
import { useRoute } from "vue-router";
import Loading from "@admin/common/Loading.vue";
const route = useRoute();
const results = ref([]);
function search() {
    BookService.search(
        (res) => {
            console.log(res);
        },
        {
            search_type: route.query.search_type,
            q: route.query.q,
        }
    );
}
onMounted(() => {
    search();
});
watch(
    () => route.query,
    () => {
        search();
    }
);
</script>
<template>
    <Loading :visible="BookService.loading.value" />
    <table class="table table-striped">
        <tbody>
            <tr>
                <td class="bibliocol">
                    <div
                        class="coverimages cover-slides itemtype_BDBOOK"
                        data-isbn="1416991247"
                        data-imgtitle="Click, clack, abc /"
                    >
                        <a
                            class="p1"
                            href="/cgi-bin/koha/opac-detail.pl?biblionumber=7697"
                        >
                            <span
                                title="Click, clack, abc /"
                                class="1416991247"
                                id="coce-thumbnail1"
                                href="https://images-na.ssl-images-amazon.com/images/P/1416991247.01.MZZZZZZZZZ.jpg"
                                ><img
                                    src="https://images-na.ssl-images-amazon.com/images/P/1416991247.01.MZZZZZZZZZ.jpg"
                                    alt="Cover image"
                            /></span>
                        </a>
                    </div>
                    <span
                        class="Z3988"
                        title="ctx_ver=Z39.88-2004&amp;amp;rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Abook&amp;amp;rft.genre=book&amp;amp;rft.btitle=Click%2C%20clack%2C%20abc%20%2F&amp;amp;rft.isbn=1416991247&amp;amp;rft.au=Cronin%2C%20Doreen.&amp;amp;rft.au=Lewin%2C%20Betsy.&amp;amp;rft.pub=Little%20Simon&amp;amp;rft.date=2009%2C%20c2005."
                    ></span>

                    <div id="title_summary_7697" class="title_summary">
                        <a
                            href="/cgi-bin/koha/opac-detail.pl?biblionumber=7697&amp;query_desc=ti%2Cwrdl%3A%20abc"
                            class="title"
                            >Click, clack, <span class="term">abc</span> /
                            <span class="title_resp_stmt"
                                >by Doreen Cronin and Betsy Lewin.
                            </span></a
                        ><span class="byAuthor">by</span>
                        <ul class="author resource_list">
                            <li>
                                <a
                                    href="/cgi-bin/koha/opac-search.pl?q=au:%22Cronin%2C%20Doreen.%22"
                                    >Cronin, Doreen</a
                                >
                            </li>
                            <li>
                                <a
                                    href="/cgi-bin/koha/opac-search.pl?q=au:%22Lewin%2C%20Betsy.%22"
                                    >Lewin, Betsy</a
                                >
                            </li>
                        </ul>
                        <div class="results_summary edition">
                            <span class="label">Edition: </span>1st Little Simon
                            board book ed.
                        </div>
                        <div class="results_summary type">
                            <span class="results_format"
                                ><span class="label">Format: </span>
                                print </span
                            ><span class="results_contents_literary"
                                ><span class="label">; Literary form: </span>
                                Fiction </span
                            ><span class="results_audience"
                                ><span class="label">; Audience: </span>
                                Primary;
                            </span>
                        </div>
                        <div class="results_summary publisher">
                            <span class="label">Publication details: </span
                            ><span class="publisher_place" property="location"
                                >New York :</span
                            >
                            <span property="name" class="publisher_name"
                                >Little Simon</span
                            >
                            <span
                                property="datePublished"
                                class="publisher_date"
                                >2009, c2005</span
                            >
                        </div>
                        <div class="results_summary availability">
                            <span class="label">Availability: </span
                            ><span class="available reallyavailable"
                                ><span class="AvailabilityLabel"
                                    ><strong
                                        >Items available for loan:
                                    </strong></span
                                ><span class="ItemSummary"
                                    ><span class="available"
                                        ><span class="ItemBranch"
                                            >Main Library</span
                                        >
                                        (1)<span class="CallNumberAndLabel"
                                            ><span class="LabelCallNumber"
                                                >Location, call number: </span
                                            ><span class="CallNumber"
                                                ><strong>JUV </strong>P
                                                CRO</span
                                            ></span
                                        >.
                                    </span></span
                                ></span
                            >
                        </div>
                        <div class="results_summary ratings">
                            <div class="br-wrapper br-theme-fontawesome-stars">
                                <div class="br-widget br-readonly">
                                    <a href="#"></a>

                                    <a href="#"></a>

                                    <a href="#"></a>

                                    <a href="#"></a>

                                    <a href="#"></a>
                                </div>
                            </div>
                            <br />
                        </div>
                        <div class="actions-menu noprint">
                            <span class="actions"
                                ><a
                                    class="btn btn-link btn-sm ps-0"
                                    aria-label="Place a hold on Click, clack, abc /"
                                    href="/cgi-bin/koha/opac-reserve.pl?biblionumber=7697"
                                    ><i
                                        class="fa fa-fw fa-bookmark"
                                        aria-hidden="true"
                                    ></i>
                                    Place hold</a
                                ></span
                            >
                            <span class="actions"
                                ><a
                                    href="/cgi-bin/koha/opac-request-article.pl?biblionumber=7697"
                                    class="btn btn-link btn-sm"
                                    ><i
                                        class="fa-solid fa-file-lines"
                                        aria-hidden="true"
                                    ></i>
                                    Request article</a
                                ></span
                            >
                            <span class="actions cart-actions"
                                ><a
                                    aria-label="Add Click, clack, abc / to the cart"
                                    data-biblionumber="7697"
                                    class="btn btn-link btn-sm addtocart cart cart7697"
                                    href="#"
                                    ><i
                                        class="fa fa-shopping-cart"
                                        aria-hidden="true"
                                    ></i>
                                    Add to cart</a
                                >
                                <a
                                    style="display: none"
                                    data-biblionumber="7697"
                                    class="btn btn-link btn-sm remove cartRemove cartR7697"
                                    href="#"
                                    >(remove)</a
                                ></span
                            >
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<style scoped></style>
