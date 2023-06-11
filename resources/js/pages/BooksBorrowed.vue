<template>
    <div>
        <v-dialog v-model="penaltyDialog" width="400">
            <v-card>
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2"
                        >Update Penalty</v-toolbar-title
                    >

                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon @click="penaltyDialog = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-form
                        @submit.prevent="UpdateStatus"
                        enctype="multipart/form-data"
                        ref="form"
                    >
                        <v-autocomplete
                            v-model="status"
                            label="Status"
                            :items="['Damage', 'Lost']"
                            clearable
                            hide-details
                            outlined
                            dense
                            class="pt-0 mb-3"
                        ></v-autocomplete>

                        <v-btn
                            @click="updateStatus()"
                            color="primary"
                            block
                            elevation="0"
                        >
                            Update</v-btn
                        ></v-form
                    >
                </v-card-text>
            </v-card>
        </v-dialog>
        <h1>Borrowed Books</h1>
        <div class="d-flex align-center">
            <v-row>
                <v-col cols="5" class="pr-0">
                    <v-text-field
                        placeholder="Search Books"
                        outlined
                        dense
                        hide-details=""
                        clearable
                        v-model="search"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-spacer></v-spacer>
            <div>
                <v-btn
                    color="primary"
                    elevation="0"
                    dark
                    @click="borrowedBooksDialog = true"
                    ><v-icon>mdi-plus</v-icon> Add Books</v-btn
                >
            </div>
        </div>
        <div>
            <!-- <img
                src="http//:localhost:8000/storage/95195012305.jpg"
                width="120px"
                height="100%"
                class="ma-5"
                alt=""
            /> -->
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Index</th>
                            <th class="text-left">ISBN</th>
                            <th class="text-left">Book</th>
                            <th class="text-left">Date Borrowed</th>
                            <th class="text-left">Borrower</th>
                            <th class="text-left">Department</th>
                            <th class="text-left">Course</th>
                            <th class="text-left">Year Level</th>
                            <th class="text-left">Phone Number</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Penalty</th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in filteredBorrowedBooks"
                            :key="item.borrowed_id"
                        >
                            <td class="">
                                <v-checkbox
                                    v-model="selected"
                                    :value="item.borrowed_id"
                                    @click="bookBorrowedChange(item)"
                                ></v-checkbox>
                            </td>
                            <td>{{ item.borrowed_id }}</td>
                            <td>{{ item.isbn }}</td>
                            <td>{{ item.title }}</td>
                            <td>{{ item.date_borrowed }}</td>
                            <td>
                                {{ item.first_name }} {{ item.middle_name }}
                                {{ item.last_name }} {{ item.suffix }}
                            </td>
                            <td>{{ item.department }}</td>
                            <td>{{ item.course }}</td>
                            <td>{{ item.year }}</td>
                            <td>{{ item.phone_number }}</td>
                            <td>{{ item.penalty }}</td>
                            <td style="color: red">
                                &#8369;
                                {{
                                    getPenalty(
                                        item.date_borrowed,
                                        item.price,
                                        item.penalty
                                    )
                                }}
                            </td>
                            <td>
                                <v-btn
                                    @click="opnUpdateStatus(item.borrowed_id)"
                                    icon
                                    color="blue"
                                >
                                    <v-icon>mdi-pencil-circle-outline</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>

        <BookBorrowedDialog
            v-model="borrowedBooksDialog"
            :borrowedBooks="borrowedBooks"
            @get-borrowed-books="getBorrowedBooks()"
        />

        <BookToReturnDialog
            v-model="bookToReturnDialog"
            :bookToReturn="bookToReturn"
            @get-borrowed-books="getBorrowedBooks()"
        />
    </div>
</template>

<script>
import BookBorrowedDialog from "../components/Dialogs/BookBorrowedDialog";
import BookToReturnDialog from "../components/Dialogs/BookToReturnDialog.vue";
import axios from "axios";
import moment from "moment";
import { mapActions, mapState } from "vuex";
export default {
    components: {
        BookBorrowedDialog,
        BookToReturnDialog,
    },
    data: () => ({
        borrowedBooksDialog: false,
        bookToReturnDialog: false,
        penaltyDialog: false,
        search: null,
        borrowedBooks: [],
        bookToReturn: {},
        selected: false,
        search: "",
        date_today: new Date(),

        rules: [
            (value) => {
                if (value) return true;

                return "";
            },
        ],

        id: null,
        status: null,
    }),

    methods: {
        ...mapActions(["getBooks"]),

        getBorrowedBooks() {
            axios({
                method: "post",
                url: "/api/borrowed_books",
            }).then((res) => {
                this.borrowedBooks = res.data;
            });
        },

        bookBorrowedChange(item) {
            this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Proceed!",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.bookToReturnDialog = true;
                    this.bookToReturn = item;
                    this.selected = false;
                } else {
                    this.selected = false;
                }
            });
        },

        getPenalty(date, price, status) {
            let penalty;
            console.log(status);

            if (status == "Lost") {
                penalty = price;
                return penalty;
            } else if (status == "Damage") {
                penalty = price * 0.5;
                return penalty;
            } else if (status == null || status == "") {
                const endDate = moment(this.date_today).format(
                    "YYYY-MM-DD HH:mm"
                );
                const startDate = moment(date).format("YYYY-MM-DD HH:mm");
                // console.log(endDate, startDate);

                const oneDay = 24 * 60 * 60 * 1000;
                const parsedStartDate = new Date(startDate);
                const parsedEndDate = new Date(endDate);
                const differenceInDays = Math.round(
                    Math.abs((parsedStartDate - parsedEndDate) / oneDay)
                );

                // console.log(differenceInDays, "test");
                // return differenceInDays >= 1;

                penalty =
                    differenceInDays == 2
                        ? 50
                        : differenceInDays == 3
                        ? 100
                        : differenceInDays == 4
                        ? 250
                        : differenceInDays >= 5
                        ? 500
                        : 0;

                return penalty;
            }
        },

        opnUpdateStatus(id) {
            this.penaltyDialog = true;
            this.id = id;
        },

        updateStatus() {
            axios({
                method: "post",
                url: "/api/update_borrowed_status",
                data: {
                    id: this.id,
                    status: this.status,
                },
            }).then((res) => {
                console.log(res.data);
                this.getBorrowedBooks();
                this.penaltyDialog = false;
                this.$swal({
                    icon: "success",
                    title: "You have successfully updated the status!",
                    text: "The penalty has changed based to the status",
                    showConfirmButton: false,
                    timer: 2000,
                });
            });
        },
    },

    computed: {
        ...mapState(["allBooks"]),

        filteredBorrowedBooks() {
            if (this.search) {
                return this.borrowedBooks.filter((element) => {
                    return (
                        element.title
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.first_name
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.last_name
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.student_id
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.isbn
                            .toUpperCase()
                            .includes(this.search.toUpperCase())
                    );
                });
            } else {
                return this.borrowedBooks;
            }
        },

        createdAt() {},
    },

    created() {
        this.getBorrowedBooks();
        this.getBooks();
    },
};
</script>

<style lang="scss" scoped></style>
