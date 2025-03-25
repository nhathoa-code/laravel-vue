<script setup>
import { onMounted, ref } from "vue";
import { RouterLink, useRoute } from "vue-router";
import { BookService } from "@root/services/book/BookService";
import { ListService } from "@root/services/book/ListService";
import { useUserStore } from "@root/stores/user";
import { useCartStore } from "@root/stores/cart";
import { Toolbar, Button } from "@admin/common/toolbar";
import { formatCurrency, useMessage } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
import Toast from "primevue/toast";
const book = ref(null);
const route = useRoute();
const cartStore = useCartStore();
const userStore = useUserStore();
const message = useMessage();
onMounted(() => {
    BookService.getById(
        route.params.id,
        (res) => {
            console.log(res);
            book.value = res.data;
        },
        { getItems: true }
    );
});
function addToCart(book) {
    if (!cartStore.inCart(book)) {
        cartStore.addItem(book);
        message("This item has been added to your cart");
    }
}
function removeFromCart(book) {
    cartStore.removeItem(book);
    message("This item has been removed from your cart");
}
function print() {
    window.print();
}
function addToList(list) {
    const values = {
        book_id: book.value.id,
    };
    ListService.setError((error) => {
        if (error.status === 400) {
            message(error.response.data.message, "error");
        }
    }).insertItem(list.id, { values }, (res) => {
        message(res.data.message);
    });
}
</script>
<template>
    <Loading
        :visible="BookService.loading.value || ListService.submitting.value"
    />
    <Toast />
    <template v-if="book">
        <Toolbar>
            <Button
                :route="{ name: 'book-items', params: { id: book.id } }"
                name="New item"
                icon="fa-plus"
            ></Button>
            <Button
                :route="{ name: 'book-edit', params: { id: book.id } }"
                name="Edit"
                icon="fa-pencil"
            ></Button>
            <Button
                @click="
                    cartStore.inCart(book)
                        ? removeFromCart(book)
                        : addToCart(book)
                "
                :name="
                    !cartStore.inCart(book) ? 'Add to cart' : 'Remove from cart'
                "
                icon="fa-shopping-cart"
            ></Button>
            <Button
                name="Add to list"
                icon="fa-list"
                :dropdown="[
                    'Your lists',
                    ...userStore.user.lists.map((item) => {
                        return {
                            name: item.name,
                            command: () => {
                                addToList(item);
                            },
                        };
                    }),
                    null,
                    {
                        route: { name: 'list-new' },
                        name: 'New list',
                    },
                ]"
            ></Button>
            <Button @click="print" name="Print" icon="fa-print"></Button>
            <Button
                :route="{ name: 'reserve', query: { bookid: book.id } }"
                name="Place hold"
                icon="fa-bookmark"
            ></Button>
        </Toolbar>
        <div class="row">
            <div class="col-md-9">
                <div class="page-section">
                    <div class="row">
                        <div>
                            <h1 class="title" property="name">
                                {{ book.title }}
                            </h1>
                            <h5 class="author">
                                <span class="byAuthor">By: </span
                                ><a>{{ book.authors.join(",") }}</a>
                            </h5>
                            <span class="results_summary type">
                                <span class="label">Material type: </span>
                                Book</span
                            >
                            <span class="results_summary publisher">
                                <span class="label">Publication details:</span>
                                <a>{{ book.publisher }}</a> </span
                            ><span class="results_summary description"
                                ><span class="label">Page count: </span
                                >{{ book.page_count }} pages</span
                            ><span class="results_summary loc"
                                ><span class="label">LOC classification: </span
                                >PZ3.F9612 | He</span
                            ><span class="results_summary opac_view"
                                ><span class="label">OPAC view: </span
                                ><a target="_blank">Open in new window</a>.
                            </span>
                            <span
                                id="catalogue_detail_marc_preview"
                                class="results_summary"
                                ><span class="label">MARC preview:</span>
                                <a title="MARC" class="previewMARC"
                                    >Show</a
                                ></span
                            >
                            <span
                                id="catalogue_detail_framework"
                                class="results_summary"
                            >
                                <span class="label">Description:</span>
                                <span class="frameworkcode">{{
                                    book.description.slice(0, 500) + "..."
                                }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 pe-0 pt-0">
                <div class="page-section book-cover">
                    <img
                        style="width: 100%; height: auto"
                        :src="book.cover_image"
                    />
                </div>
            </div>
        </div>
        <div class="home-tab tabs mt-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation">
                        <a
                            class="nav-link v-link active"
                            id="home-tab"
                            data-bs-toggle="tab"
                            href="#checkouts"
                            role="tab"
                            aria-controls="checkouts"
                            aria-selected="true"
                            >Holding ({{ book.items.length }})</a
                        >
                    </li>
                    <li role="presentation">
                        <a
                            class="nav-link v-link"
                            id="home-tab"
                            data-bs-toggle="tab"
                            href="#acquisition_details"
                            role="tab"
                            aria-controls="acquisition_details"
                            aria-selected="true"
                            >Acquisition details ({{
                                book.acquisition_details.length
                            }})</a
                        >
                    </li>
                    <li role="presentation">
                        <a
                            class="nav-link v-link"
                            id="home-tab"
                            data-bs-toggle="tab"
                            href="#desc"
                            role="tab"
                            aria-controls="desc"
                            aria-selected="true"
                            >Description</a
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Current Library</th>
                                    <th>Shelving location</th>
                                    <th>Status</th>
                                    <th>Last seen</th>
                                    <th>Checkouts</th>
                                    <th>Renewals</th>
                                    <th>Date accessioned</th>
                                    <th>Barcode</th>
                                    <th class="actions" style="width: 10%">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in book.items" :key="item.id">
                                    <td>
                                        {{ item.current_location.name }}
                                    </td>
                                    <td>{{ item.location.value }}</td>
                                    <td style="font-weight: bold"></td>
                                    <td>
                                        {{ item.last_seen }}
                                    </td>
                                    <td>
                                        {{ item.checkouts }}
                                    </td>
                                    <td>
                                        {{ item.renewals }}
                                    </td>
                                    <td>
                                        {{ item.date_accessioned }}
                                    </td>
                                    <td class="problem">
                                        {{ item.barcode }}
                                    </td>
                                    <td class="actions">
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i
                                                class="fa fa-solid fa-pencil"
                                            ></i>
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="tab-pane fade show"
                    id="acquisition_details"
                    role="tabpanel"
                    aria-labelledby="acquisition_details"
                >
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Invoice</th>
                                    <th>Basket</th>
                                    <th>Order number</th>
                                    <th>Creation date</th>
                                    <th>Receive date</th>
                                    <th>Status</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Internal note</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="item in book.acquisition_details">
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'vendor',
                                                params: {
                                                    id: item.basket.vendor.id,
                                                },
                                            }"
                                        >
                                            {{ item.basket.vendor.name }}
                                        </RouterLink>
                                    </td>
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'invoice',
                                                params: { id: item.invoice_id },
                                            }"
                                        >
                                            #{{ item.id }}
                                        </RouterLink>
                                    </td>
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'basket',
                                                params: {
                                                    id: item.basket.vendor.id,
                                                    basketId: item.basket.id,
                                                },
                                            }"
                                        >
                                            {{
                                                item.basket.name +
                                                `(${item.basket.id})`
                                            }}
                                        </RouterLink>
                                    </td>
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.creation_date }}</td>
                                    <td>{{ item.invoice.shipping_date }}</td>
                                    <td>{{ item.invoice.status }}</td>
                                    <td>{{ item.quantity }}</td>
                                    <td>
                                        {{ formatCurrency(item.cost) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="tab-pane fade show"
                    id="desc"
                    role="tabpanel"
                    aria-labelledby="desc"
                >
                    <p>{{ book.description }}</p>
                </div>
            </div>
        </div>
    </template>
</template>
<style scoped>
.title {
    font-size: 1.5rem;
    font-weight: bold;
}
.results_summary {
    color: #202020;
    display: block;
    padding: 0 0 0.5em;
}
.results_summary .label {
    color: #707070;
}
label,
.label {
    color: #000;
    display: inline;
    font-size: 0.85rem;
    font-weight: normal;
    max-width: inherit;
    padding: 0;
    white-space: normal;
}
@media print {
    :deep(.actions) {
        display: none !important;
    }
    :deep(#checkouts) {
        display: block !important;
        opacity: 1 !important;
    }
}
</style>
