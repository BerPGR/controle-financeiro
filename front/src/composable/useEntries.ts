import { ref } from "vue";
import {
  type Entries,
  getEntriesFromCard,
  insertEntryInCard,
  deleteEntryById,
} from "../api/entries";
import type { CreateEntryKind } from "../types/types";
import { notify } from "../util/utils";

export default function useEntries() {
  const entries = ref<Entries[] | []>([]);
  const dialog = ref<boolean>(false);

  interface EntryForm {
    kind: CreateEntryKind;
    description: string;
    amount: number;
    date: string;
    category: string;
  }

  const form = ref<EntryForm>({
    kind: {
      label: "",
      value: "",
    },
    description: "",
    amount: 0,
    date: `${new Date().getDate}/${new Date().getMonth}/${new Date().getMonth}`,
    category: "",
  });

  const loadEntries = async (cardId: string) => {
    try {
      const data = await getEntriesFromCard(cardId);
      entries.value = Array.isArray(data)
        ? data
        : typeof data === "string" && (data as string).trim()
        ? JSON.parse(data)
        : [];
    } catch (e) {
      throw "Não foi possível buscar entradas para esse card";
    }
  };

  const addNewEntry = async (cardId: string) => {
    try {
      const data = await insertEntryInCard(cardId, {
        kind: form.value.kind.value,
        amount: form.value.amount,
        description: form.value.description,
        category: form.value.category,
        date: form.value.date,
      });
      dialog.value = false;
      await loadEntries(cardId);
    } catch (e) {
      console.log(e);
    }
  };

  const deleteEntry = async (entry: object[], cardId: string) => {
    try {
      if (entry.length === 0) {
        notify("Não é possível deletar registro nenhum!", "negative", "error");
        return;
      }
      const convertEntry = entry[0] as Entries;
      const data = await deleteEntryById(convertEntry.id);
      if (data.status === 204) {
        notify(data.message, "primary", "check");
        await loadEntries(cardId);
      }
    } catch (e) {
      console.log(e);
    }
  };

  return { loadEntries, addNewEntry, deleteEntry, entries, dialog, form };
}
