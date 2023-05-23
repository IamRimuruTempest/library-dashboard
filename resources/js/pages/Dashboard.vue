<template>
    <div>
        <h1>Dashboard</h1>
        <v-row no-gutters>
            <v-col cols="8">
                <div
                    class="d-flex align-center justify-space-betwween"
                    style="gap: 1rem"
                >
                    <v-card
                        width="100%"
                        height="8rem"
                        color="#dd4b39"
                        class="d-flex align-left justify-center flex-column pa-12"
                    >
                        <h1
                            class="text-h3 font-weight-black"
                            style="color: #fff"
                        >
                            {{ allBooks.length }}
                        </h1>
                        <p class="text-uppercase" style="color: #fff">
                            All Books
                        </p>
                    </v-card>
                    <v-card
                        width="100%"
                        height="8rem"
                        color="#00a65a"
                        class="d-flex align-left justify-center flex-column pa-10"
                        ><h1
                            class="text-h3 font-weight-black"
                            style="color: #fff"
                        >
                            {{ borrowedToday.length }}
                        </h1>
                        <p class="text-uppercase" style="color: #fff">
                            Today's Borrowed Books
                        </p>
                    </v-card>
                    <v-card
                        width="100%"
                        height="8rem"
                        color="#f39c12"
                        class="d-flex align-left justify-center flex-column pa-10"
                    >
                        <!-- <p>{{ returnedToday.length }}</p> -->
                        <h1
                            class="text-h3 font-weight-black"
                            style="color: #fff"
                        >
                            {{ returnedToday.length }}
                        </h1>
                        <p class="text-uppercase" style="color: #fff">
                            Today's Returned Books
                        </p>
                    </v-card>
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
                <v-card class="ml-4 mb-4 pa-5" height="50%" color="#dddcee">
                    <v-card-title class="px-0 pt-0 pb-2"
                        >Recent Borrowed Books</v-card-title
                    >
                    <v-simple-table style="background-color: #dddcee">
                        <thead>
                            <th class="text-left">Name</th>
                            <th class="text-left">Borrower</th>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) of sortedBorrowedBooks"
                                :key="index"
                            >
                                <td class="px-0">
                                    {{ item.title }}
                                </td>
                                <td class="px-0">
                                    {{ item.first_name }}
                                    {{ item.middle_name }} {{ item.last_name }}
                                    {{ item.suffix }}
                                </td>
                            </tr>
                        </tbody>
                    </v-simple-table>
                </v-card>
                <v-card class="ml-4 mb-4 pa-5" height="50%" color="#dddcee">
                    <v-card-title class="px-0 pt-0 pb-2"
                        >Recent Returned Books</v-card-title
                    >
                    <v-simple-table style="background-color: #dddcee">
                        <thead>
                            <th class="text-left">Name</th>
                            <th class="text-left">Borrower</th>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) of sortedReturnedBooks"
                                :key="index"
                            >
                                <td class="px-0">
                                    {{ item.title }}
                                </td>
                                <td class="px-0">
                                    {{ item.first_name }}
                                    {{ item.middle_name }}
                                    {{ item.last_name }}
                                    {{ item.suffix }}
                                </td>
                            </tr>
                        </tbody>
                    </v-simple-table>
                </v-card>
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
        allBooks: [],
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
        recentBorrowedBooks: [],
        recentReturnedBooks: [],
        limit: 8,
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
                this.recentBorrowedBooks = res.data;
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
                    const tmpDate = moment(item.date_borrowed).format(
                        "MMM YYYY"
                    );
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
                this.recentReturnedBooks = res.data;
                const formattedToday = moment(this.today).format("MMM DD YYYY");
                for (const element of res.data) {
                    const formattedCreatedAt = moment(
                        element.created_at
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
                this.allBooks = res.data;
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

        sortedBorrowedBooks() {
            const sortedItems = this.recentBorrowedBooks
                .slice()
                .sort(
                    (b, a) =>
                        new Date(a.date_borrowed) - new Date(b.date_borrowed)
                );
            return sortedItems.slice(0, this.limit);
        },

        sortedReturnedBooks() {
            const sortedItems = this.recentReturnedBooks
                .slice()
                .sort(
                    (b, a) => new Date(a.created_at) - new Date(b.created_at)
                );
            return sortedItems.slice(0, this.limit);
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
