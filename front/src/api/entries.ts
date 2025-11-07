import { http } from "./http";

export interface Entries {
    id: number,
    kind: string,
    description: string,
    amount: number,
    date: Date,
}

export async function getEntriesFromCard(id: string): Promise<Entries[]> {
    try {
        const { data } = await http.get<Entries[]>(`/api/entries/${id}`)
        return data
    } catch (e) {
        throw('Erro ao buscar entries desse card: ' + e)
    }
}

export async function insertEntryInCard(id: string) {
    try {
        const { data } = await http.post<Entries>(`/api/entries/${id}`)
        return data
    } catch (e) {
        throw('Erro ao inserir entry para esse card: ' + e)
    }
}