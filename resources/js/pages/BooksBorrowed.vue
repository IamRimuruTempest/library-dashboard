<template>
    <div>
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
                            <th class="text-left">ISBN</th>
                            <th class="text-left">Book</th>
                            <th class="text-left">Date Borrowed</th>
                            <th class="text-left">Borrower</th>
                            <th class="text-left">Department</th>
                            <th class="text-left">Course</th>
                            <th class="text-left">Year Level</th>
                            <th class="text-left">Phone Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in filteredBorrowedBooks"
                            :key="item.id"
                        >
                            <td class="">
                                <v-checkbox
                                    v-model="selected"
                                    :value="item.id"
                                    @click="bookBorrowedChange(item)"
                                ></v-checkbox>
                            </td>
                            <td>{{ item.isbn }}</td>
                            <td>{{ item.title }}</td>
                            <td>{{ item.updated_at }}</td>
                            <td>
                                {{ item.first_name }} {{ item.middle_name }}
                                {{ item.last_name }} {{ item.suffix }}
                            </td>
                            <td>{{ item.department }}</td>
                            <td>{{ item.course }}</td>
                            <td>{{ item.year }}</td>
                            <td>{{ item.phone_number }}</td>
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

import { mapActions, mapState } from "vuex";
export default {
    components: {
        BookBorrowedDialog,
        BookToReturnDialog,
    },
    data: () => ({
        borrowedBooksDialog: false,
        bookToReturnDialog: false,
        search: null,
        borrowedBooks: [],
        bookToReturn: {},
        selected: false,
        search: "",
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
    },

    created() {
        this.getBorrowedBooks();
        this.getBooks();
    },
};
</script>

<style lang="scss" scoped></style>
