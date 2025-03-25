<script>
export default {
    name: "spinner",
    props: {
        visible: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
<template>
    <div class="spinner" v-if="visible"></div>
</template>
<style scoped>
.spinner {
    width: 25px;
    height: 25px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #589d43;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    display: inline-block;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
