<script setup>
import Spinner from "@admin/common/Spinner.vue";
defineProps({
    name: { type: String, required: true },
    submitting: { type: Boolean, default: false },
    cancel: { type: Boolean, default: true },
    class: { type: String, default: "" },
    icon: { type: String, default: null },
    style: { type: String, default: "" },
});
</script>
<template>
    <div class="d-flex align-items-center gap-3">
        <button
            :disabled="submitting"
            type="submit"
            :class="class"
            class="btn btn-secondary btn-sm m-0"
            :style="style"
        >
            <span v-html="icon"></span>
            {{ name }}
        </button>
        <a v-if="cancel" class="cancel">Cancel</a>
        <spinner :visible="submitting"></spinner>
    </div>
</template>
