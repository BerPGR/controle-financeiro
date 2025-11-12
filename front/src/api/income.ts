import { http } from "./http";

export async function getIncomes() {
    try {
        const { data } = await http.get('/api/income/')
        return data
    } catch (e) {
        console.log(e);
    }
}

export async function insertIncome(payload: {value: number, date: string}) {
    try {
        await http.post('/api/income', payload)
    } catch (e) {
        console.log(e);
    }
}