import { ref } from 'vue'
import {getIncomes, insertIncome} from '../api/income'
 
export default function useIncome() {
    const monthlyIncome = ref(0)
    const incomeDialog = ref<boolean>(false)

    const incomeForm = ref({
        value: 0.0
    })

    const newIncome = async () => {
        const [year, month, day] = new Date().toISOString().split('T')[0]!.split('-')

        try {
            const data = await insertIncome({
                value: incomeForm.value.value,
                date: `${year}-${month}-${day}`
            })
            incomeDialog.value = false
            await loadIncome()
        } catch (e) {
            console.log(e);
        }
    }

    const loadIncome = async () => {
        try {
            const data = await getIncomes()
            monthlyIncome.value = typeof data === 'number' ? data : data !== null ? parseFloat(data) : 0 
        } catch (e) {
            console.log(e);
        }
    }

    return {newIncome, loadIncome, monthlyIncome, incomeForm, incomeDialog}
}