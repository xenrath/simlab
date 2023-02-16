"use strict";

var ctx = document.getElementById("myChart2").getContext("2d");
new Chart(ctx, {
  type: "bar",
  data: {
    labels: [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
    ],
    datasets: [
      {
        label: "Statistics",
        data: [460, 458, 330, 502, 430, 610, 488],
        borderWidth: 2,
        backgroundColor: "#6777ef",
        borderColor: "#6777ef",
        borderWidth: 2.5,
        pointBackgroundColor: "#ffffff",
        pointRadius: 4,
      },
    ],
  },
  options: {
    legend: {
      display: false,
    },
    scales: {
      yAxes: [
        {
          gridLines: {
            drawBorder: false,
            color: "#f2f2f2",
          },
          ticks: {
            beginAtZero: true,
            stepSize: 150,
          },
        },
      ],
      xAxes: [
        {
          ticks: {
            display: false,
          },
          gridLines: {
            display: false,
          },
        },
      ],
    },
  },
});
