<template>
  <q-dialog v-model="dialogModel">
    <q-card style="min-width: 480px">
      <q-card-section>
        <div class="row items-center q-gutter-sm">
          <q-icon name="info" />
          <div class="text-h6">Adicionar rendimento</div>
        </div>
        Adicione um rendimento para controlar suas financias
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-md">
        <q-input outlined label="Valor do rendimento" v-model="form.value" type="number" />
      </q-card-section>
      <q-separator />
      <q-card-actions class="q-pa-md" align="right">
        <q-btn color="primary" label="Adicionar" @click="emit('insertNewIncome')" />
        <q-btn color="negative" outline label="Fechar" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
const form = defineModel<{
    value: number
}>({
    type: Object,
    required: true
})

const dialogModel = defineModel<boolean>('dialog')

const emit = defineEmits<{
    (e: 'insertNewIncome'): void
}>()

</script>