<template>
    <div>
        <h1>Dashboard</h1>
        <v-row no-gutters>
            <v-col cols="8">
                <div
                    class="d-flex align-center justify-space-betwween"
                    style="gap: 1rem"
                >
                    <v-card width="100%" height="8rem" color="red"
                        ><p>{{ allBooksLength }}</p></v-card
                    >
                    <v-card width="100%" height="8rem" color="red"
                        ><h3>{{ borrowedToday.length }}</h3></v-card
                    >
                    <v-card width="100%" height="8rem" color="red"
                        ><p>{{ returnedToday.length }}</p></v-card
                    >
                </div>
                <div class="mt-6">
                    <apexchart
                        type="bar"
                        width="100%"
                        height="350"
                        :options="chartOptions"
                        :series="chartSeries"
                    ></apexchart>
                </div>
            </v-col>
            <v-col cols="4" style="height: 100vh">
                <v-card class="ml-4 mb-4 pa-5" height="50%">
                    <v-card-title class="px-0 pt-0 pb-2"
                        >Recent Borrowed Books</v-card-title
                    >
                    <v-simple-table>
                        <thead>
                            <th class="text-left">Book</th>
                            <th class="text-left">Borrower</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-0">Heloge gekgwo geo gwe</td>
                                <td class="px-0">Heloge gekgwo geo gwe</td>
                            </tr>
                        </tbody>
                    </v-simple-table>
                </v-card>
                <v-card class="ml-4" height="50%" color="red"
                    ><p>hai</p></v-card
                >
            </v-col>
        </v-row>
    </div>
</template>

<script scoped>
import moment from "moment";
export default {
    data: () => ({
        allBorrowedBooks: [],
        borrowedToday: [],
        allBooksLength: [],
        returnedToday: [],
        monthsArray: [],
        series: [
            {
                name: "Books Borrowed",
                data: [],
            },
            {
                name: "Books Returned",
                data: [],
            },
            {
                name: "Books Added",
                data: [],
            },
        ],
        chartOptions: {
            chart: {
                type: "bar",
                height: 350,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "55%",
                    endingShape: "rounded",
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ["transparent"],
            },
            xaxis: {
                categories: [],
            },
            title: {
                text: "BOOKS STATISTICS",
            },
            fill: {
                opacity: 1,
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " Books";
                    },
                },
            },
        },

        today: new Date(),
    }),

    methods: {
        getBorrowedBooks() {
            axios({
                method: "post",
                url: "/api/borrowed_books",
            }).then((res) => {
                this.borrowedBooksLength = res.data.length;
            });
        },

        getAllBorrowedBooks() {
            axios({
                method: "post",
                url: "/api/all_borrowed_books",
            }).then((res) => {
                const formattedToday = moment(this.today).format("MMM DD YYYY");
                for (const element of res.data) {
                    const formattedCreatedAt = moment(
                        element.date_borrowed
                    ).format("MMM DD YYYY");
                    if (formattedCreatedAt === formattedToday) {
                        this.borrowedToday.push(element);
                    }
                }

                const monthObj = this.monthsArray.reduce((obj, month) => {
                    obj[month] = [];
                    return obj;
                }, {});

                for (const item of res.data) {
                    const tmpDate = moment(item.created_at).format("MMM YYYY");
                    if (tmpDate in monthObj) {
                        monthObj[tmpDate].push(item);
                    }
                }

                for (const month in monthObj) {
                    this.series[0].data.push(monthObj[month].length);
                }
            });
        },

        getAllReturnedBooks() {
            axios({
                method: "post",
                url: "/api/get_returned_books",
            }).then((res) => {
                const formattedToday = moment(this.today).format("MMM DD YYYY");
                for (const element of res.data) {
                    const formattedCreatedAt = moment(
                        element.date_received
                    ).format("MMM DD YYYY");
                    if (formattedCreatedAt === formattedToday) {
                        this.returnedToday.push(element);
                    }
                }
                const monthObj = this.monthsArray.reduce((obj, month) => {
                    obj[month] = [];
                    return obj;
                }, {});

                for (const item of res.data) {
                    const tmpDate = moment(item.created_at).format("MMM YYYY");
                    if (tmpDate in monthObj) {
                        monthObj[tmpDate].push(item);
                    }
                }

                for (const month in monthObj) {
                    this.series[1].data.push(monthObj[month].length);
                }
            });
        },

        getAllAddedBooks() {
            axios({
                method: "post",
                url: "/api/books",
            }).then((res) => {
                this.allBooksLength = res.data.length;
                const monthObj = this.monthsArray.reduce((obj, month) => {
                    obj[month] = [];
                    return obj;
                }, {});

                for (const item of res.data) {
                    const tmpDate = moment(item.created_at).format("MMM YYYY");
                    if (tmpDate in monthObj) {
                        monthObj[tmpDate].push(item);
                    }
                }

                for (const month in monthObj) {
                    this.series[2].data.push(monthObj[month].length);
                }
            });
        },

        getLast12Months() {
            let currentDate = new Date();
            // this.monthsArray = [];

            // Loop through the last 12 months
            for (let i = 0; i < 12; i++) {
                // Get the month and year for each iteration
                let month = currentDate.getMonth();
                let year = currentDate.getFullYear();

                // Format the month and year
                let formattedMonth = currentDate.toLocaleString("default", {
                    month: "short",
                });
                let formattedYear = year;

                // Add the formatted month and year to the array
                this.monthsArray.unshift(formattedMonth + " " + formattedYear);

                // Decrement the month and year for the next iteration
                currentDate.setMonth(month - 1);
                if (month === 0) {
                    currentDate.setFullYear(year - 1);
                }
            }

            // this.monthsArray.map((element) => {
            //     this.chartOptions.xaxis.categories.push(element);
            //     let tmpArr = [...this.chartOptions.xaxis.categories];
            //     this.chartOptions.xaxis.categories = [...tmpArr];
            // });
            this.chartOptions.xaxis.categories = this.monthsArray;
        },
    },

    computed: {
        chartSeries() {
            // Map the series data to a new array of objects
            return this.series.map((series) => {
                return {
                    name: series.name,
                    data: [...series.data],
                };
            });
        },
    },

    created() {
        this.getLast12Months();
        this.getAllBorrowedBooks();
        this.getAllReturnedBooks();
        this.getAllAddedBooks();
    },

    mounted() {
        this.getBorrowedBooks();
    },
};
</script>

<style lang="scss" scoped></style>
