<template>
    <div>
        <h1>All Books</h1>
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
                <!-- <v-col cols="3" class="pr-0">
                    <v-text-field
                        placeholder="Searh Books"
                        outlined
                        dense
                        v-model="search_category"
                        hide-details=""
                    ></v-text-field>
                </v-col> -->
            </v-row>
            <v-spacer></v-spacer>
            <div>
                <v-btn color="primary" elevation="0" dark @click="bookInsert()"
                    ><v-icon>mdi-plus</v-icon> Add New Books</v-btn
                >
            </div>
        </div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">Book ID</th>
                            <th class="text-left">Title</th>
                            <th class="text-left">Author</th>
                            <th class="text-left">Publisher</th>
                            <th class="text-left">ISBN</th>
                            <th class="text-left">Shelf Number</th>
                            <th class="text-left">Status</th>
                            <th class="text-left"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filteredBooks" :key="item.id">
                            <td>{{ item.book_id }}</td>
                            <td>{{ item.title }}</td>
                            <td>{{ item.author }}</td>
                            <td>{{ item.publisher }}</td>
                            <td>{{ item.isbn }}</td>
                            <td>{{ item.shelf_no }}</td>
                            <td>
                                <v-chip
                                    :color="
                                        item.status == 'Available'
                                            ? 'green'
                                            : 'red'
                                    "
                                    dark
                                    >{{ item.status }}</v-chip
                                >
                            </td>
                            <td>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="bookShowInfo(item)"
                                >
                                    <v-icon>mdi-eye-circle-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="bookUpdate(item)"
                                >
                                    <v-icon>mdi-pencil-circle-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="bookDelete(item)"
                                >
                                    <v-icon>mdi-delete-circle-outline</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>

        <BooksDialog
            v-model="bookInsertDialog"
            @get-books="getBooks()"
            title="Add Book"
            btn="Save"
        />
        <BooksDialog
            v-model="bookUpdateDialog"
            @get-books="getBooks()"
            title="Update Book"
            btn="Update"
            :items="bookInformation"
        />

        <BookInfoDialog v-model="bookInfoDialog" :items="bookInformation" />
    </div>
</template>

<script scoped>
import axios from "axios";
import { mapActions, mapState } from "vuex";
import BooksDialog from "../components/Dialogs/BooksDialog.vue";
import BookInfoDialog from "../components/Dialogs/BookInfoDialog.vue";
export default {
    components: {
        BooksDialog,
        BookInfoDialog,
    },
    data: () => ({
        bookInsertDialog: false,
        bookUpdateDialog: false,
        bookInfoDialog: false,

        search: null,
        books: [],
        bookInformation: null,
    }),

    methods: {
        ...mapActions(["getBooks"]),

        bookShowInfo(item) {
            this.bookInfoDialog = true;
            this.bookInformation = item;
        },

        bookInsert() {
            this.bookInsertDialog = true;
        },

        bookUpdate(item) {
            this.bookUpdateDialog = true;
            this.bookInformation = item;
        },

        bookDelete(item) {
            this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        method: "post",
                        url: "/api/delete_book",
                        data: item,
                    }).then((res) => {
                        this.$swal(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                        this.getBooks();
                    });
                }
            });
        },
    },

    computed: {
        ...mapState(["allBooks"]),

        filteredBooks() {
            if (this.search) {
                return this.allBooks.filter((element) => {
                    return (
                        element.title
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.publisher
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.isbn
                            .toUpperCase()
                            .includes(this.search.toUpperCase())
                    );
                });
            } else {
                return this.allBooks;
            }
        },
    },

    mounted() {
        this.getBooks();
    },
};
</script>

<style lang="scss" scoped></style>
