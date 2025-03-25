<script>
export default {
    name: "pop-up",
    props: {
        isOpen: {
            type: Boolean,
            default: false,
        },
        maxWidth: {
            type: String,
            default: "500px",
        },
        height: {
            type: String,
            default: "auto",
        },
        zIndex: {
            type: Number,
            default: 1000,
        },
    },
};
</script>
<template>
    <div
        :style="{ zIndex: zIndex }"
        v-if="isOpen"
        class="popup-overlay"
        @click.self="close"
    >
        <div
            class="popup-content"
            :style="{ maxWidth: maxWidth, height: height }"
        >
            <slot></slot>
        </div>
    </div>
</template>
<style scoped>
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.popup-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
    overflow-y: scroll;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 15px;
}

input {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.popup-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}
</style>
