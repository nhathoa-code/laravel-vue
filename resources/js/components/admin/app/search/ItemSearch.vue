<script setup>
import { onMounted, ref } from "vue";
import { Form } from "vee-validate";
import { RouterLink } from "vue-router";
import { FormInput, SubmitButton } from "@admin/common/form";
import { BookService } from "@root/services/book/BookService";
import { LibraryService } from "@root/services/administration/LibraryService";
import { AuthorizedCategoryService } from "@root/services/administration/AuthorizedCategoryService";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
const homebranchs = ref([]);
const holdingbranchs = ref([]);
const locs = ref([]);
const ccodes = ref([]);
const libraries = ref([]);
const shelveLocations = ref([]);
const collections = ref([]);
const fields = ref([{ c: "and", f: "", op: "", q: "" }]);
const results = ref(null);
onMounted(() => {
    LibraryService.getList((res) => {
        libraries.value = res.data;
    });
    AuthorizedCategoryService.getValueList(
        (res) => {
            shelveLocations.value = res.data;
        },
        { category: "LOC" }
    );
    AuthorizedCategoryService.getValueList(
        (res) => {
            collections.value = res.data;
        },
        { category: "CCODE" }
    );
});
function searchItems(values) {
    // return console.log(fields.value);
    BookService.searchItems(
        (res) => {
            console.log(res.data);
            results.value = res.data;
        },
        {
            ...values,
            homebranchs: homebranchs.value.map((item) => item.id),
            holdingbranchs: holdingbranchs.value.map((item) => item.id),
            locs: locs.value.map((item) => item.id),
        }
    );
}
</script>
<template>
    <h3 class="title">Item Search</h3>
    <div class="row">
        <div class="col-10">
            <Form @submit="searchItems">
                <SubmitButton
                    icon="<i class='fa fa-search'></i>"
                    name="Search"
                    class="submit"
                    :submitting="BookService.loading.value"
                    :cancel="false"
                />
                <div class="page-section mt-3 pb-3">
                    <div class="form-group row mb-0">
                        <label
                            for="code"
                            class="col-2 col-form-label px-0 text-end"
                            >Home library:</label
                        >
                        <div class="col-2">
                            <FormInput type="select" name="homebranch_op">
                                <option selected value="=">is</option>
                                <option value="!=">is not</option>
                            </FormInput>
                        </div>
                        <div class="col-8">
                            <multiselect
                                v-model="homebranchs"
                                track-by="id"
                                :options="libraries"
                                :multiple="true"
                                :searchable="false"
                                label="name"
                                placeholder=""
                            ></multiselect>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label
                            for="code"
                            class="col-2 col-form-label px-0 text-end"
                            >Current library:</label
                        >
                        <div class="col-2">
                            <FormInput type="select" name="holdingbranch_op">
                                <option value="=">is</option>
                                <option value="!=">is not</option>
                            </FormInput>
                        </div>
                        <div class="col-8">
                            <multiselect
                                v-model="holdingbranchs"
                                track-by="id"
                                :options="libraries"
                                :multiple="true"
                                :searchable="false"
                                label="name"
                                placeholder=""
                            ></multiselect>
                        </div>
                    </div>
                    <div class="form-group row mb-0 align-items-center">
                        <label
                            for="code"
                            class="col-2 col-form-label px-0 text-end mb-0"
                            >Shelving location:</label
                        >
                        <div class="col-2">
                            <FormInput type="select" name="loc_op">
                                <option value="=">is</option>
                                <option value="!=">is not</option>
                            </FormInput>
                        </div>
                        <div class="col-8">
                            <multiselect
                                v-model="locs"
                                track-by="id"
                                :options="shelveLocations"
                                :multiple="true"
                                :searchable="false"
                                label="value"
                                placeholder=""
                            ></multiselect>
                        </div>
                    </div>
                </div>
                <div class="page-section mt-3 pb-0">
                    <div class="form-group row mb-0">
                        <label
                            for="code"
                            class="col-2 col-form-label px-0 text-end"
                            >Collection:</label
                        >
                        <div class="col-2">
                            <FormInput type="select" name="collection_op">
                                <option value="=">is</option>
                                <option value="!=">is not</option>
                            </FormInput>
                        </div>
                        <div class="col-8">
                            <multiselect
                                v-model="ccodes"
                                track-by="id"
                                :options="collections"
                                :multiple="true"
                                :searchable="false"
                                label="value"
                                placeholder=""
                            ></multiselect>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label
                            for="code"
                            class="col-2 col-form-label px-0 text-end"
                            >Status:</label
                        >
                        <div class="col-2">
                            <FormInput type="select" name="abc">
                                <option value="=">is</option>
                                <option value="!=">is not</option>
                            </FormInput>
                        </div>
                        <div class="col-8">
                            <multiselect
                                v-model="locs"
                                track-by="id"
                                :options="[
                                    { id: 1, name: 'lost' },
                                    { id: 2, name: 'reserved' },
                                ]"
                                :multiple="true"
                                :searchable="false"
                                label="name"
                                placeholder=""
                            ></multiselect>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label
                            for="code"
                            class="col-2 col-form-label px-0 text-end"
                            >Damaged:</label
                        >
                        <div class="col-2">
                            <FormInput type="select" name="abc">
                                <option value="=">is</option>
                                <option value="!=">is not</option>
                            </FormInput>
                        </div>
                        <div class="col-8">
                            <multiselect
                                track-by="id"
                                :options="[{ id: 1, name: 'damaged' }]"
                                :multiple="true"
                                :searchable="false"
                                label="name"
                                placeholder=""
                            ></multiselect>
                        </div>
                    </div>
                </div>
                <div class="page-section mt-3 pb-0 fields">
                    <div
                        v-for="(item, index) in fields"
                        :key="index"
                        class="form-group row mb-3"
                    >
                        <div class="col-2">
                            <select
                                class="form-control"
                                v-if="index != 0"
                                v-model="item.c"
                            >
                                <option value="and">AND</option>
                                <option value="or">OR</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <select v-model="item.f" class="form-control">
                                <option selected value="barcode">
                                    Barcode
                                </option>
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                                <option value="publisher">Publisher</option>
                                <option value="publicationdate">
                                    Publication date
                                </option>
                                <option value="isbn">ISBN</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <select v-model="item.op" class="form-control">
                                <option value="=">is</option>
                                <option value="!=">is not</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <input
                                class="form-control"
                                style="height: 37px"
                                type="text"
                                v-model="item.q"
                            />
                        </div>
                        <div v-if="index === fields.length - 1" class="col-2">
                            <a
                                @click="
                                    fields.push({
                                        c: 'and',
                                        f: '',
                                        op: '',
                                        q: '',
                                    })
                                "
                                class="action"
                                title="Add a new field"
                                ><i class="fa fa-plus"></i> New field</a
                            >
                        </div>
                    </div>
                </div>
                <div class="page-section mt-3 pb-0">
                    <div class="form-group row mb-0">
                        <label
                            for="code"
                            class="col-2 col-form-label px-0 text-end"
                            >Checkout count:</label
                        >
                        <div class="col-2">
                            <FormInput type="select" name="checkout_op">
                                <option selected value="=">></option>
                                <option value="!="><</option>
                                <option value="!=">=</option>
                                <option value="!=">!=</option>
                            </FormInput>
                        </div>
                        <div class="col-4">
                            <FormInput type="text" name="checkout" />
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label
                            for="code"
                            class="col-2 col-form-label px-0 text-end"
                            >Last checkout date:</label
                        >
                        <div class="col-2">
                            <FormInput type="select" name="lastcheckout_op">
                                <option selected value="=">After</option>
                                <option value="!=">Before</option>
                                <option value="!=">On</option>
                            </FormInput>
                        </div>
                        <div class="col-4">
                            <FormInput type="text" name="lastcheckout" />
                        </div>
                    </div>
                </div>
            </Form>
        </div>
    </div>
    <div v-if="results" class="row">
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Publication date</th>
                                    <th>Publisher</th>
                                    <th>Collection</th>
                                    <th>Barcode</th>
                                    <th>Home Library</th>
                                    <th>Current Library</th>
                                    <th>Shelving location</th>
                                    <th>Status</th>
                                    <th>Checkouts</th>
                                    <th>Last checkout date</th>
                                    <th>Due date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in results" :key="item.id">
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'book',
                                                params: { id: item.book_id },
                                            }"
                                        >
                                            {{ item.title }}
                                        </RouterLink>
                                        {{ item.authors }}
                                    </td>
                                    <td>{{ item.published_date }}</td>
                                    <td>
                                        {{ item.publisher }}
                                    </td>
                                    <td>collection</td>
                                    <td>{{ item.barcode }}</td>
                                    <td>{{ item.home_library }}</td>
                                    <td>{{ item.current_location }}</td>
                                    <td>{{ item.shelve_location }}</td>
                                    <td>{{ item.status }}</td>
                                    <td>{{ item.checkouts }}</td>
                                    <td>{{ item.last_seen }}</td>
                                    <td>due date</td>
                                    <td>
                                        <RouterLink
                                            class="me-2"
                                            :to="{
                                                name: 'book-items',
                                                params: { id: item.book_id },
                                            }"
                                        >
                                            Edit item
                                        </RouterLink>
                                        <RouterLink
                                            :to="{
                                                name: 'book',
                                                params: { id: item.book_id },
                                            }"
                                        >
                                            Edit record
                                        </RouterLink>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
div.form-group {
    margin-bottom: 0;
}
.fields .form-group {
    margin-bottom: initial;
}
::v-deep(select.form-control) {
    height: 37px;
}
</style>
