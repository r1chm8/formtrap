<script>
    import { Line } from 'vue-chartjs'
    import Chart from 'chart.js'

    export default {
        extends: Line,

        props: {
            chartData: {
                type: Object,
                default: null
            }
        },

        data() {
            return {
                data: {
                    datasets: [{
                        data: [ 20, 22, 19, 22, 24, 26, 18 ],
                        pointRadius: 4,
                        borderColor: '#711ca5',
                        pointBorderColor: '#ffffff',
                        backgroundColor: 'transparent',
                        pointBackgroundColor: '#711ca5',
                    }],

                    labels: [ 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun' ],
                },

                options: {
                    maintainAspectRatio: false,
                    defaultFontColor: Chart.defaults.global.defaultFontColor = '#22292f',
                    defaultFontFamily: Chart.defaults.global.defaultFontFamily = 'Source Sans Pro',

                    legend: {
                        display: false
                    },

                    scales: {
                        yAxes: [{
                            ticks: {
                                stepSize: 5,
                            }
                        }]
                    },

                    animation: {
                        duration: 600
                    }
                }
            }
        },

        mounted() {
            this.renderChart(this.data, this.options);
        }
    }
</script>

<style>

</style>
