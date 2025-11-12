<template>
  <q-dialog v-model="dialogModel">
    <q-card style="min-width: 480px">
      <q-card-section>
        <div class="row items-center q-gutter-sm">
          <q-icon name="info" />
          <div class="text-h6">Deletar card</div>
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-md">
        <div class="text-body1">Você está prestes a deletar um card. Todos os dados relacionados a esse card serão deletados. Você tem certeza?</div>
      </q-card-section>
      <q-separator />
      <q-card-actions class="q-pa-md" align="right">
        <q-btn color="primary" label="Deletar" @click="emit('deleteCard', props.id)" />
        <q-btn color="negative" outline label="Fechar" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
const dialogModel = defineModel<boolean>('dialog')

const props = defineProps<{id: string}>()

const emit = defineEmits<{
    (e: 'deleteCard', id: string): void
}>()
</script>