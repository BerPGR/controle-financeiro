import { http } from "./http";

export interface Entries {
    id: number,
    kind: string,
    description: string,
    amount: number,
    date: Date,
    category: string
}

export async function getEntriesFromCard(id: string): Promise<Entries[]> {
    try {
        const { data } = await http.get<Entries[]>(`/api/cards/${id}/entries`)
        return data
    } catch (e) {
        throw('Erro ao buscar entries desse card: ' + e)
    }
}

export async function insertEntryInCard(id: string, payload: {kind: string, amount: number, description: string, category: string, date: string}) {
    try {
        const { data } = await http.post<Entries>(`/api/cards/${id}/entries`, payload)
        return data
    } catch (e) {
        throw('Erro ao inserir entry para esse card: ' + e)
    }
}