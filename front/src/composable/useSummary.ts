import { ref } from "vue";
import { getSummary } from "../api/cards";
import type { Options, SeriesLineOptions } from "highcharts";

export default function useSummary() {
  interface MonthlyExpenseRow {
    card_name: string;
    year: number;
    month: number;
    month_reference: string;
    total_expense: number | string;
  }

  const pieChartTotalOptions = ref({
    chart: {
      type: "pie",
      panning: {
        enabled: true,
        type: "xy",
      },
      panKey: "shift",
    },
    title: {
      text: "Rendimento x Despesa Total",
    },
    tooltip: {
      valuePrefix: "R$",
    },
    plotOptions: {
      pie: {
        allowPointSelect: true,
        cursor: "pointer",
        dataLabels: [
          {
            enabled: true,
            distance: 20,
            style: {
              fontSize: "0.8rem",
            },
          },
          {
            enabled: true,
            distance: -40,
            format: "{point.percentage:.1f}%",
            style: {
              fontSize: "1em",
              textOutline: "none",
              opacity: 0.7,
            },
            filter: {
              operator: ">",
              property: "percentage",
              value: 10,
            },
          },
        ],
      },
    },
    series: [
      {
        name: "Valor Total",
        colorByPoint: true,
        data: [
          {
            name: "Rendimento",
            y: 0,
            sliced: true,
          },
          {
            name: "Despesas",
            y: 0,
          },
        ],
      },
    ],
  });

  const pieChartTotalExpenseCardsOptions = ref({
    chart: {
      type: "pie",
      panning: {
        enabled: true,
        type: "xy",
      },
      panKey: "shift",
    },
    title: {
      text: "Despesa total por card",
    },
    tooltip: {
      valuePrefix: "R$",
    },
    plotOptions: {
      pie: {
        allowPointSelect: true,
        cursor: "pointer",
        dataLabels: [
          {
            enabled: true,
            distance: 20,
            style: {
              fontSize: "0.8rem",
            },
          },
          {
            enabled: true,
            distance: -40,
            format: "{point.percentage:.1f}%",
            style: {
              fontSize: "1em",
              textOutline: "none",
              opacity: 0.7,
            },
            filter: {
              operator: ">",
              property: "percentage",
              value: 10,
            },
          },
        ],
      },
    },
    noData: {
      style: {
        fontSize: "16px",
        fontWeight: "bold",
      },
    },
    lang: {
      noData: "Nenhum dado disponível para esse card!",
    },
    series: [
      {
        name: "Valor Total por Card",
        colorByPoint: true,
        data: [],
      },
    ],
  });

  const barChartOptions = ref({
    chart: {
      type: "column",
    },
    title: {
      text: "Investimentos x Despesas por Card",
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

  const lineMonthlyExpenseChart = ref<Options>({
    title: {
      text: "Despesa Mensal por Card",
    },

    yAxis: {
      title: {
        text: "Gasto do Mês",
      },
      labels: {
        format: "R${value}",
      },
    },

    xAxis: {
      categories: [
        "Jan",
        "Fev",
        "Mar",
        "Abr",
        "Mai",
        "Jun",
        "Jul",
        "Ago",
        "Set",
        "Out",
        "Nov",
        "Dez",
      ],
    },

    plotOptions: {
      series: {
        label: {
          connectorAllowed: false,
        },
        pointStart: "Jan",
      },
    },

    series: [] as SeriesLineOptions[],
  });

  const loadSummary = async () => {
    try {
      const data = await getSummary();

      // Maps para o barChart
      const categories = data.chart.map((card: { name: string }) => card.name);
      const incomes = data.chart.map(
        (card: { total_income: number }) => Number(card.total_income) || 0
      );
      const expenses = data.chart.map(
        (card: { total_expense: number }) => Number(card.total_expense) || 0
      );

      // Maps para o pieChart
      const allIncome = data.pie.all_income;
      const allExpense = data.pie.all_expense;

      const lineSeries = formatForLineChart(data.lineChart);

      pieChartTotalOptions.value.series[0]!.data[0]!.y = allIncome;
      pieChartTotalOptions.value.series[0]!.data[1]!.y = allExpense;

      pieChartTotalExpenseCardsOptions.value.series[0]!.data = data.pieCard.map(
        (card: { name: string; total_expense: number }) => ({
          name: card.name,
          y: Number(card.total_expense),
        })
      );

      barChartOptions.value.xAxis.categories = categories;
      barChartOptions.value.series[0]!.data = incomes;
      barChartOptions.value.series[1]!.data = expenses;

      barChartOptions.value = { ...barChartOptions.value };
      pieChartTotalOptions.value = { ...pieChartTotalOptions.value };
      pieChartTotalExpenseCardsOptions.value = {
        ...pieChartTotalExpenseCardsOptions.value,
      };

      lineMonthlyExpenseChart.value = {
        ...lineMonthlyExpenseChart.value,
        series: lineSeries,
      };
    } catch (e) {
      console.log(e);
    }
  };

  const formatForLineChart = (
    rows: MonthlyExpenseRow[]
  ): SeriesLineOptions[] => {
    const porCard: Record<string, number[]> = {};

    for (const row of rows) {
      const name = row.card_name;
      const monthIndex = row.month - 1; // 0–11
      const expense = Number(row.total_expense) || 0;

      if (!porCard[name]) {
        porCard[name] = Array(12).fill(0);
      }

      porCard[name][monthIndex] = expense;
    }

    const series: SeriesLineOptions[] = Object.entries(porCard).map(
      ([name, data]) => ({
        type: "line",
        name,
        data,
      })
    );

    return series;
  };

  return {
    loadSummary,
    barChartOptions,
    pieChartTotalOptions,
    pieChartTotalExpenseCardsOptions,
    lineMonthlyExpenseChart,
  };
}
