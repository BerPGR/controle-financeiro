import { ref } from "vue";
import { getSummary } from "../api/cards";

export default function useSummary() {
  const barChartOptions = ref({
    chart: {
      type: "column",
    },
    title: {
      text: "Investimentos x Despesas",
    },
    xAxis: {
      categories: [],
      crosshair: true,
    },
    yAxis: {
      min: 0,
      title: {
        text: "Quantidade",
      },
    },
    series: [
      {
        name: "Rendimentos",
        data: [],
      },
      {
        name: "Despesas",
        data: [],
      },
    ],
  });

  const loadSummary = async () => {
    try {
      const data = await getSummary();

      const categories = data.map((card: { name: any }) => card.name);
      const incomes = data.map(
        (card: { total_income: number }) => Number(card.total_income) || 0
      );
      const expenses = data.map(
        (card: { total_expense: number }) => Number(card.total_expense) || 0
      );

      barChartOptions.value.xAxis.categories = categories;
      barChartOptions.value.series[0]!.data = incomes;
      barChartOptions.value.series[1]!.data = expenses;

      barChartOptions.value = { ...barChartOptions.value };
    } catch (e) {
      console.log(e);
    }
  };

  return { loadSummary, barChartOptions };
}
