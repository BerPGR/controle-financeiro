import { ref } from "vue";
import { getCards, insertCard, updateCard, deleteCardById, type Cards } from "../api/cards";
import { useAuthStore } from "../store/useAuth";

export default function useCards() {
  const { user } = useAuthStore()
  const cards = ref<Cards[] | []>([]);
  const editDialog = ref<boolean>(false)
  const deleteDialog = ref<boolean>(false)
  const dialog = ref<boolean>(false);
  
  interface CardForm {
    cardName: string;
    cardColor: string;
  }

  const selectedCard = ref({
    id: '',
    cardName: "",
    cardColor: ""
  })

  const cardForm = ref<CardForm>({
    cardName: "",
    cardColor: "",
  });

  const selectCard = (cardId: string, name: string, color: string) => {
    selectedCard.value.id = cardId
    selectedCard.value.cardName = name
    selectedCard.value.cardColor = color
    editDialog.value = true
  }

  const loadCards = async () => {
    try {
      const data = await getCards(user.id);
      cards.value = Array.isArray(data)
        ? data
        : typeof data === "string" && (data as string).trim()
        ? JSON.parse(data)
        : [];
    } catch (e) {
      console.log(e);
    }
  };

  const addCard = async () => {
    try {
      const data = await insertCard({
        name: cardForm.value.cardName,
        color: cardForm.value.cardColor,
        user_id: user.id
      });
      dialog.value = false;
      await loadCards();
    } catch (e) {
      console.log(e);
    }
  };

  const editCard = async (cardId: string) => {
    try {
      const data = await updateCard(cardId, {
        name: selectedCard.value.cardName,
        color: selectedCard.value.cardColor
      })
      editDialog.value = false
      await loadCards()
    } catch (e) {
      console.log(e);
    }
  }

  const deleteCard = async (cardId: string) => {
    try {
      const data = await deleteCardById(cardId)
      deleteDialog.value = false
      await loadCards()
    } catch (e) {
      console.log(e);
    }
  }

  return { loadCards, addCard, editCard, selectCard, deleteCard, selectedCard, cards, dialog, editDialog, deleteDialog, cardForm};
}
