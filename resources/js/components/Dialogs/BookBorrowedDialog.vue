<template>
    <div>
        <v-dialog v-model="showDialog" width="600" persistent>
            <v-card>
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2">Add Books</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon @click="closeDialog()">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-form
                        @submit.prevent="InsertBookToBorrowedBook"
                        enctype="multipart/form-data"
                        ref="form"
                    >
                        <InputText
                            v-model="isbn"
                            label="ISBN"
                            v-mask="'###-#-##-######-#'"
                            :rules="rules"
                            @keyup.enter.native="getBook()"
                        />

                        <InputText
                            v-model="title"
                            label="Book"
                            readonly="readonly"
                            :rules="rules"
                        />

                        <InputText
                            v-model="borrowerID"
                            label="Borrower ID"
                            :rules="rules"
                            @keyup.enter.native="getAccount()"
                        />

                        <InputText
                            v-model="borrower"
                            label="Borrowers"
                            readonly="readonly"
                            :rules="rules"
                        />

                        <InputText
                            v-model="department"
                            label="Department"
                            readonly="readonly"
                            :rules="rules"
                        />

                        <InputText
                            v-model="course"
                            label="Course"
                            readonly="readonly"
                            :rules="rules"
                        />

                        <InputText
                            v-model="yearLevel"
                            label="Year Level"
                            readonly="readonly"
                            :rules="rules"
                        />

                        <InputText
                            v-model="phoneNumber"
                            label="Phone Number"
                            readonly="readonly"
                            :rules="rules"
                        />
                    </v-form>
                    <v-btn
                        color="primary"
                        block
                        elevation="0"
                        @click="InsertBookToBorrowedBook()"
                    >
                        Save</v-btn
                    >
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import InputText from "../InputText.vue";
import axios from "axios";
import { mapActions, mapState } from "vuex";
export default {
    props: ["value", "borrowedBooks"],
    components: {
        InputText,
    },
    data: () => ({
        book: {},
        isbn: null,
        title: null,
        borrowerID: null,
        borrower: null,
        department: null,
        course: null,
        yearLevel: null,
        phoneNumber: null,

        rules: [
            (value) => {
                if (value) return true;

                return "";
            },
        ],
    }),

    methods: {
        ...mapActions(["getBooks", "getAccounts"]),
        getBook() {
            this.checkBorrowedBooks(this.isbn);
        },

        checkBorrowedBooks(isbn) {
            const borrowedBook = this.borrowedBooks.filter((element) => {
                if (element.isbn == isbn && element.status == "Unavailable") {
                    return element;
                }
            });

            if (borrowedBook.length != 0) {
                this.$swal({
                    icon: "info",
                    title: "Oops...",
                    text: "This book in unvailable as of the moment!",
                });
            } else {
                this.checkAllBooks(isbn);
            }
        },

        checkAllBooks(isbn) {
            const tmpAllBooks = this.allBooks.filter((element) => {
                if (element.isbn == isbn) {
                    return element;
                }
            });

            if (tmpAllBooks.length >= 1) {
                this.title = tmpAllBooks[0].title;
            } else {
                this.$swal({
                    icon: "info",
                    title: "Oops...",
                    text: "The book  is no where to be found!",
                });

                this.title = null;
            }
        },

        getAccount() {
            const account = this.allAccounts.filter((element) => {
                if (element.student_id == this.borrowerID) {
                    return element;
                }
            });

            if (account.length >= 1) {
                let tmpSuffix = "";
                if (account[0].suffix == null) {
                    tmpSuffix = "";
                } else {
                    tmpSuffix = account[0].suffix;
                }
                this.borrower =
                    account[0].first_name +
                    " " +
                    account[0].middle_name +
                    " " +
                    account[0].last_name +
                    " ";
                this.department = account[0].department;
                this.course = account[0].course;
                this.yearLevel = account[0].year;
                this.phoneNumber = account[0].phone_number;
            } else {
                this.$swal({
                    icon: "info",
                    title: "Oops...",
                    text: "The account is no where to be found!",
                });

                this.title = null;
            }
        },

        InsertBookToBorrowedBook() {
            const admin = this.loggedInUser.name;
            if (this.$refs.form.validate()) {
                axios({
                    method: "post",
                    url: "/api/insert_borrowed_book",
                    data: {
                        isbn: this.isbn,
                        student_id: this.borrowerID,
                        admin: admin,
                    },
                }).then((res) => {
                    console.log(res.data);
                    this.showDialog = false;
                    this.$emit("get-borrowed-books");
                    this.$refs.form.reset();
                });
            }
        },

        closeDialog() {
            // this.$refs.form.reset();
            this.showDialog = false;
        },
    },

    computed: {
        ...mapState(["allBooks", "allAccounts", "loggedInUser"]),
        showDialog: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit("input", value);
            },
        },
    },

    created() {},

    watch: {
        showDialog() {
            this.getBooks();
            this.getAccounts();
        },
    },
};
</script>

<style lang="scss" scoped></style>
