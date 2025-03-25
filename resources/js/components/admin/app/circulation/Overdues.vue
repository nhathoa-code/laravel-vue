<script setup>
import { onMounted, ref } from "vue";
import { RouterLink } from "vue-router";
import { CirculationService } from "@root/services/circulation/CirculationService";
const overdues = ref(null);
onMounted(() => {
    CirculationService.getOverdues((res) => {
        overdues.value = res.data;
    });
});
</script>
<template>
    <div class="row">
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Patron</th>
                                    <th>Book</th>
                                    <th>Checkout</th>
                                    <th>Due date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in overdues" :key="item.id">
                                    <td>
                                        <RouterLink to="">
                                            {{ item.patron.name }}
                                        </RouterLink>
                                    </td>
                                    <td>
                                        <RouterLink to="">
                                            {{ item.book_item.book.title }}
                                        </RouterLink>
                                    </td>
                                    <td>
                                        {{ item.checkout_date }}
                                    </td>
                                    <td>
                                        {{ item.due_date }}
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
