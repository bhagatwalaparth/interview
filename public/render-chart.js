const isProbabilityeFullFill = sumOfCurrentProbability == 100;

if (isProbabilityeFullFill) {
    const labels = prizes.map((a) => `${a.title} ${a.probability}%`);
    const probabilityData = prizes.map((a) => a.probability);

    const probabilityChartCTX = document
        .getElementById("probabilityChart")
        .getContext("2d");

    const data = {
        labels: labels,
        datasets: [
            {
                label: "PROBABILITY",
                data: probabilityData,
                borderWidth: 1,
            },
        ],
    };

    const config = {
        type: "doughnut",
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "top",
                },
            },
        },
    };

    new Chart(probabilityChartCTX, config);

    const isAwaredPrizeAvailable = sumOfAwaredPrize > 0;

    if (isAwaredPrizeAvailable) {
        const actualLabels = prizes.map((a) => {
            const percentage = ((a.awarded / sumOfAwaredPrize) * 100).toFixed(
                2
            );
            return `${a.title} ${percentage}%`;
        });

        const actualData = prizes.map((a) => {
            return ((a.awarded / sumOfAwaredPrize) * 100).toFixed(2);
        });

        const actualChartCTX = document
            .getElementById("awardedChart")
            .getContext("2d");

        const actualChartData = {
            labels: actualLabels,
            datasets: [
                {
                    label: "PROBABILITY",
                    data: actualData,
                    borderWidth: 1,
                },
            ],
        };

        const actualChartConfig = {
            type: "doughnut",
            data: actualChartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                    },
                },
            },
        };

        new Chart(actualChartCTX, actualChartConfig);
    }
}
