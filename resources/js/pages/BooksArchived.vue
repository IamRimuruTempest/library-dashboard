<template>
    <div>
        <h1>Archived Books</h1>
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
        </div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">Book ID</th>
                            <th class="text-left">Title</th>
                            <th class="text-left">Author</th>
                            <th class="text-left">ISBN</th>
                            <th class="text-left"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filteredBooks" :key="item.id">
                            <td>{{ item.book_id }}</td>
                            <td>{{ item.title }}</td>
                            <td>{{ item.author }}</td>
                            <td>{{ item.isbn }}</td>

                            <td>
                                <v-btn
                                    @click="restoreBook(item.id)"
                                    icon
                                    color="blue"
                                >
                                    <v-icon>mdi-file-restore</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>
    </div>
</template>

<script scoped>
import axios from "axios";
import { mapActions, mapState } from "vuex";
export default {
    components: {},
    data: () => ({
        search: null,
        books: [],
        bookInformation: null,
    }),

    methods: {
        getArchivedBooks() {
            axios({
                method: "post",
                url: "/api/get_archived_books",
            }).then((res) => {
                console.log(res.data);
                this.books = res.data;
            });
        },

        restoreBook(id) {
            this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, restore it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        method: "post",
                        url: "/api/restore_book",
                        data: { id: id },
                    }).then((res) => {
                        this.$swal(
                            "Restore!",
                            "Your file has been restored.",
                            "success"
                        );
                        this.getArchivedBooks();
                    });
                }
            });
        },
    },

    computed: {
        ...mapState(["allBooks"]),

        filteredBooks() {
            if (this.search) {
                return this.books.filter((element) => {
                    return (
                        element.title
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.author
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.isbn
                            .toUpperCase()
                            .includes(this.search.toUpperCase())
                    );
                });
            } else {
                return this.books;
            }
        },
    },

    mounted() {
        this.getArchivedBooks();
    },
};
</script>

<style lang="scss" scoped></style>
