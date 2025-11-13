import { http } from "./http"; 

export interface Cards { 
    id: string, 
    name: string, 
    color: string, 
    created_at: Date 
} 

export async function getCards(): Promise<Cards[]> { 
    try { 
        const { data } = await http.get<Cards[]>('/api/cards') 
        return data 
    } catch (e) { 
        throw('Deu ruim' + e) 
    } 
} 
    
export async function insertCard(payload: { name: string, color: string }) { 
    const { data } = await http.post<Cards>('/api/cards', payload) 
    return data 
}

export async function updateCard(cardId: string, payload: {name: string, color: string}) {
    const { data } = await http.put(`/api/cards/${cardId}`, payload)
    return data
}

export async function deleteCardById(cardId: string) {
    const { data } = await http.delete(`/api/cards/${cardId}`)
    return data
}

export async function getSummary() {
    const { data } = await http.get('/api/cards/summary')
    return data
}