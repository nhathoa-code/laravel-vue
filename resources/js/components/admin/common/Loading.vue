<script>
import ProgressSpinner from "primevue/progressspinner";
export default {
    name: "overlay",
    components: {
        ProgressSpinner,
    },
    props: {
        visible: {
            type: Boolean,
            default: false,
        },
        zIndex: {
            type: Number,
            default: 1001,
        },
    },
};
</script>
<template>
    <div :style="{ zIndex: zIndex }" v-if="visible" class="overlay">
        <div class="overlay-content">
            <ProgressSpinner
                style="width: 80px; height: 80px"
                strokeWidth="1"
                fill="transparent"
                animationDuration=".5s"
                aria-label="Custom ProgressSpinner"
            />
        </div>
    </div>
</template>
<style scoped>
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: transparent;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.overlay-content {
    background: transparent;
    padding: 20px;
    max-width: 80%;
    max-height: 80%;
    overflow-y: auto;
}
</style>
