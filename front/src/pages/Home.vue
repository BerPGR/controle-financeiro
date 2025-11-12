<template>
  <q-page padding>
    <div class="row q-col-gutter-md items-stretch">
      <div class="col-12 col-md-8">
        <q-card class="q-pa-md">
          <q-card-section>
            <div class="text-h5 q-mb-xs">
              Bem-vindo ao seu controle financeiro
            </div>
            <div class="text-subtitle2">
              Acompanhe aqui seus gastos mensais e os calculos
            </div>
          </q-card-section>
          <q-separator />
          <q-card-section>
            <q-btn
              color="primary"
              icon="add"
              label="Adicionar card"
              class="q-mr-sm"
              @click="dialog = true"
            />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-4 q-gutter-md">
        <div>
          <q-card class="bg-primary text-white">
            <q-card-section>
              <div class="text-h6 text-weight-bold">Status</div>
              <div class="text-body1">Nº de Cards: {{ cards.length }}</div>
            </q-card-section>
          </q-card>
        </div>
        <div>
          <q-card class="bg-primary text-white">
            <q-card-section>
              <div class="row items-center justify-between">
                <div class="text-h6 text-weight-bold">Total recebido neste mês</div>
                <q-btn round flat icon="add" @click="incomeDialog = true"/>
              </div>
              <div class="text-body1">{{  formattedValue(monthlyIncome) }}</div>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>

    <div class="text-h3 text-weight-bold q-mt-xl">Seus Cards</div>
    <div class="row wrap q-gutter-md items-center q-mt-xs">
      <q-card
        v-for="card in cards"
        class="col-12 col-md-3 shadow-4"
        :style="{
          backgroundColor: card.color,
          color: getTextColorFromDB(card.color),
        }"
      >
        <q-card-section>
          <div class="row items-center justify-between">
            <div class="text-h5 text-weight-bold">{{ card.name }}</div>
            <q-btn flat round icon="menu">
              <q-menu
                :offset="[120, 0]"
                transition-show="jump-down"
                transition-hide="jump-up"
              >
                <q-list>
                  <q-item clickable v-ripple v-close-popup @click="selectCard(card.id, card.name, card.color)">
                    <q-item-section>Editar</q-item-section>
                    <q-item-section avatar>
                      <q-icon name="edit" />
                    </q-item-section>
                  </q-item>
                  <q-item clickable v-ripple v-close-popup @click="openDeleteDialog(card.id)">
                    <q-item-section>Apagar card</q-item-section>
                    <q-item-section avatar>
                      <q-icon name="delete" />
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-menu>
            </q-btn>
          </div>
          <div class="text-body1">
            Criado em {{ formatDate(card.created_at) }}
          </div>
        </q-card-section>
        <q-separator />
        <q-card-actions align="right">
          <q-btn
            @click="goToEntriesPage(card)"
            flat
            label="Acessar"
            :style="{
              color: getTextColorFromDB(card.color),
              border: `1px solid ${getTextColorFromDB(card.color)}`,
              borderRadius: '5px',
            }"
          />
        </q-card-actions>
      </q-card>
    </div>

    <QDialogCreateCard
      @addCard="addCard"
      v-model="cardForm"
      v-model:dialog="dialog"
    />

    <QDialogEditCard @editCard="editCard(selectedCard.id)" v-model="selectedCard" v-model:dialog="editDialog"/>

    <QDialogDeleteCard @deleteCard="deleteCard(idCardDelete)" :id="idCardDelete" v-model:dialog="deleteDialog"/>

    <QDialogInsertIncome @insertNewIncome="newIncome" v-model:dialog="incomeDialog" v-model="incomeForm"/>
  </q-page>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import QDialogCreateCard from "../components/Dialog/QDialogCreateCard.vue";
import { formattedValue, getTextColorFromDB } from "../util/utils";
import { useCardStore } from "../store/cardStore";
import { useRouter } from "vue-router";
import type { Cards } from "../api/cards";
import useCards from "../composable/useCards";
import QDialogEditCard from "../components/Dialog/QDialogEditCard.vue";
import QDialogDeleteCard from "../components/Dialog/QDialogDeleteCard.vue";
import useIncome from "../composable/useIncome";
import QDialogInsertIncome from "../components/Dialog/QDialogInsertIncome.vue";

const router = useRouter()
const { loadCards, addCard, editCard, selectCard, deleteCard, selectedCard, cardForm, cards, dialog, editDialog, deleteDialog } = useCards()
const {incomeForm, loadIncome, monthlyIncome, newIncome, incomeDialog} = useIncome()
const { setCardData } = useCardStore();

const idCardDelete = ref("")

onMounted(async () => {
  await loadCards();
  await loadIncome();
});

const goToEntriesPage = async (card: Cards) => {
  await setCardData(card.id, card.name, card.color);
  router.push(`/entries/${card.id}`);
};

const dateFormatter = Intl.DateTimeFormat("pt-BR", {
  day: "2-digit",
  month: "2-digit",
  year: "numeric",
});

const formatDate = (date: Date | string) => {
  return dateFormatter.format(new Date(date));
};

const openDeleteDialog = (id: string) => {
  idCardDelete.value = id
  deleteDialog.value = true
}
</script>
