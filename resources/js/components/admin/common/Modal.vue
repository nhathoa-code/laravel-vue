<script>
export default {
    name: "modal",
    props: {
        open: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
<template>
    <div
        class="modal printable in"
        :style="{ display: open ? 'block' : 'none' }"
    >
        <div class="modal-dialog focused" role="document">
            <div style="width: 700px; height: 300px" class="modal-content">
                <div class="modal-header">
                    <slot name="header"></slot>
                    <button
                        type="button"
                        class="closebtn"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.modal-open .modal {
    overflow-x: hidden;
    overflow-y: auto;
}
.modal {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1050;
    display: none;
    overflow: hidden;
    -webkit-overflow-scrolling: touch;
    outline: 0;
    background-color: rgba(0, 0, 0, 0.5);
}
.modal.in .modal-dialog {
    transform: translate(0, 0);
}
.modal-dialog {
    position: relative;
    width: auto;
    margin: 10px;
    display: flex;
    justify-content: center;
}
.modal-content {
    background-color: #dcefd0 !important;
}
.modal-content {
    position: relative;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #999;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 6px;
    -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
    box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
    outline: 0;
}
@media (min-width: 768px) {
    .modal-dialog {
        width: 600px;
        margin: 30px auto;
    }
}
.modal-header {
    border-bottom: 1px solid #6faf44 !important;
}
.modal-header {
    padding: 15px;
    border-bottom: 1px solid #e5e5e5;
}
.modal-title {
    margin: 0;
    line-height: 1.42857143;
}
button.closebtn {
    background: rgba(0, 0, 0, 0);
    border: 0;
    cursor: pointer;
    padding: 0;
}
.closebtn:focus,
.closebtn:hover {
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.6);
}

button:hover,
.btn:hover {
    background-color: #e0e0e0;
}
.closebtn {
    color: #000;
    cursor: pointer;
    float: right;
    font-size: 21px;
    font-weight: bold;
    line-height: 1;
    margin-top: 4px;
    text-decoration: none;
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
}
.modal-body {
    background-color: #fff;
    overflow-y: auto;
}
.modal-body {
    position: relative;
    padding: 15px;
}
.modal-footer {
    border-top: 1px solid #6faf44 !important;
}
.modal-footer {
    text-align: right;
    border-top: 1px solid #e5e5e5;
}
</style>
