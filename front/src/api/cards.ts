import { http } from "./http"; 

export interface Cards { 
    id: number, 
    name: string, 
    color: string, 
    createdAt: Date 
} 

export async function getCards(): Promise<Cards[]> { 
    try { 
        const { data } = await http.get<Cards[]>('/api/cards') 
        return data 
    } catch (e) { 
        console.log(e); 
        throw('Deu ruim' + e) 
    } 
} 
    
export async function insertCard(payload: { name: string, color: string }) { 
    const {data} = await http.post<Cards>('/api/cards', payload) 
    return data 
}