<template>
    <div>
        <v-dialog v-model="showDialog" width="600" persistent>
            <v-card>
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2">Return Book</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon @click="showDialog = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-form @submit.prevent="">
                        <InputText
                            v-model="book.isbn"
                            label="ISBN"
                            readonly="readonly"
                        />
                        <InputText
                            v-model="book.book"
                            label="Book"
                            readonly="readonly"
                        />
                        <InputText
                            v-model="book.borrower"
                            label="Borrower"
                            readonly="readonly"
                        />
                        <InputText
                            v-model="book.department"
                            label="Department"
                            readonly="readonly"
                        />
                        <InputText
                            v-model="book.course"
                            label="Course"
                            readonly="readonly"
                        />
                        <InputText
                            v-model="book.yearLevel"
                            label="Year Level"
                            readonly="readonly"
                        />
                        <InputText
                            v-model="book.phoneNumber"
                            label="Phone Number"
                            readonly="readonly"
                        />

                        <InputText v-model="book.receiver" label="Receiver" />

                        <v-menu
                            ref="menu"
                            v-model="menu"
                            :close-on-content-click="false"
                            :return-value.sync="book.date"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="book.date"
                                    label="Date Received"
                                    readonly
                                    outlined
                                    dense
                                    hide-details=""
                                    class="mb-3"
                                    clearable
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="date" no-title scrollable>
                                <v-spacer></v-spacer>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="menu = false"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.menu.save(date)"
                                >
                                    OK
                                </v-btn>
                            </v-date-picker>
                        </v-menu>

                        <v-btn
                            color="primary"
                            block
                            elevation="0"
                            @click="InsertToReturnBooks()"
                        >
                            Save</v-btn
                        ></v-form
                    >
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import InputText from "../InputText.vue";
import axios from "axios";
export default {
    props: ["value", "bookToReturn"],
    components: {
        InputText,
    },
    data: () => ({
        book: {},
        date: null,
        menu: false,
    }),

    methods: {
        InsertToReturnBooks() {
            axios({
                method: "post",
                url: "/api/insert_to_return_books",
                data: this.book,
            }).then((res) => {
                this.showDialog = false;
                this.$emit("get-borrowed-books");
            });
        },
    },

    computed: {
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
            this.book.isbn = this.bookToReturn.isbn;
            this.book.book = this.bookToReturn.title;
            this.book.student_id = this.bookToReturn.student_id;
            let suffix;
            if (this.bookToReturn.suffix == null) {
                suffix = "";
            } else {
                suffix = this.bookToReturn.suffix;
            }
            this.book.borrower =
                this.bookToReturn.first_name +
                " " +
                this.bookToReturn.middle_name +
                " " +
                this.bookToReturn.last_name +
                " " +
                suffix;
            this.book.department = this.bookToReturn.department;
            this.book.course = this.bookToReturn.course;
            this.book.yearLevel = this.bookToReturn.year;
            this.book.phoneNumber = this.bookToReturn.phone_number;
        },
    },
};
</script>

<style lang="scss" scoped></style>
