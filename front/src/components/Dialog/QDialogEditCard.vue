<template>
  <q-dialog v-model="dialogModel">
    <q-card style="min-width: 480px">
      <q-card-section>
        <div class="row items-center q-gutter-sm">
          <q-icon name="info" />
          <div class="text-h6">Editar card</div>
        </div>
        Adicione um novo card para controlar suas financias
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-md">
        <q-input outlined label="Nome do card" v-model="form.cardName" />
        <q-input outlined label="Cor do card" v-model="form.cardColor">
          <template v-slot:append>
            <q-icon name="colorize" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-color v-model="form.cardColor" />
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </q-card-section>
      <q-separator />
      <q-card-actions class="q-pa-md" align="right">
        <q-btn color="primary" label="Adicionar" @click="emit('editCard', form.id)" />
        <q-btn color="negative" outline label="Fechar" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
const form = defineModel<{
    id: string
    cardName: string,
    cardColor: string
}>({
    type: Object,
    required: true
})

const dialogModel = defineModel<boolean>('dialog')

const emit = defineEmits<{
    (e: 'editCard', id: string): void
}>()

</script>