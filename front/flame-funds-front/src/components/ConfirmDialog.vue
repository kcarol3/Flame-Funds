<template>
  <Dialog v-model:visible="visible" modal :header="header" :style="{ width: '350px' }">
    <h2 class="lilita-one">
      {{message}}
    </h2>
    <template #footer>
      <Button label="anuluj" icon="bi bi-times" @click="cancel" text />
      <button type="button" class="button-primary" @click="confirmAction" style="font-size: 18px">{{ actionName }}</button>
    </template>
  </Dialog>
</template>
<script>
export default {
  name: "ConfirmDialog",
  props: {
    visible: Boolean,
    message: String,
    actionName: String,
    confirm: Function,
    cancel: Function,
  },

  methods:{
    confirmAction() {
      if (typeof this.confirm === "function") {
        this.confirm();
      }
    },
    cancelAction() {
      if (typeof this.cancel === "function") {
        this.cancel();
      }
    },
},
}
</script>
<style scoped>

</style>