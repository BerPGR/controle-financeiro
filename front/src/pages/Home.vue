<template>
    <q-page padding>
        <div class="row q-col-gutter-md items-stretch">
            <div class="col-12 col-md-8">
                <q-card class="q-pa-md">
                    <q-card-section>
                        <div class="text-h5 q-mb-xs">Bem-vindo ao seu controle financeiro</div>
                        <div class="text-subtitle2">Acompanhe aqui seus gastos mensais e os calculos
                        </div>
                    </q-card-section>
                    <q-separator />
                    <q-card-section>
                        <q-btn color="accent" icon="add" label="Adicionar card" class="q-mr-sm"
                            @click="dialog = true" />
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-md-4">
                <q-card class="bg-primary text-white">
                    <q-card-section>
                        <div class="text-h6">Status</div>
                        <div>Modo: <strong>{{ isDark ? 'Escuro' : 'Claro' }}</strong></div>
                        <div>Itens: <strong>{{ rows.length }}</strong></div>
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <div class="text-h3 text-weight-bold q-mt-xl">Seus Cards</div>
        <div class="row wrap q-gutter-md items-center q-mt-xs">
            <q-card v-for="i in 5" class="col-12 col-md-3 shadow-4 ">
                <q-card-section>
                    <div>card 1</div>
                    <div>card 2</div>
                    <div>card 3</div>
                </q-card-section>
                <q-card-actions align="right">
                    <q-btn label="Acessar" color="primary" />
                    <q-btn outline color="negative" icon='delete' label="Deletar" />
                </q-card-actions>
            </q-card>
        </div>

        <q-dialog v-model="dialog">
            <q-card style="min-width: 320px">
                <q-card-section class="row items-center q-gutter-sm">
                    <q-icon name="info" />
                    <div class="text-h6">Sobre este exemplo</div>
                </q-card-section>
                <q-card-section>
                    Este arquivo usa Quasar via CDN (sem build). Para projetos reais, prefira o
                    Quasar CLI com Vite para ter tree-shaking, rotas, i18n, etc.
                </q-card-section>
                <q-card-actions align="right">
                    <q-btn color="accent" label="Adicionar" />
                    <q-btn color="negative" outline label="Fechar" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>

    </q-page>
</template>

<script setup>
import { useQuasar } from 'quasar'
import { computed, ref } from 'vue'

const $q = useQuasar()
const leftDrawerOpen = ref(true)
const filter = ref('')
const dialog = ref(false)
const isDark = computed(() => $q.dark.isActive)

const columns = [
    { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
    { name: 'name', label: 'Nome', field: 'name', align: 'left', sortable: true },
    { name: 'age', label: 'Idade', field: 'age', align: 'right', sortable: true },
    { name: 'city', label: 'Cidade', field: 'city', align: 'left' },
    { name: 'actions', label: 'Ações', field: 'actions', align: 'center' }
]

const rows = ref([
    { id: 1, name: 'Ana', age: 28, city: 'São Paulo' },
    { id: 2, name: 'Bruno', age: 34, city: 'Rio de Janeiro' },
    { id: 3, name: 'Carla', age: 22, city: 'Belo Horizonte' },
    { id: 4, name: 'Diego', age: 41, city: 'Curitiba' },
    { id: 5, name: 'Elaine', age: 30, city: 'Porto Alegre' },
    { id: 6, name: 'Felipe', age: 27, city: 'Recife' }
])

const form = ref({ name: '', age: null, city: '' })

function notify(msg) {
    $q.notify({ message: msg, position: 'top', color: 'primary' })
}
function viewUser(row) {
    $q.dialog({
        title: 'Usuário',
        message: `${row.name}, ${row.age} anos — ${row.city}`
    })
}
function deleteUser(id) {
    rows.value = rows.value.filter(r => r.id !== id)
    notify('Removido!')
}
function addUser() {
    const id = rows.value.length ? Math.max(...rows.value.map(r => r.id)) + 1 : 1
    rows.value.push({ id, ...form.value })
    resetForm()
    notify('Usuário adicionado!')
}
function resetForm() {
    form.value = { name: '', age: null, city: '' }
}
function reload() {
    notify('Recarregado (mock)!')
}
function toggleDark() {
    $q.dark.set(!$q.dark.isActive)
}
</script>